import { mount, unmount } from 'svelte'
import BreadcrumbsSvelte from './BreadcrumbsSvelte.svelte'

export default function mountBreadcrumbsSvelte({ host, props: initialProps }) {
    const props = $state({ ...initialProps })
    const component = mount(BreadcrumbsSvelte, { target: host, props })

    return {
        update: (nextProps) => {
            Object.assign(props, nextProps)
        },
        destroy: () => unmount(component),
    }
}
