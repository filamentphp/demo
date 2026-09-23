import { createApp, h, shallowRef } from 'vue'
import InputVue from './InputVue.vue'

export default function mountInputVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(InputVue, props.value),
    })
    application.mount(host)
    return {
        update: (nextProps) => {
            props.value = nextProps
        },
        destroy: () => application.unmount(),
    }
}
