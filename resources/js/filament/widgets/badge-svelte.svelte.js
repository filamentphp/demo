import { mount, unmount } from 'svelte'
import BadgeSvelte from './BadgeSvelte.svelte'
export default function mountBadgeSvelte({ host, props: initialProps }) {
    const props = $state({ ...initialProps })
    const component = mount(BadgeSvelte, { target: host, props })
    return {
        update: (next) => Object.assign(props, next),
        destroy: () => unmount(component),
    }
}
