import assert from 'node:assert/strict'
import { readFile } from 'node:fs/promises'
import path from 'node:path'
import test from 'node:test'
import { fileURLToPath } from 'node:url'
import { createContext, SourceTextModule, SyntheticModule } from 'node:vm'

// Executes the actual dependency modules, with only browser/scheduling boundaries stubbed.
const livewireDirectory =
    process.env.LIVEWIRE_DIRECTORY ||
    fileURLToPath(new URL('../../../../livewire/', import.meta.url))

async function runtime() {
    const microtasks = []
    const timers = []
    const morphs = []
    const context = createContext({
        queueMicrotask: (callback) => microtasks.push(callback),
        setTimeout: (callback) => timers.push(callback),
    })
    const modules = new Map()
    const stubs = {
        alpinejs: {
            default: {
                transaction: (callback) => callback(),
                walk: (form, callback) =>
                    form.nodes.forEach((element) =>
                        callback(element, () => {}),
                    ),
            },
        },
        '@/morph': { morph: (...arguments_) => morphs.push(arguments_) },
        '@/directives': { getDirectives: () => {} },
        '@/request': {
            sendRequest: () => {
                throw new Error('Unexpected network request in unit test')
            },
        },
    }
    async function load(specifier) {
        if (modules.has(specifier)) return modules.get(specifier)
        if (stubs[specifier]) {
            const values = stubs[specifier]
            const module = new SyntheticModule(
                Object.keys(values),
                function () {
                    for (const [key, value] of Object.entries(values))
                        this.setExport(key, value)
                },
                { context },
            )
            modules.set(specifier, module)
            return module
        }
        assert.ok(specifier.startsWith('@/'), `Unexpected import ${specifier}`)
        const filename = path.join(
            livewireDirectory,
            'js',
            `${specifier.slice(2)}.js`,
        )
        const module = new SourceTextModule(await readFile(filename, 'utf8'), {
            context,
            identifier: filename,
        })
        modules.set(specifier, module)
        await module.link(load)
        return module
    }
    async function evaluate(specifier) {
        const module = await load(specifier)
        if (module.status !== 'evaluated') await module.evaluate()
        return module.namespace
    }
    const hooks = await evaluate('@/hooks')
    const { Commit } = await evaluate('@/request/commit')
    return { hooks, Commit, evaluate, microtasks, timers, morphs }
}

function component(id = 'original') {
    const events = []
    const instance = {
        id,
        events,
        el: {},
        children: [],
        canonical: {},
        ephemeral: {},
        snapshot: { memo: { children: {} } },
        snapshotEncoded: JSON.stringify({ memo: { children: {} } }),
        mergeQueuedUpdates: (updates) => updates,
        mergeNewSnapshot(snapshot, effects) {
            events.push('merge')
            this.effects = effects
            this.receivedSnapshot = snapshot
        },
        processEffects() {
            events.push('effects')
        },
    }
    instance.el.__livewire = instance
    return instance
}

function observe(hooks, instance) {
    hooks.on('commit', ({ component, respond, succeed }) => {
        if (component !== instance) return
        respond(() => instance.events.push('respond'))
        succeed(() => instance.events.push('succeed'))
        return () => instance.events.push('finish')
    })
}

function response(id, returns) {
    return {
        snapshot: JSON.stringify({
            data: { marker: id },
            memo: { children: {} },
        }),
        effects: returns === undefined ? {} : { returns },
    }
}

test('normal response merges state, applies effects, resolves calls, and runs success hooks', async () => {
    const { hooks, Commit } = await runtime()
    const instance = component()
    observe(hooks, instance)
    const commit = new Commit(instance)
    const returns = []
    commit.addCall('first', [], (value) => returns.push(value))
    commit.addCall('second', [], (value) => returns.push(value))
    commit.addResolver(() => instance.events.push('resolved'))
    commit.toRequestPayload()[1](response('normal', [17, false]))
    assert.deepEqual(returns, [17, false])
    assert.deepEqual(instance.events, [
        'respond',
        'merge',
        'effects',
        'finish',
        'resolved',
        'succeed',
    ])
})

for (const replacement of ['detached', 'same-id-new-object']) {
    test(`stale ${replacement} response only cleans up and settles server return values`, async () => {
        const { hooks, Commit } = await runtime()
        const instance = component('reused-id')
        observe(hooks, instance)
        const commit = new Commit(instance)
        const returns = []
        commit.addCall('first', [], (value) => returns.push(value))
        commit.addCall('second', [], (value) => returns.push(value))
        commit.addResolver(() => instance.events.push('resolved'))
        const handleResponse = commit.toRequestPayload()[1]
        const restored = component('reused-id')
        if (replacement === 'detached') delete instance.el.__livewire
        else instance.el.__livewire = restored
        handleResponse(response('stale', [31, 'server completed']))
        assert.deepEqual(returns, [31, 'server completed'])
        assert.deepEqual(instance.events, ['respond', 'resolved'])
        assert.deepEqual(restored.events, [])
        assert.equal(instance.receivedSnapshot, undefined)
    })
}

test('destroyed calls settle with undefined when the response has no returns', async () => {
    const { Commit } = await runtime()
    const instance = component()
    const commit = new Commit(instance)
    const returns = []
    commit.addCall('first', [], (value) => returns.push(value))
    commit.addCall('second', [], (value) => returns.push(value))
    let resolved = false
    commit.addResolver(() => {
        resolved = true
    })
    const handleResponse = commit.toRequestPayload()[1]
    delete instance.el.__livewire
    handleResponse(response('stale'))
    assert.deepEqual(returns, [undefined, undefined])
    assert.equal(resolved, true)
    assert.deepEqual(instance.events, [])
})

test('ownership is checked after respond cleanup can destroy the instance', async () => {
    const { hooks, Commit } = await runtime()
    const instance = component()
    observe(hooks, instance)
    hooks.on('commit', ({ respond }) =>
        respond(() => {
            delete instance.el.__livewire
        }),
    )
    const commit = new Commit(instance)
    commit.addResolver(() => instance.events.push('resolved'))
    commit.toRequestPayload()[1](response('stale', []))
    assert.deepEqual(instance.events, ['respond', 'resolved'])
})

test('one destroyed pooled commit does not consume the surviving sibling response', async () => {
    const { hooks, Commit, evaluate } = await runtime()
    const { RequestPool } = await evaluate('@/request/pool')
    const destroyed = component('destroyed')
    const surviving = component('surviving')
    const returns = []
    const pool = new RequestPool()
    for (const instance of [destroyed, surviving]) {
        observe(hooks, instance)
        const commit = new Commit(instance)
        commit.addCall('probe', [], (value) =>
            returns.push([instance.id, value]),
        )
        commit.addResolver(() => instance.events.push('resolved'))
        pool.add(commit)
    }
    const handleResponse = pool.payload()[1]
    delete destroyed.el.__livewire
    handleResponse([
        response('old', ['old-result']),
        response('fresh', ['fresh-result']),
    ])
    assert.deepEqual(returns, [
        ['destroyed', 'old-result'],
        ['surviving', 'fresh-result'],
    ])
    assert.deepEqual(destroyed.events, ['respond', 'resolved'])
    assert.deepEqual(surviving.events, [
        'respond',
        'merge',
        'effects',
        'finish',
        'resolved',
        'succeed',
    ])
    assert.equal(JSON.parse(surviving.receivedSnapshot).data.marker, 'fresh')
})

test('queued morph rechecks ownership after the first microtask; current morph still runs', async () => {
    const { hooks, evaluate, microtasks, morphs } = await runtime()
    await evaluate('@/features/supportMorphDom')
    const old = component('same-id')
    hooks.trigger('effect', {
        component: old,
        effects: { html: '<div>old</div>' },
    })
    microtasks.shift()()
    const restored = component('same-id')
    old.el.__livewire = restored
    microtasks.shift()()
    assert.equal(morphs.length, 0)
    hooks.trigger('effect', {
        component: restored,
        effects: { html: '<div>fresh</div>' },
    })
    microtasks.shift()()
    microtasks.shift()()
    assert.equal(morphs.length, 1)
    assert.equal(morphs[0][0], restored)
    assert.equal(morphs[0][2], '<div>fresh</div>')
})

function form() {
    const submitListeners = []
    const input = {
        tagName: 'INPUT',
        type: 'text',
        readOnly: false,
        hasAttribute: () => false,
    }
    const button = {
        tagName: 'BUTTON',
        type: 'submit',
        disabled: false,
        hasAttribute: () => false,
    }
    return {
        input,
        button,
        nodes: [input, button],
        contains: (element) => element === input || element === button,
        addEventListener: (name, callback) => {
            if (name === 'submit') submitListeners.push(callback)
        },
        submit: () => submitListeners.forEach((callback) => callback()),
    }
}

for (const parentSubmission of [false, true]) {
    test(`old form cleanup cannot enable same-ID replacement form (parent submit: ${parentSubmission})`, async () => {
        const { hooks, evaluate, timers } = await runtime()
        await evaluate('@/features/supportDisablingFormsDuringRequest')
        const old = component('same-id')
        const restored = component('same-id')
        const forms = [form(), form()]
        for (const [index, instance] of [old, restored].entries()) {
            const owner = parentSubmission
                ? { ...component(`child-${index}`), parent: instance }
                : instance
            hooks.trigger('directive.init', {
                el: forms[index],
                directive: {
                    value: 'submit',
                    expression: parentSubmission ? '$parent.save' : 'save',
                },
                cleanup: () => {},
                component: owner,
            })
            timers.shift()()
            forms[index].submit()
        }
        assert.equal(forms[0].button.disabled, true)
        assert.equal(forms[1].input.readOnly, true)
        const cleanups = []
        hooks.trigger('commit', {
            component: old,
            respond: (callback) => cleanups.push(callback),
        })
        cleanups.shift()()
        assert.equal(forms[0].button.disabled, false)
        assert.equal(forms[0].input.readOnly, false)
        assert.equal(forms[1].button.disabled, true)
        assert.equal(forms[1].input.readOnly, true)
        hooks.trigger('commit', {
            component: restored,
            respond: (callback) => cleanups.push(callback),
        })
        cleanups.shift()()
        assert.equal(forms[1].button.disabled, false)
        assert.equal(forms[1].input.readOnly, false)
    })
}
