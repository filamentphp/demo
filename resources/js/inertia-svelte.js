import { createInertiaApp } from '@inertiajs/svelte'
import { flushSync, hydrate, mount, unmount } from 'svelte'
import SvelteWorkbench from './SvelteWorkbench.svelte'

export default async function mountPage(element, externalNavigation, onMounted) {
    let app

    await createInertiaApp({
        id: element.id,
        externalNavigation,
        resolve: () => ({ default: SvelteWorkbench }),
        setup({ el, App, props }) {
            if (!element.isConnected) return

            app = (el.dataset.serverRendered === 'true' ? hydrate : mount)(App, {
                target: el,
                props,
            })
            flushSync()
            onMounted()
        },
    })

    return () => app && unmount(app)
}
