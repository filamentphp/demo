import assert from 'node:assert/strict'
import { mkdirSync, writeFileSync } from 'node:fs'
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
    headless: process.env.THEME_PREVIEW_HEADFUL !== '1',
    args: ['--no-sandbox'],
})
const page = await browser.newPage()
await page.evaluateOnNewDocument(() => {
    window.liveDemoPopupTransitions = []
    document.addEventListener('transitionrun', (event) => {
        if (event.target.matches('.live-demo-studio'))
            window.liveDemoPopupTransitions.push(event.propertyName)
    })
})
await page.setViewport({ width: 1440, height: 1000 })
const errors = []
page.on('pageerror', (error) => errors.push(error.message))
const goto = (path) =>
    page.goto(new URL(path, base).href, { waitUntil: 'networkidle0' })
const selection = () =>
    page.evaluate(() => ({
        theme: document.querySelector('[data-live-demo-theme]:checked').value,
        compact: document.querySelector('[data-live-demo-compact]').checked,
        hosts: [...document.querySelectorAll('link[rel=stylesheet]')]
            .map((el) => el.href)
            .filter((url) => /\/(stock|sharp|soft|noir)(-|\.)/.test(url)),
    }))
const panelIsOpen = () =>
    page.$eval('[data-live-demo-toolbar-controls]', (el) =>
        el.matches(':popover-open'),
    )
const openPanel = async () => {
    if (!(await panelIsOpen()))
        await page.click('[data-live-demo-toolbar-toggle]')
    await page.waitForFunction(() =>
        document
            .querySelector('[data-live-demo-toolbar-controls]')
            .matches(':popover-open'),
    )
    await new Promise((resolve) => setTimeout(resolve, 300))
}
const identity = (target) =>
    target.evaluate(() => ({
        primary: getComputedStyle(document.documentElement)
            .getPropertyValue('--primary-500')
            .trim(),
        font: getComputedStyle(document.body).fontFamily,
    }))

try {
    assert.ok(
        await page.evaluate(
            () => matchMedia('(hover: hover) and (pointer: fine)').matches,
        ),
        'Desktop hover checks need a mouse-capable browser; use THEME_PREVIEW_HEADFUL=1 with xvfb-run on Linux.',
    )
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
    await page.setRequestInterception(true)
    const withoutPreviewScript = (request) =>
        /\/live-demo-[\w-]+\.js/.test(request.url())
            ? request.abort()
            : request.continue()
    page.on('request', withoutPreviewScript)
    await goto('/login')
    assert.equal(
        await page.evaluate(() => window.liveDemoToolbarState),
        undefined,
        'preview script is genuinely blocked',
    )
    assert.equal(await panelIsOpen(), false)
    await page.click('[data-live-demo-toolbar-toggle]')
    assert.equal(
        await panelIsOpen(),
        true,
        'first click opens even before preview JavaScript loads',
    )
    await page.click('[data-live-demo-toolbar-toggle]')
    assert.equal(
        await panelIsOpen(),
        false,
        'second trigger click closes without preview JS',
    )
    await page.click('[data-live-demo-toolbar-toggle]')
    await page.click('[data-live-demo-close]')
    assert.equal(await panelIsOpen(), false)
    await page.setRequestInterception(false)
    page.off('request', withoutPreviewScript)
    await goto('/login')
    const stockIdentity = await identity(page)
    assert.equal(await panelIsOpen(), false)
    const launcher = await page.$('[data-live-demo-toolbar-toggle]')
    const circle = await launcher.boundingBox()
    assert.equal(
        Math.round(circle.width),
        Math.round(circle.height),
        'launcher starts circular',
    )
    await page.screenshot({ path: `${artifacts}/studio-launcher.png` })
    await page.hover('[data-live-demo-toolbar-toggle]')
    await new Promise((resolve) => setTimeout(resolve, 350))
    assert.ok(
        (await launcher.boundingBox()).width > circle.width + 80,
        'hover smoothly reveals the label',
    )
    assert.equal(
        await page.$eval('[data-live-demo-toolbar-toggle]', (el) =>
            el.getAttribute('aria-label'),
        ),
        'Switch theme',
    )
    assert.equal(
        await page.evaluate(
            () =>
                document
                    .querySelector('.live-demo-launcher__label')
                    .getBoundingClientRect().right <
                document
                    .querySelector('.live-demo-launcher__icon')
                    .getBoundingClientRect().left,
        ),
        true,
        'trigger text sits left of its icon',
    )
    await page.screenshot({ path: `${artifacts}/studio-hover.png` })
    await openPanel()
    await page.screenshot({ path: `${artifacts}/studio-light.png` })
    assert.ok(
        (await page.evaluate(() => window.liveDemoPopupTransitions)).includes(
            'opacity',
        ),
        'manual opening still animates',
    )
    await page.evaluate(() => {
        window.liveDemoPopupTransitions = []
    })
    await page.click('[data-live-demo-toolbar-toggle]')
    assert.equal(
        await panelIsOpen(),
        false,
        'trigger also closes with preview JS',
    )
    await new Promise((resolve) => setTimeout(resolve, 300))
    assert.ok(
        (await page.evaluate(() => window.liveDemoPopupTransitions)).includes(
            'opacity',
        ),
        'manual closing still animates',
    )
    await openPanel()
    await page.click('[data-live-demo-close]')
    await page.focus('[data-live-demo-toolbar-toggle]')
    await page.keyboard.press('Enter')
    assert.equal(await panelIsOpen(), true, 'keyboard opens the panel')
    await page.keyboard.press('Escape')
    assert.equal(await panelIsOpen(), false, 'Escape closes the panel')
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
            await openPanel()
            const result = await selection()
            assert.equal(result.theme, theme)
            assert.equal(result.compact, compact)
            for (const selector of [
                '.fi-sidebar a[href$="/shop/products"] svg',
                '.live-demo-launcher svg',
            ]) {
                assert.equal(
                    await page.$eval(selector, (el) => el.getAttribute('viewBox')),
                    theme === 'sharp' ? '0 -960 960 960' : '0 0 24 24',
                    'demo aliases use Material Sharp only in Sharp',
                )
            }
            const showroom = await page.evaluate(() => {
                const style = (selector) =>
                    getComputedStyle(document.querySelector(selector))
                const link = document.querySelector('.live-demo-shop__link')
                return {
                    closeTop: document
                        .querySelector('[data-live-demo-close]')
                        .getBoundingClientRect().top,
                    headerContentTop: document
                        .querySelector('.live-demo-studio__header > div')
                        .getBoundingClientRect().top,
                    launcherBorder: style('.live-demo-launcher').borderTopWidth,
                    launcherShadow: style('.live-demo-launcher').boxShadow,
                    launcherWeight: style('.live-demo-launcher__label')
                        .fontWeight,
                    radius: style('.live-demo-studio').borderTopLeftRadius,
                    spacing: style('.live-demo-studio__body').paddingTop,
                    softFont: style(
                        '.live-demo-theme--soft .live-demo-theme__name',
                    ).fontFamily,
                    sharpFont: style('.live-demo-theme--sharp').fontFamily,
                    href: link.href,
                    target: link.target,
                    first: document.querySelector('.live-demo-studio__body')
                        .firstElementChild.className,
                }
            })
            assert.ok(
                Math.abs(showroom.closeTop - showroom.headerContentTop) < 1,
                'close is top-aligned',
            )
            assert.equal(showroom.launcherBorder, '0px')
            assert.doesNotMatch(showroom.launcherShadow, /inset/)
            assert.equal(showroom.launcherWeight, '500')
            assert.equal(showroom.spacing, compact ? '12px' : '20px')
            assert.equal(showroom.radius === '0px', theme === 'sharp')
            assert.match(showroom.softFont, /Lora/)
            assert.match(showroom.sharpFont, /Inter Variable/)
            assert.equal(showroom.href, 'https://filamentphp.com/themes')
            assert.equal(showroom.target, '_blank')
            assert.equal(showroom.first, 'live-demo-appearance')
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
                    await page.click(`[data-live-demo-scheme="${scheme}"]`)
                await page.waitForFunction(
                    (dark) =>
                        document.documentElement.classList.contains('dark') ===
                        dark,
                    {},
                    scheme === 'dark',
                )
                await page.evaluate(() => document.fonts.ready)
                await new Promise((resolve) => setTimeout(resolve, 300))
                await page.screenshot({
                    path: `${artifacts}/${theme}${compact ? '-compact' : ''}-${scheme}.png`,
                })
            }
        }
    }

    for (const change of ['theme', 'compact']) {
        await goto(
            `/shop/products?theme=${change === 'theme' ? 'stock' : 'noir'}&compact=0`,
        )
        await openPanel()
        await page.click(
            `[data-live-demo-scheme="${change === 'theme' ? 'light' : 'dark'}"]`,
        )
        if (change === 'compact')
            await page.setViewport({ width: 390, height: 844 })
        const cdp = await page.createCDPSession()
        let heldRequest
        const holdNavigation = (request) => {
            if (
                request.isNavigationRequest() &&
                request.frame() === page.mainFrame()
            ) {
                heldRequest = request
            } else {
                request.continue()
            }
        }
        await page.setRequestInterception(true)
        page.on('request', holdNavigation)
        const navigation = page.waitForNavigation({ waitUntil: 'networkidle0' })
        const loading = await page.evaluate(
            (selector) => {
                document.querySelector(selector).click()
                const selected = () =>
                    [
                        ...document.querySelectorAll(
                            '[data-live-demo-toolbar] input',
                        ),
                    ].map((input) => input.checked)
                const before = selected()
                document
                    .querySelector('[data-live-demo-theme][value="sharp"]')
                    .click()
                document.querySelector('[data-live-demo-compact]').click()
                const overlay = document.querySelector(
                    '[data-live-demo-loading]',
                )
                const popup = document.querySelector(
                    '[data-live-demo-toolbar-controls]',
                )
                const overlayBounds = overlay.getBoundingClientRect()
                const popupBounds = popup.getBoundingClientRect()
                return {
                    visible: !overlay.hidden && popup.matches(':popover-open'),
                    contained:
                        overlayBounds.top >= popupBounds.top &&
                        overlayBounds.bottom <= popupBounds.bottom &&
                        overlayBounds.left >= popupBounds.left &&
                        overlayBounds.right <= popupBounds.right,
                    pageBlur: getComputedStyle(document.body).filter,
                    disabled: [
                        ...document.querySelectorAll(
                            '[data-live-demo-toolbar] button, [data-live-demo-toolbar] input',
                        ),
                    ].every((control) => control.disabled),
                    cancelPrevented: !document.dispatchEvent(
                        new KeyboardEvent('keydown', {
                            key: 'Escape',
                            cancelable: true,
                        }),
                    ),
                    unchanged:
                        JSON.stringify(before) === JSON.stringify(selected()),
                }
            },
            change === 'theme'
                ? '[data-live-demo-theme][value="soft"]'
                : '[data-live-demo-compact]',
        )
        assert.deepEqual(loading, {
            visible: true,
            contained: true,
            pageBlur: 'none',
            disabled: true,
            cancelPrevented: true,
            unchanged: true,
        })
        await new Promise((resolve) => setTimeout(resolve, 300))
        const capture = await cdp.send('Page.captureScreenshot', {
            format: 'png',
        })
        writeFileSync(
            `${artifacts}/loading-${change}.png`,
            Buffer.from(capture.data, 'base64'),
        )
        while (!heldRequest)
            await new Promise((resolve) => setTimeout(resolve, 10))
        page.off('request', holdNavigation)
        await heldRequest.continue()
        await page.setRequestInterception(false)
        await navigation
        await cdp.detach()
        await page.setViewport({ width: 1440, height: 1000 })
        assert.equal((await selection()).compact, true)
        assert.equal(
            (await selection()).theme,
            change === 'theme' ? 'soft' : 'noir',
        )
        assert.equal(
            await page.$eval('[data-live-demo-loading]', (el) => el.hidden),
            true,
        )
        assert.deepEqual(
            await page.evaluate(() => window.liveDemoPopupTransitions),
            [],
            'restoring the popup after a switch does not animate',
        )
        await page.goBack({ waitUntil: 'networkidle0' })
        await page.waitForNetworkIdle()
        assert.equal(
            await page.$eval('[data-live-demo-loading]', (el) => el.hidden),
            true,
            'Back restores usable controls',
        )
        assert.equal(
            await page.$eval('[data-live-demo-compact]', (el) => el.disabled),
            false,
        )
        console.log(
            `PASS: ${change} refresh blocks repeated selections and restores controls after Back`,
        )
    }

    for (const theme of ['sharp', 'soft', 'noir']) {
        const scheme = theme === 'noir' ? 'dark' : 'light'
        const opposite = scheme === 'dark' ? 'light' : 'dark'
        await goto('/shop/products?theme=stock&compact=0')
        await openPanel()
        await page.click(`[data-live-demo-scheme="${opposite}"]`)
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle0' }),
            page.click(`[data-live-demo-theme][value="${theme}"]`),
        ])
        assert.equal((await selection()).compact, true)
        assert.equal(
            await page.evaluate(() => localStorage.getItem('theme')),
            scheme,
        )
        assert.equal(
            await page.evaluate(() =>
                document.documentElement.classList.contains('dark'),
            ),
            scheme === 'dark',
        )
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle0' }),
            page.click('[data-live-demo-compact]'),
        ])
        await page.click(`[data-live-demo-scheme="${opposite}"]`)
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle0' }),
            page.click('[data-live-demo-theme][value="stock"]'),
        ])
        assert.equal(
            (await selection()).compact,
            false,
            'Default preserves manual density',
        )
        assert.equal(
            await page.evaluate(() => localStorage.getItem('theme')),
            opposite,
            'Default preserves manual color scheme',
        )
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
    await openPanel()
    await page.evaluate(() => {
        window.liveDemoDocumentMarker = 'old'
    })
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle0' }),
        page.click('[data-live-demo-theme][value="soft"]'),
    ])
    assert.equal(new URL(page.url()).searchParams.get('probe'), 'keep')
    assert.equal(new URL(page.url()).hash, '#retained')
    assert.equal((await selection()).compact, true, 'Soft enables Compact')
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle0' }),
        page.click('[data-live-demo-compact]'),
    ])
    assert.equal((await selection()).compact, false)
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
    await openPanel()
    await page.click('[data-live-demo-close]')
    await page.click('[data-live-demo-toolbar-toggle]')
    assert.equal(
        await panelIsOpen(),
        true,
        'one click opens after SPA navigation',
    )

    await goto('/shop/products/create')
    await page.type('input[id="form.name"]', 'Unsaved preview product')
    await openPanel()
    await page.waitForNetworkIdle()
    const beforeCancelledTheme = await selection()
    const schemeBeforeCancelledTheme = await page.evaluate(() =>
        localStorage.getItem('theme'),
    )
    let prompted = false
    page.once('dialog', async (dialog) => {
        prompted = true
        await dialog.dismiss()
    })
    await page.click('[data-live-demo-theme][value="noir"]')
    assert.equal(prompted, true)
    assert.deepEqual(await selection(), beforeCancelledTheme)
    assert.equal(
        await page.$eval('[data-live-demo-loading]', (el) => el.hidden),
        true,
    )
    assert.equal(
        await page.$eval('[data-live-demo-compact]', (el) => el.disabled),
        false,
    )
    assert.equal(
        await page.evaluate(() => localStorage.getItem('theme')),
        schemeBeforeCancelledTheme,
    )
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
        await other.$eval('[data-live-demo-theme]:checked', (el) => el.value),
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
    assert.equal(
        await other.$eval('.live-demo-launcher svg', (el) => el.getAttribute('viewBox')),
        '0 -960 960 960',
    )
    await goto('/shop/products?theme=soft&compact=1')
    assert.notEqual((await identity(page)).primary, stockIdentity.primary)
    assert.match((await identity(page)).font, /Albert Sans/)
    assert.equal(
        await page.$eval('.live-demo-launcher svg', (el) => el.getAttribute('viewBox')),
        '0 0 24 24',
        'Sharp demo icons do not leak between sessions on the same worker',
    )
    await other.goto(new URL('/app/login', base).href, {
        waitUntil: 'networkidle0',
    })
    assert.equal(await other.$('[data-live-demo-toolbar]'), null)
    await isolated.close()

    await goto('/shop/products?theme=soft&compact=1')
    await openPanel()
    await page.click('[data-live-demo-scheme="dark"]')
    await page.screenshot({ path: `${artifacts}/studio-dark.png` })
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight))
    assert.equal(
        await page.evaluate(
            () =>
                document.querySelector('.fi-pagination').getBoundingClientRect()
                    .bottom <
                document
                    .querySelector('[data-live-demo-toolbar-controls]')
                    .getBoundingClientRect().top,
        ),
        true,
        'pagination can scroll clear of the expanded toolbar',
    )
    await page.screenshot({ path: `${artifacts}/soft-compact-footer.png` })

    await page.setViewport({
        width: 390,
        height: 844,
        isMobile: true,
        hasTouch: true,
    })
    await goto('/shop/products?theme=noir&compact=1')
    await openPanel()
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight))
    assert.equal(
        await page.evaluate(
            () =>
                document.querySelector('.fi-pagination').getBoundingClientRect()
                    .bottom <
                document
                    .querySelector('[data-live-demo-toolbar-controls]')
                    .getBoundingClientRect().top,
        ),
        true,
        'mobile pagination remains reachable',
    )
    await page.screenshot({ path: `${artifacts}/mobile-noir-compact.png` })
    await page.click('[data-live-demo-scheme="light"]')
    await new Promise((resolve) => setTimeout(resolve, 300))
    assert.equal(
        await page.$eval(
            '.live-demo-studio__body',
            (el) => getComputedStyle(el).paddingTop,
        ),
        '20px',
        'Compact keeps default mobile spacing',
    )
    await page.screenshot({ path: `${artifacts}/studio-mobile-light.png` })
    assert.equal(
        await page.$eval(
            '[data-live-demo-toolbar-controls]',
            (el) =>
                el.getBoundingClientRect().right <= innerWidth &&
                el.getBoundingClientRect().left >= 0,
        ),
        true,
    )
    await page.click('[data-live-demo-close]')
    await page.touchscreen.tap(
        ...Object.values(
            await page.$eval('[data-live-demo-toolbar-toggle]', (el) => {
                const rect = el.getBoundingClientRect()
                return {
                    x: rect.x + rect.width / 2,
                    y: rect.y + rect.height / 2,
                }
            }),
        ),
    )
    assert.equal(await panelIsOpen(), true, 'first touch opens the panel')
    await page.emulateMediaFeatures([
        { name: 'prefers-reduced-motion', value: 'reduce' },
    ])
    assert.ok(
        await page.$eval('[data-live-demo-toolbar-controls]', (el) =>
            getComputedStyle(el)
                .transitionDuration.split(',')
                .every((duration) => parseFloat(duration) <= 0.001),
        ),
        'reduced motion removes perceptible transitions',
    )
    assert.deepEqual(errors, [])
    console.log(
        'PASS: first click without preview JS, hover expansion, keyboard/Escape, separate close, first touch, reduced motion, 16 appearance states, full reload/query/hash preservation, Livewire and SPA persistence, dirty cancellation, isolated session/worker state, stock app panel, mobile bounds; no JS errors.',
    )
} finally {
    await browser.close()
}
