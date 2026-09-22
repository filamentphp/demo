import { createApp, h, shallowRef } from 'vue'
import LoadingIndicatorVue from './LoadingIndicatorVue.vue'

export default function mountLoadingIndicatorVue({
    host,
    props: initialProps,
}) {
    const props = shallowRef(initialProps)
    const application = createApp({
        setup: () => () => h(LoadingIndicatorVue, props.value),
    })
    application.mount(host)

    return {
        update: (nextProps) => {
            props.value = nextProps
        },
        destroy: () => application.unmount(),
    }
}
