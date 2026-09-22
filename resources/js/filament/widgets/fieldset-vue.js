import { createApp, h, shallowRef } from 'vue'
import FieldsetVue from './FieldsetVue.vue'

export default function mountFieldsetVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(FieldsetVue, props.value),
    })
    application.mount(host)

    return {
        update: (nextProps) => {
            props.value = nextProps
        },
        destroy: () => application.unmount(),
    }
}
