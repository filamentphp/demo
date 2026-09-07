const state = (window.liveDemoToolbarState ??= {
    formIsDirty: false,
    listenersInstalled: false,
})

const toolbarSelector = '[data-live-demo-toolbar]'

const isToolbarElement = (element) => element.closest?.(toolbarSelector)

const confirmNavigation = () =>
    !state.formIsDirty || window.confirm('Discard your unsaved changes?')

const navigateWithSelection = (changes) => {
    if (!confirmNavigation()) {
        return false
    }

    const url = new URL(window.location.href)
    const toolbar = document.querySelector(toolbarSelector)
    const theme = toolbar?.querySelector('[data-live-demo-theme]')?.value
    const compact = toolbar?.querySelector('[data-live-demo-compact]')?.checked

    if (theme) {
        url.searchParams.set('theme', theme)
    }

    if (typeof compact === 'boolean') {
        url.searchParams.set('compact', compact ? '1' : '0')
    }

    Object.entries(changes).forEach(([name, value]) => {
        url.searchParams.set(name, value)
    })

    state.formIsDirty = false
    window.location.assign(url.href)

    return true
}

const currentScheme = () => {
    if (window.Alpine) {
        const theme = window.Alpine.store('theme')

        if (theme === 'dark' || theme === 'light') {
            return theme
        }
    }

    return document.documentElement.classList.contains('dark')
        ? 'dark'
        : 'light'
}

const updateSchemeControls = (scheme = currentScheme()) => {
    document.querySelectorAll('[data-live-demo-scheme]').forEach((button) => {
        const isDark = scheme === 'dark'
        button.setAttribute('aria-pressed', String(isDark))
        button.setAttribute(
            'aria-label',
            isDark ? 'Use light color scheme' : 'Use dark color scheme',
        )

        const label = button.querySelector('[data-live-demo-scheme-label]')

        if (label) {
            label.textContent = isDark ? 'Dark' : 'Light'
        }
    })
}

if (!state.listenersInstalled) {
    state.listenersInstalled = true

    document.addEventListener('input', (event) => {
        if (!isToolbarElement(event.target)) {
            state.formIsDirty = true
        }
    })

    document.addEventListener('change', (event) => {
        if (isToolbarElement(event.target)) {
            return
        }

        state.formIsDirty = true
    })

    document.addEventListener('submit', () => {
        state.formIsDirty = false
    })

    document.addEventListener('click', (event) => {
        const toggle = event.target.closest?.('[data-live-demo-toolbar-toggle]')

        if (toggle) {
            const controls = document.getElementById(
                toggle.getAttribute('aria-controls'),
            )
            const expanded = toggle.getAttribute('aria-expanded') === 'true'

            toggle.setAttribute('aria-expanded', String(!expanded))

            if (controls) {
                controls.hidden = !expanded
            }

            return
        }

        const schemeButton = event.target.closest?.('[data-live-demo-scheme]')

        if (schemeButton) {
            const scheme = currentScheme() === 'dark' ? 'light' : 'dark'
            window.dispatchEvent(
                new CustomEvent('theme-changed', { detail: scheme }),
            )
            updateSchemeControls(scheme)
        }
    })

    document.addEventListener('change', (event) => {
        const themeSelect = event.target.closest?.('[data-live-demo-theme]')

        if (themeSelect) {
            const previousValue =
                themeSelect.querySelector('option[selected]')?.value

            if (!navigateWithSelection({ theme: themeSelect.value })) {
                themeSelect.value = previousValue ?? 'stock'
            }

            return
        }

        const compactCheckbox = event.target.closest?.(
            '[data-live-demo-compact]',
        )

        if (compactCheckbox) {
            if (
                !navigateWithSelection({
                    compact: compactCheckbox.checked ? '1' : '0',
                })
            ) {
                compactCheckbox.checked = !compactCheckbox.checked
            }
        }
    })

    window.addEventListener('theme-changed', (event) => {
        updateSchemeControls(event.detail)
    })

    document.addEventListener('alpine:initialized', () =>
        updateSchemeControls(),
    )
    document.addEventListener('livewire:navigated', () =>
        updateSchemeControls(),
    )
    document.addEventListener('DOMContentLoaded', () => updateSchemeControls())

    window.addEventListener('beforeunload', (event) => {
        if (state.formIsDirty) {
            event.preventDefault()
            event.returnValue = ''
        }
    })
}
