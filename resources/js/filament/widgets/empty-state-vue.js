import { createApp, h, shallowRef } from 'vue'
import EmptyStateVue from './EmptyStateVue.vue'

export default function mountEmptyStateVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(EmptyStateVue, props.value),
    })
    application.mount(host)
    return {
        update: (next) => (props.value = next),
        destroy: () => application.unmount(),
    }
}
