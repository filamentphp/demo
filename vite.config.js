import { defineConfig } from 'vite'
import laravel, { refreshPaths } from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'
import vue from '@vitejs/plugin-vue'
import react from '@vitejs/plugin-react'
import { svelte } from '@sveltejs/vite-plugin-svelte'

export default defineConfig({
    resolve: {
        dedupe: ['@inertiajs/core', 'vue', 'react', 'react-dom', 'svelte'],
    },
    build: {
        rolldownOptions: { preserveEntrySignatures: 'exports-only' },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css',
                'resources/css/inertia-workbench.css',
                'resources/js/inertia-workbench.js',
                'resources/js/inertia-react.jsx',
                'resources/js/inertia-svelte.js',
            ],
            ssr: 'resources/js/ssr.js',
            refresh: [
                ...refreshPaths,
                'app/Filament/**',
                'app/Forms/Components/**',
                'app/Livewire/**',
                'app/Infolists/Components/**',
                'app/Providers/Filament/**',
                'app/Tables/Columns/**',
            ],
        }),
        tailwindcss(),
        vue(),
        react(),
        svelte(),
    ],
})
