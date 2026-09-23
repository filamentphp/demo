import { mount, unmount } from 'svelte'
import LoadingSectionSvelte from './LoadingSectionSvelte.svelte'

export default function mountLoadingSectionSvelte({
    host,
    props: initialProps,
}) {
    const props = $state({ ...initialProps })
    const component = mount(LoadingSectionSvelte, { target: host, props })
    return {
        update: (next) => Object.assign(props, next),
        destroy: () => unmount(component),
    }
}
