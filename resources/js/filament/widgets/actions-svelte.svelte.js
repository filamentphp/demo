import { mount, unmount } from 'svelte'
import ActionsSvelte from './ActionsSvelte.svelte'

export default function mountActionsSvelte({ host, props: initialProps }) {
    const props = $state({ ...initialProps })
    const component = mount(ActionsSvelte, { target: host, props })
    return {
        update: (next) => Object.assign(props, next),
        destroy: () => unmount(component),
    }
}
