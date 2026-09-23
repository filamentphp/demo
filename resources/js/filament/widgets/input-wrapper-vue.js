import { createApp, h, shallowRef } from 'vue'
import InputWrapperVue from './InputWrapperVue.vue'

export default function mountInputWrapperVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(InputWrapperVue, props.value),
    })
    application.mount(host)
    return {
        update: (nextProps) => {
            props.value = nextProps
        },
        destroy: () => application.unmount(),
    }
}
