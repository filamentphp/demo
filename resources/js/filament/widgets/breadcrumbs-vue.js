import { createApp, h, shallowRef } from 'vue'
import BreadcrumbsVue from './BreadcrumbsVue.vue'

export default function mountBreadcrumbsVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(BreadcrumbsVue, props.value),
    })
    application.mount(host)

    return {
        update: (nextProps) => {
            props.value = nextProps
        },
        destroy: () => application.unmount(),
    }
}
