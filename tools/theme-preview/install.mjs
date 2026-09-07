#!/usr/bin/env node

import { spawnSync } from 'node:child_process'
import { createHash } from 'node:crypto'
import {
    existsSync,
    lstatSync,
    mkdirSync,
    readFileSync,
    readdirSync,
    rmSync,
    writeFileSync,
} from 'node:fs'
import { dirname, join, resolve } from 'node:path'
import { fileURLToPath } from 'node:url'

const directory = dirname(fileURLToPath(import.meta.url))
const themes = ['compact', 'sharp', 'soft', 'noir']
const variants = ['stock', 'sharp', 'soft', 'noir']
const registry = 'https://packages.filamentphp.com/composer'
const themeVersion = '^1.0'
const hash = (value) => createHash('sha256').update(value).digest('hex')
const json = (value) => `${JSON.stringify(value, null, 4)}\n`

function replaceOnce(value, old, replacement, label) {
    if (value.split(old).length !== 2)
        throw new Error(`Unsupported ${label} shape; no files changed`)
    return value.replace(old, replacement)
}

export function install(
    root,
    { vcs = false, production = false, run = execute } = {},
) {
    root = resolve(root)
    const statePath = join(root, '.theme-preview-install.json')
    const read = (path) => readFileSync(join(root, path), 'utf8')
    const options = { vcs, production }
    const saved = existsSync(statePath)
        ? JSON.parse(readFileSync(statePath, 'utf8'))
        : null
    if (saved) {
        if (JSON.stringify(saved.options) !== JSON.stringify(options))
            throw new Error(
                'Installer options changed; use a fresh build checkout',
            )
        for (const [path, digest] of Object.entries(saved.files)) {
            if (!existsSync(join(root, path)) || hash(read(path)) !== digest)
                throw new Error(
                    `Installed file changed: ${path}; use a fresh build checkout`,
                )
        }
        run(root, 'composer', [
            'install',
            '--no-interaction',
            '--prefer-dist',
            '--no-scripts',
            ...(production ? ['--no-dev'] : []),
        ])
        run(root, 'composer', [
            'dump-autoload',
            '--no-interaction',
            ...(production ? ['--no-dev'] : []),
        ])
        console.log(
            'Theme preview already installed; locked dependencies restored.',
        )
        return
    }

    const originals = new Map()
    const files = new Map()
    function assertNoSymlinks(path) {
        for (
            let parent = join(root, path);
            parent !== root;
            parent = dirname(parent)
        ) {
            if (lstatSync(parent, { throwIfNoEntry: false })?.isSymbolicLink())
                throw new Error(`Refusing symlink: ${path}`)
        }
    }
    function add(path, value, existing = false) {
        const absolute = join(root, path)
        assertNoSymlinks(path)
        if (!existing && existsSync(absolute))
            throw new Error(`Refusing to overwrite ${path}`)
        originals.set(path, existsSync(absolute) ? read(path) : null)
        files.set(path, value)
    }

    assertNoSymlinks('composer.lock')
    const composer = JSON.parse(read('composer.json'))
    const originalLock = read('composer.lock')
    for (const theme of themes) {
        if (composer.require[`filament/${theme}-theme`])
            throw new Error(
                `Theme already configured: ${theme}; use a fresh build checkout`,
            )
        composer.require[`filament/${theme}-theme`] = themeVersion
    }
    composer.require['kienso/blade-google-material-symbols'] = '^1.0'
    {
        const originalRepositories = composer.repositories ?? []
        const entries = Object.entries(originalRepositories).filter(
            ([, entry]) => !vcs || entry?.url?.replace(/\/$/, '') !== registry,
        )
        const additions = []
        if (vcs) {
            const refs = JSON.parse(
                readFileSync(join(directory, 'vcs-refs.json'), 'utf8'),
            )
            for (const theme of themes) {
                if (!/^[a-f0-9]{40}$/.test(refs[theme]))
                    throw new Error(`Invalid reviewed ref: ${theme}`)
                additions.push({
                    type: 'vcs',
                    url: `https://github.com/filamentphp/${theme}-theme`,
                })
                composer.require[`filament/${theme}-theme`] =
                    `1.x-dev#${refs[theme]}`
            }
        } else if (
            !entries.some(
                ([, entry]) =>
                    entry?.type === 'composer' &&
                    entry.url?.replace(/\/$/, '') === registry,
            )
        ) {
            additions.push({ type: 'composer', url: registry })
        }
        composer.repositories = Array.isArray(originalRepositories)
            ? [...additions, ...entries.map(([, entry]) => entry)]
            : Object.fromEntries([
                  ...additions.map((entry, index) => [
                      `theme-preview-${index}`,
                      entry,
                  ]),
                  ...entries,
              ])
    }
    add('composer.json', json(composer), true)
    originals.set('composer.lock', originalLock)

    let provider = read('app/Providers/Filament/AdminPanelProvider.php')
    if (provider.split('                StartSession::class,\n').length !== 2) {
        throw new Error(
            'Unsupported admin session middleware shape; no files changed',
        )
    }
    provider = replaceOnce(
        provider,
        'return $panel\n',
        'return \\App\\LiveDemo\\Theme::configure($panel)\n',
        'admin panel',
    )
    provider = replaceOnce(
        provider,
        "            ->viteTheme('resources/css/filament/admin/theme.css')\n",
        '',
        'admin theme',
    )
    provider = replaceOnce(
        provider,
        "            ->spa()\n            ->colors([\n                'primary' => Color::Blue,\n            ])\n            ->font('Albert Sans');",
        '            ->spa();',
        'admin identity',
    )
    provider = replaceOnce(
        provider,
        'use Filament\\Support\\Colors\\Color;\n',
        '',
        'admin import',
    )
    add('app/Providers/Filament/AdminPanelProvider.php', provider, true)

    const inputs = [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/filament/admin/theme.css',
    ]
    const sources = read('resources/css/filament/admin/theme.css')
        .split('\n')
        .filter((line) => line.trimStart().startsWith('@source '))
        .map((line) => line.replaceAll('../../../../', '../../../'))
        .join('\n')
    if (!sources) throw new Error('Admin CSS sources missing')
    for (const theme of variants) {
        for (const compact of [false, true]) {
            const imports = [
                "@import '../../../vendor/filament/compact-theme/resources/css/layers.css';",
                "@import '../../../vendor/filament/filament/resources/css/theme.css';",
            ]
            if (theme !== 'stock')
                imports.push(
                    `@import '../../../vendor/filament/${theme}-theme/resources/css/theme.css';`,
                )
            if (compact)
                imports.push(
                    "@import '../../../vendor/filament/compact-theme/resources/css/theme.css';",
                )
            const path = `resources/css/live-demo/${theme}${compact ? '-compact' : ''}.css`
            add(path, `${imports.join('\n')}\n\n${sources}\n`)
            inputs.push(path)
        }
    }
    inputs.push(
        'resources/css/live-demo-toolbar.css',
        'resources/js/live-demo.js',
    )
    add(
        'vite.config.js',
        replaceOnce(
            read('vite.config.js'),
            "input: ['resources/css/app.css', 'resources/js/app.js', 'resources/css/filament/admin/theme.css'],",
            `input: ${JSON.stringify(inputs)},`,
            'Vite inputs',
        ),
        true,
    )
    function templates(path = '') {
        for (const entry of readdirSync(join(directory, 'templates', path), {
            withFileTypes: true,
        })) {
            const child = join(path, entry.name)
            if (entry.isDirectory()) templates(child)
            else
                add(
                    child,
                    readFileSync(join(directory, 'templates', child), 'utf8'),
                )
        }
    }
    templates()
    const write = (path, content) => {
        mkdirSync(dirname(join(root, path)), { recursive: true })
        writeFileSync(join(root, path), content)
    }
    try {
        write('composer.json', files.get('composer.json'))
        run(root, 'composer', [
            'update',
            ...themes.map((theme) => `filament/${theme}-theme`),
            'kienso/blade-google-material-symbols',
            '--no-interaction',
            '--prefer-dist',
            '--no-scripts',
            ...(production ? ['--no-dev'] : []),
        ])
        for (const theme of themes) {
            if (
                !existsSync(
                    join(
                        root,
                        `vendor/filament/${theme}-theme/resources/css/theme.css`,
                    ),
                )
            )
                throw new Error(`Installed ${theme} package is missing CSS`)
        }
        for (const [path, value] of files) write(path, value)
        run(root, 'composer', [
            'dump-autoload',
            '--no-interaction',
            ...(production ? ['--no-dev'] : []),
        ])
        const digests = Object.fromEntries(
            [...files.keys(), 'composer.lock'].map((path) => [
                path,
                hash(read(path)),
            ]),
        )
        writeFileSync(statePath, json({ options, files: digests }))
        console.log(
            'Theme preview installed. Build assets before production optimizations; do not commit generated files.',
        )
    } catch (error) {
        for (const [path, original] of originals) {
            if (original === null) rmSync(join(root, path), { force: true })
            else write(path, original)
        }
        throw new Error(
            `${error.message}\nSource files and Composer manifests restored. Vendor/cache may be partially updated: discard this build or run composer install and php artisan optimize:clear before retrying. Do not serve this failed build.`,
        )
    }
}

function execute(cwd, command, args) {
    const result = spawnSync(command, args, { cwd, stdio: 'inherit' })
    if (result.status !== 0)
        throw new Error(`${command} failed (exit ${result.status})`)
}

if (
    process.argv[1] &&
    resolve(process.argv[1]) === fileURLToPath(import.meta.url)
) {
    try {
        const options = {}
        for (let index = 2; index < process.argv.length; index++) {
            const arg = process.argv[index]
            if (arg === '--vcs') options.vcs = true
            else if (arg === '--production') options.production = true
            else throw new Error(`Unknown or incomplete argument: ${arg}`)
        }
        install(resolve(directory, '../..'), options)
    } catch (error) {
        console.error(`theme-preview: ${error.message}`)
        process.exitCode = 1
    }
}
