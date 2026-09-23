import { mount, unmount } from 'svelte'
import SelectSvelte from './SelectSvelte.svelte'

export default function mountSelectSvelte({ host, props: initialProps }) {
    const props = $state({ ...initialProps })
    const component = mount(SelectSvelte, { target: host, props })
    return {
        update: (next) => Object.assign(props, next),
        destroy: () => unmount(component),
    }
}
