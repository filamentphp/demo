import { createInertiaApp } from '@inertiajs/react'
import { useLayoutEffect } from 'react'
import { createRoot, hydrateRoot } from 'react-dom/client'
import ReactWorkbench from './ReactWorkbench'

function Mounted({ children, onMounted }) {
    useLayoutEffect(onMounted, [onMounted])

    return children
}

export default async function mount(element, externalNavigation, onMounted) {
    let root

    await createInertiaApp({
        id: element.id,
        externalNavigation,
        resolve: () => ReactWorkbench,
        setup({ el, App, props }) {
            if (!element.isConnected) return

            const app = (
                <Mounted onMounted={onMounted}>
                    <App {...props} />
                </Mounted>
            )

            if (el.dataset.serverRendered === 'true') {
                root = hydrateRoot(el, app)
            } else {
                root = createRoot(el)
                root.render(app)
            }
        },
    })

    return () => root?.unmount()
}
