import { svelte as filamentSvelte } from '@sveltejs/vite-plugin-svelte'
import filamentVue from '@vitejs/plugin-vue'
import { defineConfig } from 'vite'
import laravel, { refreshPaths } from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    build: {
        rolldownOptions: { preserveEntrySignatures: 'exports-only' },
    },
    plugins: [
        filamentSvelte(),
        filamentVue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/filament/admin/theme.css', 'resources/js/filament/widgets/avatar-react.jsx', 'resources/js/filament/widgets/avatar-vue.js', 'resources/js/filament/widgets/avatar-svelte.svelte.js'],
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
    ],
})
