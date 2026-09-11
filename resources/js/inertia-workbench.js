import { createInertiaApp } from '@inertiajs/vue3'
import { createApp, createSSRApp, h } from 'vue'
import Workbench from './Workbench'

export default async function mount(root, externalNavigation) {
    let app

    await createInertiaApp({
        id: root.id,
        externalNavigation,
        resolve: () => Workbench,
        setup({ el, App, props, plugin }) {
            if (!root.isConnected) return

            const create =
                el.dataset.serverRendered === 'true' ? createSSRApp : createApp

            app = create({ render: () => h(App, props) }).use(plugin)
            app.mount(el)
        },
    })

    return () => app?.unmount()
}
