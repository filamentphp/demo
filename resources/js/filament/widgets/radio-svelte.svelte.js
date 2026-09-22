import { mount, unmount } from 'svelte'
import RadioSvelte from './RadioSvelte.svelte'

export default function mountRadioSvelte({ host, props: initialProps }) {
    const props = $state({ ...initialProps })
    const component = mount(RadioSvelte, { target: host, props })

    return {
        update: (nextProps) => {
            Object.assign(props, nextProps)
        },
        destroy: () => unmount(component),
    }
}
