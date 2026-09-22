import { createApp, h, shallowRef } from 'vue'
import CheckboxVue from './CheckboxVue.vue'

export default function mountCheckboxVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(CheckboxVue, props.value),
    })
    application.mount(host)

    return {
        update: (nextProps) => {
            props.value = nextProps
        },
        destroy: () => application.unmount(),
    }
}
