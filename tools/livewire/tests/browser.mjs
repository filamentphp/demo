import assert from 'node:assert/strict'
import { createRequire } from 'node:module'
import path from 'node:path'

// Run from a checkout with Playwright installed, such as the Filament repository.
const { chromium } = createRequire(path.join(process.cwd(), 'package.json'))(
    'playwright',
)

// Run against a disposable local demo with lazy notifications enabled.
// CASES=bundle also exercises the unpatched eager-notification baseline.
const baseUrl = process.env.INERTIA_DEMO_URL || 'http://127.0.0.1:8000'
const cases = (process.env.CASES || 'away,restored,bundle').split(',')
const notificationName = 'filament.livewire.database-notifications'
const browser = await chromium.launch({ headless: true })
let failed = false

function deferred() {
    let resolve
    const promise = new Promise((complete) => {
        resolve = complete
    })
    return { promise, resolve }
}

async function preparePage() {
    const context = await browser.newContext({
        viewport: { width: 1440, height: 1000 },
    })
    const page = await context.newPage()
    const errors = []
    page.on('pageerror', (error) =>
        errors.push({ message: error.message, stack: error.stack }),
    )
    await page.addInitScript(() => {
        const probe = (window.lifecycleProbe = {
            instances: [],
            activity: [],
            dispatched: [],
            promises: {},
        })
        const generations = new WeakMap()
        let nextGeneration = 0
        const generation = (component) => {
            if (!generations.has(component))
                generations.set(component, ++nextGeneration)
            return generations.get(component)
        }
        document.addEventListener('lifecycle-response-probe', (event) =>
            probe.dispatched.push(event.detail),
        )
        document.addEventListener('livewire:init', () => {
            Livewire.hook('component.init', ({ component, cleanup }) => {
                const entry = {
                    component,
                    generation: generation(component),
                    destroyed: false,
                }
                probe.instances.push(entry)
                cleanup(() => {
                    entry.destroyed = true
                })
                const merge = component.mergeNewSnapshot
                component.mergeNewSnapshot = function (...arguments_) {
                    probe.activity.push({
                        type: 'snapshot',
                        generation: entry.generation,
                        id: component.id,
                        connected: component.el.isConnected,
                    })
                    return merge.apply(this, arguments_)
                }
            })
            for (const type of ['effect', 'morph']) {
                Livewire.hook(type, ({ component }) =>
                    probe.activity.push({
                        type,
                        generation: generation(component),
                        id: component.id,
                        connected: component.el.isConnected,
                    }),
                )
            }
            Livewire.hook('commit', ({ component, respond, succeed }) => {
                const record = (type) =>
                    probe.activity.push({
                        type,
                        generation: generation(component),
                        id: component.id,
                        connected: component.el.isConnected,
                    })
                respond(() => record('respond'))
                succeed(() => record('succeed'))
                return () => record('finish')
            })
        })
    })
    await page.goto(`${baseUrl}/login`)
    await page.locator('button[type="submit"]').click({ noWaitAfter: true })
    await page
        .getByText('Welcome to the Filament Demo!', { exact: true })
        .waitFor()
    await page.waitForFunction(
        (name) =>
            Livewire.all().some(
                (component) =>
                    component.name === name &&
                    component.snapshot.memo.lazyLoaded !== false,
            ),
        notificationName,
    )
    return { context, page, errors }
}

async function holdResponse(page, predicate) {
    const held = deferred()
    const release = deferred()
    const delivered = deferred()
    let selected = false
    await page.route('**/livewire/update', async (route) => {
        const payload = route.request().postDataJSON()
        const components = payload.components.map((component) => ({
            id: JSON.parse(component.snapshot).memo.id,
            name: JSON.parse(component.snapshot).memo.name,
            calls: component.calls.map((call) => call.method),
        }))
        if (selected || !predicate(components)) return route.continue()
        selected = true
        try {
            // Complete server processing before allowing the test to navigate.
            const response = await route.fetch()
            const body = await response.json()
            body.components.forEach((component, index) => {
                component.effects.dispatches = [
                    ...(component.effects.dispatches || []),
                    {
                        name: 'lifecycle-response-probe',
                        params: { id: components[index].id },
                    },
                ]
            })
            held.resolve({ components })
            await release.promise
            try {
                await route.fulfill({ response, json: body })
                delivered.resolve('fulfilled')
            } catch (error) {
                delivered.resolve(
                    `transport cancelled: ${error.message.split('\n')[0]}`,
                )
            }
        } catch (error) {
            held.resolve({ error: error.message })
            delivered.resolve('request failed')
        }
    })
    return {
        async held() {
            const result = await Promise.race([
                held.promise,
                new Promise((_, reject) =>
                    setTimeout(
                        () =>
                            reject(
                                new Error(
                                    'No matching request; check laziness/bundling configuration',
                                ),
                            ),
                        10000,
                    ),
                ),
            ])
            if (result.error) throw new Error(result.error)
            return result
        },
        async release() {
            release.resolve()
            const outcome = await delivered.promise
            await page.waitForTimeout(250)
            return outcome
        },
    }
}

async function generationFor(page, id) {
    return page.evaluate((id) => {
        const entry = window.lifecycleProbe.instances.findLast(
            (entry) => entry.component.id === id && !entry.destroyed,
        )
        return entry?.generation
    }, id)
}

async function lazyCase(restore) {
    const { context, page, errors } = await preparePage()
    try {
        const response = await holdResponse(page, (components) =>
            components.some(
                (component) =>
                    component.name === notificationName &&
                    component.calls.includes('__lazyLoad'),
            ),
        )
        await page
            .locator('a[href$="/inertia-workbench"]')
            .click({ noWaitAfter: true })
        await page.waitForURL('**/inertia-workbench')
        const { components } = await response.held()
        const oldId = components.find(
            (component) => component.name === notificationName,
        ).id
        const oldGeneration = await generationFor(page, oldId)
        assert.ok(
            oldGeneration,
            'Old instance must exist before navigating away',
        )
        await page.evaluate(() => Livewire.navigate('/'))
        await page
            .getByText('Welcome to the Filament Demo!', { exact: true })
            .waitFor()
        await page.waitForFunction(
            (generation) =>
                window.lifecycleProbe.instances.find(
                    (entry) => entry.generation === generation,
                ).destroyed,
            oldGeneration,
        )
        let restoredGeneration
        if (restore) {
            await page.goBack()
            await page.waitForURL('**/inertia-workbench')
            await page.waitForFunction((id) => {
                const entry = window.lifecycleProbe.instances.findLast(
                    (entry) => entry.component.id === id && !entry.destroyed,
                )
                return (
                    entry && entry.component.snapshot.memo.lazyLoaded === true
                )
            }, oldId)
            restoredGeneration = await generationFor(page, oldId)
            assert.notEqual(
                restoredGeneration,
                oldGeneration,
                'Back must restore a NEW instance with the SAME wire:id',
            )
        } else {
            await page.waitForFunction(
                (name) =>
                    Livewire.all().some(
                        (component) =>
                            component.name === name &&
                            component.snapshot.memo.lazyLoaded === true,
                    ),
                notificationName,
            )
        }
        await page.evaluate(() => {
            window.lifecycleProbe.activity = []
            window.lifecycleProbe.dispatched = []
        })
        const transport = await response.release()
        const evidence = await page.evaluate(() => ({
            activity: window.lifecycleProbe.activity,
            dispatched: window.lifecycleProbe.dispatched,
        }))
        console.log(
            JSON.stringify({
                case: restore ? 'restored' : 'away',
                oldId,
                oldGeneration,
                restoredGeneration,
                transport,
                ...evidence,
                errors,
            }),
        )
        assert.deepEqual(
            evidence.activity
                .filter((event) => event.generation === oldGeneration)
                .map((event) => event.type),
            ['respond'],
            'Old response must only run respond cleanup',
        )
        assert.deepEqual(
            evidence.activity.filter(
                (event) => event.generation === restoredGeneration,
            ),
            [],
            'Old response must not mutate the restored instance',
        )
        assert.deepEqual(
            evidence.dispatched.filter((event) => event.id === oldId),
            [],
            'Old response effect escaped',
        )
        assert.deepEqual(errors, [])
    } finally {
        await context.close()
    }
}

async function bundleCase() {
    const { context, page, errors } = await preparePage()
    try {
        await page
            .locator('a[href$="/inertia-workbench"]')
            .click({ noWaitAfter: true })
        await page.waitForURL('**/inertia-workbench')
        await page
            .locator('#deferred-analytics')
            .filter({ hasText: '137' })
            .waitFor()
        await page.waitForFunction(
            (name) =>
                Livewire.all().some(
                    (component) =>
                        component.name === name &&
                        component.snapshot.memo.lazyLoaded !== false,
                ),
            notificationName,
        )
        const response = await holdResponse(
            page,
            (components) =>
                components.length === 2 &&
                components.some(
                    (component) => component.name === notificationName,
                ) &&
                components.some((component) =>
                    component.calls.includes('refreshShell'),
                ),
        )
        const ids = await page.evaluate((name) => {
            const notification = Livewire.all().find(
                (component) => component.name === name,
            )
            const shell = Livewire.all().find(
                (component) =>
                    component.name === 'app.filament.pages.inertia-workbench',
            )
            const track = (key, promise) => {
                window.lifecycleProbe.promises[key] = 'pending'
                promise.then(
                    () => {
                        window.lifecycleProbe.promises[key] = 'resolved'
                    },
                    () => {
                        window.lifecycleProbe.promises[key] = 'rejected'
                    },
                )
            }
            // Schedule both ordinary commits in the same bundling interval.
            track('destroyed', notification.$wire.$refresh())
            track('surviving', shell.$wire.$call('refreshShell'))
            return { destroyed: notification.id, surviving: shell.id }
        }, notificationName)
        const { components } = await response.held()
        const oldGeneration = await generationFor(page, ids.destroyed)
        await page.evaluate((id) => {
            const component = Livewire.find(id).__instance
            Alpine.destroyTree(component.el)
            component.el.remove()
            window.lifecycleProbe.activity = []
            window.lifecycleProbe.dispatched = []
        }, ids.destroyed)
        const transport = await response.release()
        await page.waitForFunction(
            () => window.lifecycleProbe.promises.surviving !== 'pending',
        )
        const evidence = await page.evaluate(() => ({
            activity: window.lifecycleProbe.activity,
            dispatched: window.lifecycleProbe.dispatched,
            promises: window.lifecycleProbe.promises,
            count: document
                .querySelector('#shell-refresh-count')
                .textContent.trim(),
        }))
        console.log(
            JSON.stringify({
                case: 'bundle',
                ids,
                components,
                oldGeneration,
                transport,
                ...evidence,
                errors,
            }),
        )
        assert.deepEqual(
            evidence.activity
                .filter((event) => event.generation === oldGeneration)
                .map((event) => event.type),
            ['respond'],
            'Destroyed bundled component must only run respond cleanup',
        )
        assert.equal(
            evidence.promises.destroyed,
            'resolved',
            'Destroyed commit promise did not settle',
        )
        assert.equal(
            evidence.promises.surviving,
            'resolved',
            'Valid sibling promise did not resolve',
        )
        assert.equal(
            evidence.count,
            '1',
            'Valid sibling received the wrong response or failed to morph',
        )
        assert.deepEqual(
            evidence.dispatched.map((event) => event.id),
            [ids.surviving],
            'Bundled response/effect alignment was lost',
        )
        assert.deepEqual(errors, [])
    } finally {
        await context.close()
    }
}

try {
    for (const name of cases) {
        try {
            if (name === 'bundle') await bundleCase()
            else await lazyCase(name === 'restored')
            console.log(`PASS ${name}`)
        } catch (error) {
            failed = true
            console.log(`FAIL ${name}: ${error.stack}`)
        }
    }
} finally {
    await browser.close()
}
process.exitCode = failed ? 1 : 0
