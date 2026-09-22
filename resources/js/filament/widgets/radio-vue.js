import { createApp, h, shallowRef } from 'vue'
import RadioVue from './RadioVue.vue'

export default function mountRadioVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(RadioVue, props.value),
    })
    application.mount(host)

    return {
        update: (nextProps) => {
            props.value = nextProps
        },
        destroy: () => application.unmount(),
    }
}
