import { mount, unmount } from 'svelte'
import CheckboxSvelte from './CheckboxSvelte.svelte'

export default function mountCheckboxSvelte({ host, props: initialProps }) {
    const props = $state({ ...initialProps })
    const component = mount(CheckboxSvelte, { target: host, props })

    return {
        update: (nextProps) => {
            Object.assign(props, nextProps)
        },
        destroy: () => unmount(component),
    }
}
