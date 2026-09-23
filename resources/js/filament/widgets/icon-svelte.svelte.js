import { mount, unmount } from 'svelte'
import IconSvelte from './IconSvelte.svelte'

export default function mountIconSvelte({ host, props: initialProps }) {
    const props = $state({ ...initialProps })
    const component = mount(IconSvelte, { target: host, props })
    return {
        update: (nextProps) => {
            Object.assign(props, nextProps)
        },
        destroy: () => unmount(component),
    }
}
