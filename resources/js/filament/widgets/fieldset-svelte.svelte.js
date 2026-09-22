import { mount, unmount } from 'svelte'
import FieldsetSvelte from './FieldsetSvelte.svelte'

export default function mountFieldsetSvelte({ host, props: initialProps }) {
    const props = $state({ ...initialProps })
    const component = mount(FieldsetSvelte, { target: host, props })

    return {
        update: (nextProps) => {
            Object.assign(props, nextProps)
        },
        destroy: () => unmount(component),
    }
}
