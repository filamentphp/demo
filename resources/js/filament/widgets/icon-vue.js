import { createApp, h, shallowRef } from 'vue'
import IconVue from './IconVue.vue'

export default function mountIconVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(IconVue, props.value),
    })
    application.mount(host)
    return {
        update: (nextProps) => {
            props.value = nextProps
        },
        destroy: () => application.unmount(),
    }
}
