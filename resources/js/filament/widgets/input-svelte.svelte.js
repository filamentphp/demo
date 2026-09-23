import { mount, unmount } from 'svelte'
import InputSvelte from './InputSvelte.svelte'

export default function mountInputSvelte({ host, props: initialProps }) {
    const props = $state({ ...initialProps })
    const component = mount(InputSvelte, { target: host, props })
    return {
        update: (nextProps) => Object.assign(props, nextProps),
        destroy: () => unmount(component),
    }
}
