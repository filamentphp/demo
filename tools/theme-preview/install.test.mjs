import assert from 'node:assert/strict'
import { spawnSync } from 'node:child_process'
import { createHash } from 'node:crypto'
import {
    cpSync,
    lstatSync,
    mkdtempSync,
    mkdirSync,
    readFileSync,
    rmSync,
    symlinkSync,
    writeFileSync,
} from 'node:fs'
import { tmpdir } from 'node:os'
import { dirname, join, resolve } from 'node:path'
import { afterEach, test } from 'node:test'

import { install } from './install.mjs'

const projectRoot = resolve(import.meta.dirname, '../..')
const sourceFiles = [
    'composer.json',
    'composer.lock',
    'vite.config.js',
    'app/Providers/Filament/AdminPanelProvider.php',
    'resources/css/filament/admin/theme.css',
]
const themes = ['compact', 'sharp', 'soft', 'noir']
const variants = [
    'stock',
    'stock-compact',
    'sharp',
    'sharp-compact',
    'soft',
    'soft-compact',
    'noir',
    'noir-compact',
]
const templateFiles = [
    'app/LiveDemo/DemoMaterialSymbols.php',
    'app/LiveDemo/PreviewPanel.php',
    'app/LiveDemo/Selection.php',
    'app/LiveDemo/Theme.php',
    'resources/css/live-demo-toolbar.css',
    'resources/js/live-demo.js',
    'resources/views/live-demo/toolbar.blade.php',
    'tests/Feature/LiveDemoTest.php',
]
const temporaryDirectories = []
const digest = (value) => createHash('sha256').update(value).digest('hex')
const read = (root, path) => readFileSync(join(root, path), 'utf8')

function fixture() {
    const root = mkdtempSync(join(tmpdir(), 'theme-preview-install-'))
    temporaryDirectories.push(root)

    for (const path of sourceFiles) {
        mkdirSync(dirname(join(root, path)), { recursive: true })
        cpSync(join(projectRoot, path), join(root, path))
    }

    return root
}

function snapshot(root, paths = sourceFiles) {
    return Object.fromEntries(paths.map((path) => [path, read(root, path)]))
}

function fakeComposer({ failOn } = {}) {
    const calls = []
    const run = (root, command, args) => {
        calls.push({ command, args: [...args] })
        const operation = args[0]

        if (operation === 'update') {
            const lock = JSON.parse(read(root, 'composer.lock'))
            lock.packages ??= []
            lock.packages.push(
                ...themes.map((theme) => ({
                    name: `filament/${theme}-theme`,
                    version: '1.0.0',
                })),
            )
            writeFileSync(
                join(root, 'composer.lock'),
                `${JSON.stringify(lock, null, 4)}\n`,
            )

            for (const theme of themes) {
                const path = join(
                    root,
                    `vendor/filament/${theme}-theme/resources/css/theme.css`,
                )
                mkdirSync(dirname(path), { recursive: true })
                writeFileSync(path, `/* simulated ${theme} theme */\n`)
            }
        }

        if (failOn === operation) {
            throw new Error(`simulated composer ${operation} failure`)
        }
    }

    return { calls, run }
}

afterEach(() => {
    for (const directory of temporaryDirectories.splice(0)) {
        rmSync(directory, { recursive: true, force: true })
    }
})

test('importing the installer does not invoke it or alter a checkout', () => {
    const root = fixture()
    const before = snapshot(root)
    cpSync(import.meta.dirname, join(root, 'tools/theme-preview'), {
        recursive: true,
    })
    const result = spawnSync(
        process.execPath,
        [
            '--input-type=module',
            '-e',
            "await import('./tools/theme-preview/install.mjs')",
        ],
        { cwd: root, encoding: 'utf8' },
    )

    assert.equal(result.status, 0, result.stderr)
    assert.equal(result.stdout, '')
    assert.deepEqual(snapshot(root), before)
    assert.throws(() => lstatSync(join(root, 'app/LiveDemo')), {
        code: 'ENOENT',
    })
})

test('default install uses published packages and the production Composer repository', () => {
    const root = fixture()
    const originalLock = JSON.parse(read(root, 'composer.lock'))
    const composer = fakeComposer()

    install(root, { run: composer.run })

    const manifest = JSON.parse(read(root, 'composer.json'))
    for (const theme of themes) {
        assert.equal(manifest.require[`filament/${theme}-theme`], '^1.0')
    }
    assert.equal(
        manifest.require['kienso/blade-google-material-symbols'],
        '^1.0',
    )
    assert.ok(
        Array.isArray(manifest.repositories),
        'default install must configure a Composer repository',
    )
    assert.deepEqual(manifest.repositories[0], {
        type: 'composer',
        url: 'https://packages.filamentphp.com/composer',
    })
    assert.deepEqual(
        JSON.parse(read(root, '.theme-preview-install.json')).options,
        { vcs: false, production: false },
    )

    const update = composer.calls.find(({ args }) => args[0] === 'update')
    assert.ok(update)
    assert.deepEqual(update.args.slice(1, 6), [
        'filament/compact-theme',
        'filament/sharp-theme',
        'filament/soft-theme',
        'filament/noir-theme',
        'kienso/blade-google-material-symbols',
    ])
    assert.equal(update.args.includes('-W'), false)
    assert.equal(update.args.includes('--with-all-dependencies'), false)
    assert.equal(update.args.includes('--with-dependencies'), false)

    const lock = JSON.parse(read(root, 'composer.lock'))
    for (const pkg of originalLock.packages) {
        assert.ok(
            lock.packages.some(
                ({ name, version }) =>
                    name === pkg.name && version === pkg.version,
            ),
            `lock retained ${pkg.name}`,
        )
    }
})

test('VCS install pins four reviewed private repositories without the production registry', () => {
    const root = fixture()
    const composer = fakeComposer()
    const refs = JSON.parse(
        readFileSync(join(import.meta.dirname, 'vcs-refs.json'), 'utf8'),
    )
    const original = JSON.parse(read(root, 'composer.json'))
    original.repositories = [
        { type: 'composer', url: 'https://packages.filamentphp.com/composer' },
    ]
    writeFileSync(join(root, 'composer.json'), JSON.stringify(original))

    install(root, { vcs: true, run: composer.run })

    const manifest = JSON.parse(read(root, 'composer.json'))
    assert.equal(
        manifest.repositories.some(
            ({ url }) => url === 'https://packages.filamentphp.com/composer',
        ),
        false,
    )
    assert.deepEqual(
        new Set(manifest.repositories.map(({ type, url }) => `${type}:${url}`)),
        new Set(
            themes.map(
                (theme) => `vcs:https://github.com/filamentphp/${theme}-theme`,
            ),
        ),
    )
    for (const theme of themes) {
        assert.equal(
            manifest.require[`filament/${theme}-theme`],
            `1.x-dev#${refs[theme]}`,
        )
    }
})

test('preserves an equivalent named registry and other named repository settings', () => {
    const root = fixture()
    const original = JSON.parse(read(root, 'composer.json'))
    original.repositories = {
        filament: {
            type: 'composer',
            url: 'https://packages.filamentphp.com/composer/',
        },
        'packagist.org': false,
    }
    writeFileSync(join(root, 'composer.json'), JSON.stringify(original))
    install(root, { run: fakeComposer().run })
    assert.deepEqual(
        JSON.parse(read(root, 'composer.json')).repositories,
        original.repositories,
    )
})

test('production mode omits development dependencies in both Composer steps', () => {
    const root = fixture()
    const composer = fakeComposer()
    install(root, { production: true, run: composer.run })
    assert.equal(composer.calls.length, 2)
    assert.ok(composer.calls.every(({ args }) => args.includes('--no-dev')))
})

test('retains stock Vite inputs and generates CSS for all eight theme hosts', () => {
    const root = fixture()
    install(root, { run: fakeComposer().run })

    const vite = read(root, 'vite.config.js')
    for (const input of [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/filament/admin/theme.css',
    ]) {
        assert.match(vite, new RegExp(input.replaceAll('/', '\\/')))
    }

    for (const variant of variants) {
        const css = read(root, `resources/css/live-demo/${variant}.css`)
        assert.match(
            css,
            /vendor\/filament\/filament\/resources\/css\/theme\.css/,
        )
        assert.match(css, /@source '\.\.\/\.\.\/\.\.\/app\/Filament\/\*\*\/\*'/)
    }
    assert.equal(
        (
            vite.match(
                /resources\/css\/live-demo\/(?:stock|sharp|soft|noir)(?:-compact)?\.css/g,
            ) ?? []
        ).length,
        8,
    )
})

test('unsupported source shape fails before any mutation or Composer invocation', () => {
    const root = fixture()
    const composer = fakeComposer()
    const providerPath = 'app/Providers/Filament/AdminPanelProvider.php'
    writeFileSync(
        join(root, providerPath),
        read(root, providerPath).replace(
            'return $panel\n',
            'return $panel /* changed */\n',
        ),
    )
    const before = snapshot(root)

    assert.throws(
        () => install(root, { run: composer.run }),
        /Unsupported admin panel shape; no files changed/,
    )
    assert.deepEqual(snapshot(root), before)
    assert.deepEqual(composer.calls, [])
})

test('refuses generated-file collisions without changing source files', () => {
    const root = fixture()
    const collision = join(root, 'resources/css/live-demo/stock.css')
    mkdirSync(dirname(collision), { recursive: true })
    writeFileSync(collision, 'keep me\n')
    const before = snapshot(root)

    assert.throws(
        () => install(root, { run: fakeComposer().run }),
        /Refusing to overwrite resources\/css\/live-demo\/stock\.css/,
    )
    assert.deepEqual(snapshot(root), before)
    assert.equal(readFileSync(collision, 'utf8'), 'keep me\n')
})

test('a repeated install is hash-stable and only restores locked dependencies', () => {
    const root = fixture()
    install(root, { run: fakeComposer().run })
    const stateBefore = read(root, '.theme-preview-install.json')
    const state = JSON.parse(stateBefore)
    const hashesBefore = Object.fromEntries(
        Object.keys(state.files).map((path) => [
            path,
            digest(read(root, path)),
        ]),
    )
    const composer = fakeComposer()

    install(root, { run: composer.run })

    assert.equal(read(root, '.theme-preview-install.json'), stateBefore)
    assert.deepEqual(
        Object.fromEntries(
            Object.keys(state.files).map((path) => [
                path,
                digest(read(root, path)),
            ]),
        ),
        hashesBefore,
    )
    assert.deepEqual(
        composer.calls.map(({ args }) => args[0]),
        ['install', 'dump-autoload'],
    )
})

test('Composer failure restores manifests, provider, Vite, and generated files and warns about vendor state', () => {
    const root = fixture()
    const before = snapshot(root)

    assert.throws(
        () =>
            install(root, {
                run: fakeComposer({ failOn: 'dump-autoload' }).run,
            }),
        (error) => {
            assert.match(
                error.message,
                /Source files and Composer manifests restored/,
            )
            assert.match(
                error.message,
                /Vendor\/cache may be partially updated/,
            )
            return true
        },
    )
    assert.deepEqual(snapshot(root), before)
    const generatedFiles = [
        ...variants.map((variant) => `resources/css/live-demo/${variant}.css`),
        ...templateFiles,
        '.theme-preview-install.json',
    ]
    for (const path of generatedFiles) {
        assert.throws(() => lstatSync(join(root, path)), { code: 'ENOENT' })
    }
})

test('repeat installs reject changed installed files and changed options', () => {
    const changedFileRoot = fixture()
    install(changedFileRoot, { run: fakeComposer().run })
    const installedPath = 'resources/css/live-demo/stock.css'
    writeFileSync(
        join(changedFileRoot, installedPath),
        `${read(changedFileRoot, installedPath)}/* local edit */\n`,
    )
    assert.throws(
        () => install(changedFileRoot, { run: fakeComposer().run }),
        /Installed file changed: resources\/css\/live-demo\/stock\.css/,
    )

    const changedOptionsRoot = fixture()
    install(changedOptionsRoot, { run: fakeComposer().run })
    assert.throws(
        () =>
            install(changedOptionsRoot, {
                production: true,
                run: fakeComposer().run,
            }),
        /Installer options changed/,
    )
})

test('refuses symlinked generated paths', () => {
    const root = fixture()
    const target = mkdtempSync(join(tmpdir(), 'theme-preview-symlink-target-'))
    temporaryDirectories.push(target)
    mkdirSync(join(root, 'resources/css'), { recursive: true })
    symlinkSync(target, join(root, 'resources/css/live-demo'))
    const before = snapshot(root)

    assert.throws(
        () => install(root, { run: fakeComposer().run }),
        /Refusing symlink: resources\/css\/live-demo\/stock\.css/,
    )
    assert.deepEqual(snapshot(root), before)
})

test('refuses dangling output symlinks and a symlinked Composer lock before running Composer', () => {
    for (const path of ['resources/css/live-demo/stock.css', 'composer.lock']) {
        const root = fixture()
        const composer = fakeComposer()
        mkdirSync(dirname(join(root, path)), { recursive: true })
        rmSync(join(root, path), { force: true })
        symlinkSync(join(root, 'missing-target'), join(root, path))
        assert.throws(
            () => install(root, { run: composer.run }),
            /Refusing symlink:/,
        )
        assert.deepEqual(composer.calls, [])
    }
})

test('requires exactly one session middleware before installing request-dependent panel boot', () => {
    const root = fixture()
    const path = 'app/Providers/Filament/AdminPanelProvider.php'
    writeFileSync(
        join(root, path),
        read(root, path).replace('                StartSession::class,\n', ''),
    )
    const before = snapshot(root)
    const composer = fakeComposer()
    assert.throws(
        () => install(root, { run: composer.run }),
        /Unsupported admin session middleware shape/,
    )
    assert.deepEqual(composer.calls, [])
    assert.deepEqual(snapshot(root), before)
})
