import { mount, unmount } from 'svelte'
import EmptyStateSvelte from './EmptyStateSvelte.svelte'

export default function mountEmptyStateSvelte({ host, props: initialProps }) {
    const props = $state({ ...initialProps })
    const component = mount(EmptyStateSvelte, { target: host, props })
    return {
        update: (next) => Object.assign(props, next),
        destroy: () => unmount(component),
    }
}
