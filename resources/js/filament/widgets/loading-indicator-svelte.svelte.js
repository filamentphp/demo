import { mount, unmount } from 'svelte'
import LoadingIndicatorSvelte from './LoadingIndicatorSvelte.svelte'

export default function mountLoadingIndicatorSvelte({
    host,
    props: initialProps,
}) {
    const props = $state({ ...initialProps })
    const component = mount(LoadingIndicatorSvelte, { target: host, props })

    return {
        update: (nextProps) => {
            Object.assign(props, nextProps)
        },
        destroy: () => unmount(component),
    }
}
