import { createApp, h, shallowRef } from 'vue'
import BadgeVue from './BadgeVue.vue'
export default function mountBadgeVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(BadgeVue, props.value),
    })
    application.mount(host)
    return {
        update: (next) => (props.value = next),
        destroy: () => application.unmount(),
    }
}
