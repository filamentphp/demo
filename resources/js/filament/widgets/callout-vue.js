import { createApp, h, shallowRef } from 'vue'
import CalloutVue from './CalloutVue.vue'

export default function mountCalloutVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(CalloutVue, props.value),
    })
    application.mount(host)
    return {
        update: (next) => (props.value = next),
        destroy: () => application.unmount(),
    }
}
