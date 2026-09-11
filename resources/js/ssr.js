import { createInertiaApp as createVueApp } from '@inertiajs/vue3'
import { createInertiaApp as createReactApp } from '@inertiajs/react'
import { createInertiaApp as createSvelteApp } from '@inertiajs/svelte'
import createServer from '@inertiajs/vue3/server'
import { createSSRApp, h } from 'vue'
import { renderToString } from 'vue/server-renderer'
import { createElement } from 'react'
import { renderToString as renderReact } from 'react-dom/server'
import { render as renderSvelte } from 'svelte/server'
import Workbench from './Workbench'
import ReactWorkbench from './ReactWorkbench'
import SvelteWorkbench from './SvelteWorkbench.svelte'

createServer(
    (page) => {
        if (page.component === 'ReactWorkbench') {
            return createReactApp({
                id: 'filament-inertia',
                page,
                render: renderReact,
                resolve: () => ReactWorkbench,
                setup: ({ App, props }) => createElement(App, props),
            })
        }

        if (page.component === 'SvelteWorkbench') {
            return createSvelteApp({
                id: 'filament-inertia',
                page,
                resolve: () => ({ default: SvelteWorkbench }),
                setup: ({ App, props }) => renderSvelte(App, { props }),
            })
        }

        return createVueApp({
            id: 'filament-inertia',
            page,
            render: renderToString,
            resolve: () => Workbench,
            setup({ App, props, plugin }) {
                return createSSRApp({ render: () => h(App, props) }).use(plugin)
            },
        })
    },
    { host: '127.0.0.1' },
)
