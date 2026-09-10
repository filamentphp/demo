import { build } from 'esbuild'

await build({
    entryPoints: ['resources/js/project-collaboration.js'],
    outfile: 'public/build/project-collaboration.js',
    bundle: true,
    format: 'esm',
    minify: true,
    define: { 'process.env.NODE_ENV': '"production"' },
    plugins: [{
        name: 'filament-tiptap',
        setup(build) {
            const modules = {
                '@tiptap/core': 'core',
                '@tiptap/pm/state': 'pmState',
                '@tiptap/pm/view': 'pmView',
                '@tiptap/pm/model': 'pmModel',
                'prosemirror-state': 'pmState',
                'prosemirror-view': 'pmView',
                'prosemirror-model': 'pmModel',
            }

            build.onResolve({ filter: /^(@tiptap\/(core|pm\/(state|view|model))|prosemirror-(state|view|model))$/ }, ({ path }) => ({ path, namespace: 'filament' }))
            build.onLoad({ filter: /.*/, namespace: 'filament' }, async ({ path }) => ({
                contents: `export const { ${Object.keys(await import(path)).filter(key => key !== 'default').join(', ')} } = window.FilamentRichEditor.tiptap.${modules[path]}`,
                loader: 'js',
            }))
        },
    }],
})
