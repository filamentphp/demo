import { createApp, h, shallowRef } from 'vue'
import LoadingSectionVue from './LoadingSectionVue.vue'

export default function mountLoadingSectionVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(LoadingSectionVue, props.value),
    })
    application.mount(host)
    return {
        update: (next) => (props.value = next),
        destroy: () => application.unmount(),
    }
}
