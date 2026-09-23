import { mount, unmount } from 'svelte'
import InputWrapperSvelte from './InputWrapperSvelte.svelte'

export default function mountInputWrapperSvelte({ host, props: initialProps }) {
    const props = $state({ ...initialProps })
    const component = mount(InputWrapperSvelte, { target: host, props })
    return {
        update: (nextProps) => {
            Object.assign(props, nextProps)
        },
        destroy: () => unmount(component),
    }
}
