import { createApp, h, shallowRef } from 'vue'
import AvatarVue from './AvatarVue.vue'

export default function mountAvatarVue({ host, props: initialProps }) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(AvatarVue, props.value),
    })
    application.mount(host)

    return {
        update: (nextProps) => {
            props.value = nextProps
        },
        destroy: () => application.unmount(),
    }
}
