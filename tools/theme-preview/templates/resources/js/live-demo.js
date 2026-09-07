const state = (window.liveDemoToolbarState ??= {
    formIsDirty: false,
    listenersInstalled: false,
})

const toolbarSelector = '[data-live-demo-toolbar]'

const isToolbarElement = (element) => element.closest?.(toolbarSelector)

const confirmNavigation = () =>
    !state.formIsDirty || window.confirm('Discard your unsaved changes?')

const navigateWithSelection = (changes) => {
    if (state.isLoading || !confirmNavigation()) {
        return false
    }

    const url = new URL(window.location.href)
    url.searchParams.delete('scheme')
    const toolbar = document.querySelector(toolbarSelector)
    const theme = toolbar?.querySelector(
        '[data-live-demo-theme]:checked',
    )?.value
    const compact =
        toolbar
            ?.querySelector('[data-live-demo-compact]')
            ?.getAttribute('aria-checked') === 'true'

    if (theme) {
        url.searchParams.set('theme', theme)
    }

    if (typeof compact === 'boolean') {
        url.searchParams.set('compact', compact ? '1' : '0')
    }

    Object.entries(changes).forEach(([name, value]) => {
        url.searchParams.set(name, value)
    })

    if (['sharp', 'soft', 'noir'].includes(changes.theme)) {
        url.searchParams.set('compact', '1')
        window.dispatchEvent(
            new CustomEvent('theme-changed', {
                detail: changes.theme === 'noir' ? 'dark' : 'light',
            }),
        )
    }

    state.formIsDirty = false
    state.isLoading = true
    toolbar.querySelectorAll('button, input').forEach((control) => {
        control.disabled = true
    })
    toolbar
        .querySelector('[data-live-demo-toolbar-controls]')
        .setAttribute('aria-busy', 'true')
    toolbar.querySelector('[data-live-demo-loading]').hidden = false
    sessionStorage.setItem('live-demo.reopen', url.href)
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
        button.classList.toggle(
            'fi-active',
            button.dataset.liveDemoScheme === scheme,
        )
        button.setAttribute(
            'aria-pressed',
            String(button.dataset.liveDemoScheme === scheme),
        )
    })
}

const initializeToolbar = () => {
    const controls = document.querySelector('[data-live-demo-toolbar-controls]')
    if (!controls) return

    const toolbar = controls.closest(toolbarSelector)
    const scheme = toolbar.dataset.liveDemoScheme
    if (scheme === 'light' || scheme === 'dark') {
        delete toolbar.dataset.liveDemoScheme
        if (sessionStorage.getItem('live-demo.reopen') !== location.href) {
            localStorage.setItem('theme', scheme)
            document.documentElement.classList.toggle('dark', scheme === 'dark')
            window.dispatchEvent(
                new CustomEvent('theme-changed', { detail: scheme }),
            )
        }
    }

    if (controls.matches(':popover-open')) state.panelOpen = true
    state.panelOpen ??=
        controls.dataset.liveDemoOpen === 'true' ||
        sessionStorage.getItem('live-demo.reopen') === location.href
    sessionStorage.removeItem('live-demo.reopen')
    if (state.panelOpen && !controls.matches(':popover-open')) {
        controls.dataset.liveDemoInstant = 'true'
        controls.showPopover()
        controls.getBoundingClientRect()
        delete controls.dataset.liveDemoInstant
    }
    updateSchemeControls()
}

if (!state.listenersInstalled) {
    state.listenersInstalled = true

    document.addEventListener(
        'toggle',
        (event) => {
            if (
                event.target.matches?.('[data-live-demo-toolbar-controls]') &&
                event.target.isConnected
            ) {
                state.panelOpen = event.newState === 'open'
            }
        },
        true,
    )

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

    document.addEventListener(
        'click',
        (event) => {
            const toggle = event.target.closest?.('[data-live-demo-compact]')
            if (!toggle) return
            event.preventDefault()
            event.stopImmediatePropagation()
            navigateWithSelection({
                compact:
                    toggle.getAttribute('aria-checked') === 'true' ? '0' : '1',
            })
        },
        true,
    )

    document.addEventListener('click', (event) => {
        const schemeButton = event.target.closest?.('[data-live-demo-scheme]')

        if (schemeButton) {
            const scheme = schemeButton.dataset.liveDemoScheme
            window.dispatchEvent(
                new CustomEvent('theme-changed', { detail: scheme }),
            )
            updateSchemeControls(scheme)
        }
    })

    document.addEventListener('change', (event) => {
        const themeChoice = event.target.closest?.('[data-live-demo-theme]')

        if (themeChoice) {
            if (!navigateWithSelection({ theme: themeChoice.value })) {
                document
                    .querySelectorAll('[data-live-demo-theme]')
                    .forEach((input) => {
                        input.checked = input.defaultChecked
                    })
            }
        }
    })

    window.addEventListener('theme-changed', (event) => {
        updateSchemeControls(event.detail)
    })

    document.addEventListener('alpine:initialized', () =>
        updateSchemeControls(),
    )
    document.addEventListener('livewire:navigating', () => {
        state.panelOpen = false
    })
    document.addEventListener('livewire:navigated', initializeToolbar)
    document.addEventListener('DOMContentLoaded', initializeToolbar)

    for (const eventName of ['pointerdown', 'pointerup', 'click', 'keydown']) {
        document.addEventListener(
            eventName,
            (event) => {
                if (!state.isLoading) return
                event.preventDefault()
                event.stopImmediatePropagation()
            },
            true,
        )
    }

    window.addEventListener('pageshow', () => {
        state.isLoading = false
        const loading = document.querySelector('[data-live-demo-loading]')
        if (loading) loading.hidden = true
        document
            .querySelector('[data-live-demo-toolbar-controls]')
            ?.removeAttribute('aria-busy')
        document
            .querySelectorAll(
                `${toolbarSelector} button, ${toolbarSelector} input`,
            )
            .forEach((control) => {
                control.disabled = false
            })
    })

    window.addEventListener('beforeunload', (event) => {
        if (state.formIsDirty) {
            event.preventDefault()
            event.returnValue = ''
        }
    })
}

initializeToolbar()
