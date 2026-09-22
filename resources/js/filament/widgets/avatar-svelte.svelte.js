import { mount, unmount } from 'svelte'
import AvatarSvelte from './AvatarSvelte.svelte'

export default function mountAvatarSvelte({ host, props: initialProps }) {
    const props = $state({ ...initialProps })
    const component = mount(AvatarSvelte, { target: host, props })

    return {
        update: (nextProps) => {
            Object.assign(props, nextProps)
        },
        destroy: () => unmount(component),
    }
}
