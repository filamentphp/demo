import { createInertiaApp } from '@inertiajs/react'
import { createRoot, hydrateRoot } from 'react-dom/client'
import ReactWorkbench from './ReactWorkbench'

export default async function mount(element, externalNavigation) {
    let root

    await createInertiaApp({
        id: element.id,
        externalNavigation,
        resolve: () => ReactWorkbench,
        setup({ el, App, props }) {
            if (!element.isConnected) return

            if (el.dataset.serverRendered === 'true') {
                root = hydrateRoot(el, <App {...props} />)
            } else {
                root = createRoot(el)
                root.render(<App {...props} />)
            }
        },
    })

    return () => root?.unmount()
}
