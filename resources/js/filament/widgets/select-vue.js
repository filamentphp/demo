import { createApp, h, shallowRef } from 'vue'
import SelectVue from './SelectVue.vue'

export default function mountSelectVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(SelectVue, props.value),
    })
    application.mount(host)
    return {
        update: (next) => (props.value = next),
        destroy: () => application.unmount(),
    }
}
