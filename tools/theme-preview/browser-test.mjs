import assert from 'node:assert/strict'
import { mkdirSync } from 'node:fs'
import { resolve } from 'node:path'
import puppeteer from 'puppeteer-core'

const base = process.env.THEME_PREVIEW_URL
if (!base)
    throw new Error(
        'Set THEME_PREVIEW_URL to the running demo with the theme preview installed.',
    )
const artifacts = resolve('.amp/in/artifacts/theme-preview')
mkdirSync(artifacts, { recursive: true })
const browser = await puppeteer.launch({
    executablePath:
        process.env.PUPPETEER_EXECUTABLE_PATH || '/usr/bin/chromium',
    headless: true,
    args: ['--no-sandbox'],
})
const page = await browser.newPage()
await page.setViewport({ width: 1440, height: 1000 })
const errors = []
page.on('pageerror', (error) => errors.push(error.message))
const goto = (path) =>
    page.goto(new URL(path, base).href, { waitUntil: 'networkidle0' })
const selection = () =>
    page.evaluate(() => ({
        theme: document.querySelector('[data-live-demo-theme]').value,
        compact: document.querySelector('[data-live-demo-compact]').checked,
        hosts: [...document.querySelectorAll('link[rel=stylesheet]')]
            .map((el) => el.href)
            .filter((url) => /\/(stock|sharp|soft|noir)(-|\.)/.test(url)),
    }))
const identity = (target) =>
    target.evaluate(() => ({
        primary: getComputedStyle(document.documentElement)
            .getPropertyValue('--primary-500')
            .trim(),
        font: getComputedStyle(document.body).fontFamily,
    }))

try {
    if (process.env.THEME_PREVIEW_LOGIN_URL) {
        await page.goto(process.env.THEME_PREVIEW_LOGIN_URL, {
            waitUntil: 'networkidle0',
        })
    }
    const isolated = await browser.createBrowserContext()
    const other = await isolated.newPage()
    other.on('pageerror', (error) => errors.push(error.message))
    if (process.env.THEME_PREVIEW_SECOND_LOGIN_URL) {
        await other.goto(process.env.THEME_PREVIEW_SECOND_LOGIN_URL, {
            waitUntil: 'networkidle0',
        })
    }
    await goto('/login')
    const stockIdentity = await identity(page)
    assert.equal(
        await page.$eval('[data-live-demo-toolbar-toggle]', (el) =>
            el.getAttribute('aria-expanded'),
        ),
        'false',
    )
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle0' }),
        page.click('button[type=submit]'),
    ])
    await page.screenshot({ path: `${artifacts}/stock-default.png` })
    for (const theme of ['stock', 'sharp', 'soft', 'noir']) {
        for (const compact of [false, true]) {
            await goto(
                `/shop/products?theme=${theme}&compact=${compact ? 1 : 0}`,
            )
            const result = await selection()
            assert.equal(result.theme, theme)
            assert.equal(result.compact, compact)
            assert.equal(
                result.hosts.length,
                1,
                'only one host stylesheet loads',
            )
            assert.match(
                result.hosts[0],
                new RegExp(
                    `/${theme}${compact ? '-compact' : ''}-[A-Za-z0-9_-]+\\.css$`,
                ),
            )
            for (const scheme of ['light', 'dark']) {
                const dark = await page.evaluate(() =>
                    document.documentElement.classList.contains('dark'),
                )
                if (dark !== (scheme === 'dark'))
                    await page.click('[data-live-demo-scheme]')
                await page.waitForFunction(
                    (dark) =>
                        document.documentElement.classList.contains('dark') ===
                        dark,
                    {},
                    scheme === 'dark',
                )
                await page.screenshot({
                    path: `${artifacts}/${theme}${compact ? '-compact' : ''}-${scheme}.png`,
                })
            }
        }
    }

    await goto('/shop/products?theme=sharp&compact=0&probe=keep#retained')
    const livewireResponse = page.waitForResponse(
        (response) =>
            response.request().method() === 'POST' &&
            response.url().includes('/livewire'),
    )
    await page.evaluate(() =>
        [...document.querySelectorAll('button')]
            .find((el) => el.getAttribute('wire:click') === "sortTable('name')")
            .click(),
    )
    assert.equal((await livewireResponse).status(), 200)
    await page.waitForNetworkIdle()
    assert.equal((await selection()).theme, 'sharp')
    await page.evaluate(() => {
        window.liveDemoDocumentMarker = 'old'
    })
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle0' }),
        page.select('[data-live-demo-theme]', 'soft'),
    ])
    assert.equal(new URL(page.url()).searchParams.get('probe'), 'keep')
    assert.equal(new URL(page.url()).hash, '#retained')
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle0' }),
        page.click('[data-live-demo-compact]'),
    ])
    assert.equal((await selection()).compact, true)
    assert.equal(new URL(page.url()).searchParams.get('probe'), 'keep')
    assert.equal(new URL(page.url()).hash, '#retained')
    assert.equal(
        await page.evaluate(() => window.liveDemoDocumentMarker),
        undefined,
        'switch must fully reload',
    )

    await page.evaluate(() => {
        window.liveDemoDocumentMarker = 'spa'
    })
    await page.click('a[href$="/shop/customers"]')
    await page.waitForFunction(() => location.pathname === '/shop/customers')
    await page.waitForNetworkIdle()
    assert.equal(
        await page.evaluate(() => window.liveDemoDocumentMarker),
        'spa',
        'normal navigation stays SPA',
    )
    assert.equal((await selection()).theme, 'soft')

    await goto('/shop/products/create')
    await page.type('input[id="form.name"]', 'Unsaved preview product')
    await page.click('[data-live-demo-theme]')
    await page.waitForNetworkIdle()
    let prompted = false
    page.once('dialog', async (dialog) => {
        prompted = true
        await dialog.dismiss()
    })
    await page.select('[data-live-demo-theme]', 'noir')
    assert.equal(prompted, true)
    assert.equal(
        (await selection()).theme,
        'soft',
        'cancelling restores selected value',
    )
    assert.equal(new URL(page.url()).pathname, '/shop/products/create')
    assert.equal(
        await page.$eval('input[id="form.name"]', (el) => el.value),
        'Unsaved preview product',
    )
    const compactBeforeCancellation = (await selection()).compact
    page.once('dialog', (dialog) => dialog.dismiss())
    await page.click('[data-live-demo-compact]')
    assert.equal((await selection()).compact, compactBeforeCancellation)
    await page.evaluate(() => {
        window.liveDemoToolbarState.formIsDirty = false
    })

    await other.goto(new URL('/login', base).href, {
        waitUntil: 'networkidle0',
    })
    assert.equal(
        await other.$eval('[data-live-demo-theme]', (el) => el.value),
        'stock',
        'a second session is stock on the same worker',
    )
    assert.deepEqual(
        await identity(other),
        stockIdentity,
        'worker restores the stock palette and font for another session',
    )
    await other.goto(new URL('/login?theme=sharp', base).href, {
        waitUntil: 'networkidle0',
    })
    assert.match((await identity(other)).font, /Inter Variable/)
    await goto('/shop/products?theme=soft&compact=1')
    assert.notEqual((await identity(page)).primary, stockIdentity.primary)
    assert.match((await identity(page)).font, /Albert Sans/)
    await other.goto(new URL('/app/login', base).href, {
        waitUntil: 'networkidle0',
    })
    assert.equal(await other.$('[data-live-demo-toolbar]'), null)
    await isolated.close()

    await goto('/shop/products?theme=soft&compact=1')
    await page.click('[data-live-demo-scheme]')
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight))
    assert.equal(
        await page.evaluate(
            () =>
                document.querySelector('.fi-pagination').getBoundingClientRect()
                    .bottom <
                document
                    .querySelector('[data-live-demo-toolbar]')
                    .getBoundingClientRect().top,
        ),
        true,
        'pagination can scroll clear of the expanded toolbar',
    )
    await page.screenshot({ path: `${artifacts}/soft-compact-footer.png` })

    await page.setViewport({ width: 390, height: 844 })
    await goto('/shop/products?theme=noir&compact=1')
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight))
    assert.equal(
        await page.evaluate(
            () =>
                document.querySelector('.fi-pagination').getBoundingClientRect()
                    .bottom <
                document
                    .querySelector('[data-live-demo-toolbar]')
                    .getBoundingClientRect().top,
        ),
        true,
        'mobile pagination remains reachable',
    )
    await page.screenshot({ path: `${artifacts}/mobile-noir-compact.png` })
    assert.equal(
        await page.$eval(
            '[data-live-demo-toolbar]',
            (el) =>
                el.getBoundingClientRect().right <= innerWidth &&
                el.getBoundingClientRect().left >= 0,
        ),
        true,
    )
    assert.deepEqual(errors, [])
    console.log(
        'PASS: 16 appearance states, full reload/query/hash preservation, Livewire and SPA persistence, dirty cancellation, isolated session/worker state, stock app panel, mobile bounds; no JS errors.',
    )
} finally {
    await browser.close()
}
