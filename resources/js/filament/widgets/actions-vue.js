import { createApp, h, shallowRef } from 'vue'
import ActionsVue from './ActionsVue.vue'

export default function mountActionsVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(ActionsVue, props.value),
    })
    application.mount(host)
    return {
        update: (next) => (props.value = next),
        destroy: () => application.unmount(),
    }
}
