import { mount, unmount } from 'svelte'
import CalloutSvelte from './CalloutSvelte.svelte'

export default function mountCalloutSvelte({ host, props: initialProps }) {
    const props = $state({ ...initialProps })
    const component = mount(CalloutSvelte, { target: host, props })
    return {
        update: (next) => Object.assign(props, next),
        destroy: () => unmount(component),
    }
}
