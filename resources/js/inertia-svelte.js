import { createInertiaApp } from '@inertiajs/svelte'
import { hydrate, mount, unmount } from 'svelte'
import SvelteWorkbench from './SvelteWorkbench.svelte'

export default async function mountPage(element, externalNavigation) {
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
        },
    })

    return () => app && unmount(app)
}
