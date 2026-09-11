import assert from 'node:assert/strict'
import { mkdir, readFile } from 'node:fs/promises'
import { createRequire } from 'node:module'
import path from 'node:path'
import { fileURLToPath } from 'node:url'

// Run from Filament, where Playwright and Pest's axe bundle are installed.
const { chromium } = createRequire(path.join(process.cwd(), 'package.json'))(
    'playwright',
)
const demoDirectory = fileURLToPath(new URL('../../../', import.meta.url))
const manifest = JSON.parse(
    await readFile(
        path.join(demoDirectory, 'public/build/manifest.json'),
        'utf8',
    ),
)
const axe = await readFile(
    path.join(
        process.cwd(),
        'vendor/pestphp/pest-plugin-browser/resources/js/axe.min.js',
    ),
    'utf8',
)
const baseUrl = process.env.INERTIA_DEMO_URL || 'http://127.0.0.1:8000'
const serverRendered = ['true', '1'].includes(process.env.INERTIA_TEST_SSR)
const artifactDirectory = process.env.ARTIFACT_DIRECTORY
const frameworks = [
    ['vue', '/inertia-workbench', 'resources/js/inertia-workbench.js'],
    ['react', '/react-inertia-workbench', 'resources/js/inertia-react.jsx'],
    ['svelte', '/svelte-inertia-workbench', 'resources/js/inertia-svelte.js'],
].filter(([name]) => !process.env.FRAMEWORK || process.env.FRAMEWORK === name)
const browser = await chromium.launch({ headless: true })
let passed = 0
let failed = 0

function gate() {
    let release
    const promise = new Promise((resolve) => {
        release = resolve
    })
    return { promise, release }
}

async function assertNoAccessibilityIssues(page, phase) {
    // Deliberately do not await networkidle: module/deferred responses are held.
    const violations = await page.evaluate(
        async () => (await window.axe.run()).violations,
    )
    const serious = violations.filter(({ impact }) =>
        ['critical', 'serious'].includes(impact),
    )
    console.log(
        JSON.stringify({
            phase,
            accessibility: violations.map(({ id, impact, nodes }) => ({
                id,
                impact,
                targets: nodes.map(({ target }) => target),
            })),
        }),
    )
    assert.deepEqual(
        serious,
        [],
        `${phase}: serious/critical accessibility violations`,
    )
}

async function capture(page, name) {
    if (!artifactDirectory) return
    await mkdir(artifactDirectory, { recursive: true })
    await page.locator('[data-inertia-stage]').evaluate((stage) => {
        stage.scrollIntoView({ block: 'start' })
        window.scrollBy(0, -80)
    })
    await page.screenshot({ path: path.join(artifactDirectory, `${name}.png`) })
}

async function prepare(colorScheme) {
    const context = await browser.newContext({
        colorScheme,
        viewport: { width: 1280, height: 900 },
        deviceScaleFactor: 2,
    })
    await context.addInitScript(axe)
    await context.addInitScript((theme) => {
        localStorage.setItem('theme', theme)
        const probe = (window.loadingProbe = { hold: false, frames: [] })
        const requestFrame = window.requestAnimationFrame.bind(window)
        // A cached ES module will not issue another HTTP request. Pause the host's
        // existing import scheduling boundary to inspect restored Blade markup.
        window.requestAnimationFrame = (callback) => {
            if (
                probe.hold &&
                callback
                    .toString()
                    .replace(/\s/g, '')
                    .includes('import(moduleUrl)')
            ) {
                probe.frames.push(callback)
                return 0
            }
            return requestFrame(callback)
        }
        probe.release = () => {
            probe.hold = false
            probe.frames.splice(0).forEach((callback) => requestFrame(callback))
        }
    }, colorScheme)
    const page = await context.newPage()
    page.setDefaultTimeout(10000)
    page.setDefaultNavigationTimeout(15000)
    const errors = []
    const diagnostics = []
    page.on('pageerror', (error) => errors.push(error.message))
    page.on('console', (message) => {
        if (message.type() === 'error') diagnostics.push(message.text())
    })
    await page.goto(`${baseUrl}/login`)
    await page.locator('button[type="submit"]').click({ noWaitAfter: true })
    await page
        .getByText('Welcome to the Filament Demo!', { exact: true })
        .waitFor()
    return { context, page, errors, diagnostics }
}

async function assertPending(page) {
    await page.locator('[data-inertia-stage][aria-busy="true"]').waitFor()
    assert.equal(
        await page.locator('[data-inertia-loading]').isVisible(),
        !serverRendered,
    )
    assert.equal(await page.locator('[data-inertia-error]').isVisible(), false)
    assert.equal(
        await page.locator('#inertia-report-title').isVisible(),
        serverRendered,
    )
    assert.equal(
        (await page
            .locator('#filament-inertia')
            .getAttribute('data-server-rendered')) === 'true',
        serverRendered,
    )
    if (!serverRendered) {
        assert.ok(
            (await page.locator('[data-inertia-stage]').boundingBox()).height >=
                192,
            'CSR stage must reserve at least 12rem',
        )
        assert.equal(
            await page.locator('[data-inertia-loading]').getAttribute('role'),
            'status',
        )
        assert.match(
            await page.locator('[data-inertia-loading]').innerText(),
            /Loading page…/,
        )
    }
}

async function assertMounted(page) {
    await page.locator('[data-inertia-stage][aria-busy="false"]').waitFor()
    await page.locator('#inertia-report-title').waitFor()
    assert.equal(
        await page.locator('[data-inertia-loading]').isVisible(),
        false,
    )
    assert.equal(await page.locator('[data-inertia-error]').isVisible(), false)
}

async function run(framework, colorScheme, scenario) {
    const [name, pagePath, entry] = framework
    const label = `${name}-${serverRendered ? 'ssr' : 'csr'}-${colorScheme}-${scenario}`
    const { context, page, errors, diagnostics } = await prepare(colorScheme)
    const moduleGate = gate()
    const deferredGate = gate()
    let moduleRequests = 0
    let deferredRequests = 0
    try {
        await page.route(`**/build/${manifest[entry].file}`, async (route) => {
            moduleRequests++
            if (moduleRequests > 1) return route.continue()
            if (scenario === 'error') return route.abort('failed')
            await moduleGate.promise
            await route.continue()
        })
        await page.route('**/*', async (route) => {
            const request = route.request()
            if (request.headers()['x-inertia-partial-data']) {
                deferredRequests++
                if (scenario === 'mounted') await deferredGate.promise
            }
            await route.fallback()
        })
        // A direct document load avoids confusing Livewire's navigation progress
        // indicator with this independently tested page-loading indicator.
        await page.goto(`${baseUrl}${pagePath}`, {
            waitUntil: 'domcontentloaded',
        })
        await page.waitForFunction(() =>
            document.querySelector('[data-inertia-stage]'),
        )

        if (scenario === 'error') {
            await page.locator('[data-inertia-error]:not([hidden])').waitFor()
            assert.equal(
                await page
                    .locator('[data-inertia-stage]')
                    .getAttribute('aria-busy'),
                'false',
            )
            assert.equal(
                await page.locator('[data-inertia-loading]').isVisible(),
                false,
            )
            assert.equal(
                await page.locator('#inertia-report-title').isVisible(),
                serverRendered,
            )
            await page
                .getByRole('button', { name: 'Reload page', exact: true })
                .waitFor()
            await assertNoAccessibilityIssues(page, label)
            await capture(page, label)
            assert.ok(
                diagnostics.some((message) =>
                    message.includes('Unable to mount the Inertia page.'),
                ),
                'Expected caught-import diagnostic',
            )
            await page.evaluate(() => {
                window.documentBeforeRetry = document
            })
            await page
                .locator('[data-inertia-retry]')
                .click({ noWaitAfter: true })
            await page.waitForFunction(
                () => window.documentBeforeRetry === undefined,
            )
            await assertMounted(page)
            assert.equal(
                await page.evaluate(
                    () => window.documentBeforeRetry === undefined,
                ),
                true,
                'Retry must create a fresh document/module registry',
            )
            assert.equal(moduleRequests, 2)
        } else {
            await assertPending(page)
            await page.evaluate(() => {
                window.originalLoadingRoot =
                    document.querySelector('#filament-inertia')
                window.originalLoadingMarkup =
                    window.originalLoadingRoot.innerHTML
                window.originalLoadingHeading = document.querySelector(
                    '#inertia-report-title',
                )
            })
            await assertNoAccessibilityIssues(page, `${label}-module-held`)
            if (scenario === 'mounted') {
                await capture(
                    page,
                    `${label.replace(/-mounted$/, '')}-module-held`,
                )
            }
            assert.equal(
                moduleRequests,
                1,
                'The renderer import must have started and remain held',
            )

            if (scenario === 'away') {
                await page.evaluate(() => Livewire.navigate('/'))
                await page
                    .getByText('Welcome to the Filament Demo!', { exact: true })
                    .waitFor()
                assert.equal(
                    await page.evaluate(
                        () => window.originalLoadingRoot.isConnected,
                    ),
                    false,
                )
                const detachedMarkup = await page.evaluate(
                    () => window.originalLoadingRoot.innerHTML,
                )
                moduleGate.release()
                await page.waitForTimeout(500)
                assert.equal(await page.locator('#filament-inertia').count(), 0)
                assert.equal(
                    await page.evaluate(
                        () => window.originalLoadingRoot.innerHTML,
                    ),
                    detachedMarkup,
                )
                assert.equal(
                    deferredRequests,
                    0,
                    'A stale renderer must not start Inertia requests',
                )
            } else {
                await page
                    .locator('#refresh-shell')
                    .click({ noWaitAfter: true })
                await page
                    .locator('#shell-refresh-count')
                    .filter({ hasText: '1' })
                    .waitFor()
                await assertPending(page)
                assert.equal(
                    await page.evaluate(
                        () =>
                            window.originalLoadingRoot ===
                            document.querySelector('#filament-inertia'),
                    ),
                    true,
                    'Shell refresh must preserve the pending root',
                )
                moduleGate.release()
                await assertMounted(page)
                // Observe request interception, not deferred results, before
                // asserting that page readiness is independent of those results.
                for (
                    let attempt = 0;
                    deferredRequests < 2 && attempt < 100;
                    attempt++
                )
                    await page.waitForTimeout(20)
                assert.equal(
                    deferredRequests,
                    2,
                    'Both deferred groups must still be blocked',
                )
                await page
                    .getByText('Loading analytics…', { exact: true })
                    .waitFor()
                assert.equal(
                    await page.locator('#deferred-analytics').count(),
                    0,
                )
                if (serverRendered) {
                    assert.equal(
                        await page.evaluate(
                            () =>
                                window.originalLoadingHeading ===
                                document.querySelector('#inertia-report-title'),
                        ),
                        true,
                        'Hydration must retain the SSR heading',
                    )
                }
                await assertNoAccessibilityIssues(
                    page,
                    `${label}-deferred-held`,
                )
                console.log(
                    JSON.stringify({
                        phase: `${label}-deferred-held`,
                        deferredRequests,
                        dom: await page.evaluate(() => ({
                            busy: document
                                .querySelector('[data-inertia-stage]')
                                .getAttribute('aria-busy'),
                            loadingHidden: document.querySelector(
                                '[data-inertia-loading]',
                            ).hidden,
                            heading: document.querySelector(
                                '#inertia-report-title',
                            ).textContent,
                            shellRefreshes: document
                                .querySelector('#shell-refresh-count')
                                .textContent.trim(),
                            analyticsPending: !document.querySelector(
                                '#deferred-analytics',
                            ),
                        })),
                    }),
                )
                await capture(page, `${label}-deferred-held`)
                deferredGate.release()
                await page
                    .locator('#deferred-analytics')
                    .filter({ hasText: '137' })
                    .waitFor()
                await page.evaluate(() => Livewire.navigate('/'))
                await page
                    .getByText('Welcome to the Filament Demo!', { exact: true })
                    .waitFor()
                await page.evaluate(() => {
                    window.loadingProbe.hold = true
                })
                await page.goBack()
                await page.waitForFunction(
                    () => window.loadingProbe.frames.length === 1,
                )
                await assertPending(page)
                assert.equal(
                    await page.evaluate(
                        () =>
                            document.querySelector('#filament-inertia')
                                .innerHTML === window.originalLoadingMarkup,
                    ),
                    true,
                    'SPA cache must restore the original CSR/SSR markup',
                )
                await page.evaluate(() => window.loadingProbe.release())
                await assertMounted(page)
                assert.equal(
                    moduleRequests,
                    1,
                    'The cached-return phase must exercise an already imported module',
                )
            }
        }
        assert.deepEqual(errors, [])
        console.log(`PASS ${label}`)
        passed++
    } catch (error) {
        failed++
        console.log(`FAIL ${label}: ${error.stack}`)
    } finally {
        moduleGate.release()
        deferredGate.release()
        await context.close()
    }
}

try {
    for (const framework of frameworks) {
        for (const colorScheme of ['light', 'dark']) {
            for (const scenario of ['mounted', 'error', 'away'])
                await run(framework, colorScheme, scenario)
        }
    }
} finally {
    await browser.close()
}
console.log(JSON.stringify({ serverRendered, passed, failed }))
process.exitCode = failed ? 1 : 0
