import assert from 'node:assert/strict'
import test from 'node:test'

test('applies a URL scheme after a toolbar reload and resolves system mode', async () => {
    const listeners = new Map()
    let prefersDark = false

    const addEventListener = (name, listener) => {
        listeners.set(name, [...(listeners.get(name) ?? []), listener])
    }
    const dispatchEvent = (event) => {
        for (const listener of listeners.get(event.type) ?? []) {
            listener(event)
        }
    }
    const makeClassList = () => {
        const classes = new Set()

        return {
            contains: (name) => classes.has(name),
            toggle: (name, force) => {
                if (force) {
                    classes.add(name)
                } else {
                    classes.delete(name)
                }
            },
        }
    }
    const makeSchemeButton = (scheme) => ({
        dataset: { liveDemoScheme: scheme },
        classList: makeClassList(),
        setAttribute(name, value) {
            this[name] = value
        },
    })

    const lightButton = makeSchemeButton('light')
    const darkButton = makeSchemeButton('dark')
    const toolbar = { dataset: { liveDemoScheme: 'dark' } }
    const controls = {
        dataset: { liveDemoOpen: 'false' },
        closest: () => toolbar,
        getBoundingClientRect: () => {},
        matches: () => false,
        showPopover: () => {},
    }
    const storage = new Map([
        ['theme', 'system'],
        ['live-demo.reopen', 'https://example.test/?theme=noir'],
    ])

    globalThis.CustomEvent = class {
        constructor(type, options = {}) {
            this.type = type
            this.detail = options.detail
        }
    }
    globalThis.location = { href: 'https://example.test/?theme=noir' }
    globalThis.localStorage = {
        getItem: (name) => storage.get(name) ?? null,
        setItem: (name, value) => storage.set(name, value),
    }
    globalThis.sessionStorage = {
        getItem: (name) => storage.get(name) ?? null,
        removeItem: (name) => storage.delete(name),
        setItem: (name, value) => storage.set(name, value),
    }
    globalThis.document = {
        addEventListener,
        documentElement: { classList: makeClassList() },
        querySelector: (selector) =>
            selector === '[data-live-demo-toolbar-controls]' ? controls : null,
        querySelectorAll: (selector) =>
            selector === 'button[data-live-demo-scheme]'
                ? [lightButton, darkButton]
                : [],
    }
    globalThis.window = {
        addEventListener,
        dispatchEvent,
        location: { assign: () => {} },
        matchMedia: () => ({ matches: prefersDark }),
    }
    addEventListener('theme-changed', (event) => {
        storage.set('theme', event.detail)
        document.documentElement.classList.toggle(
            'dark',
            event.detail === 'dark',
        )
    })

    await import('../templates/resources/js/live-demo.js')

    assert.equal(storage.get('theme'), 'dark')
    assert.equal(document.documentElement.classList.contains('dark'), true)
    assert.equal(darkButton['aria-pressed'], 'true')

    toolbar.dataset.liveDemoScheme = ''
    dispatchEvent({
        type: 'click',
        target: {
            closest: (selector) =>
                selector === '[data-live-demo-scheme]' ? toolbar : null,
        },
    })
    assert.equal(storage.get('theme'), 'dark')
    assert.equal(darkButton['aria-pressed'], 'true')

    dispatchEvent(new CustomEvent('theme-changed', { detail: 'system' }))
    assert.equal(lightButton['aria-pressed'], 'true')
    assert.equal(darkButton['aria-pressed'], 'false')

    prefersDark = true
    dispatchEvent(new CustomEvent('theme-changed', { detail: 'system' }))
    assert.equal(lightButton['aria-pressed'], 'false')
    assert.equal(darkButton['aria-pressed'], 'true')
})
