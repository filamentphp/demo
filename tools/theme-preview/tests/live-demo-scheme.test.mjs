import assert from 'node:assert/strict'
import test from 'node:test'

import { resolveScheme } from '../templates/resources/js/live-demo-scheme.js'

test('resolves the system scheme from the browser preference', () => {
    assert.equal(resolveScheme('system', true), 'dark')
    assert.equal(resolveScheme('system', false), 'light')
})

test('preserves an explicit scheme', () => {
    assert.equal(resolveScheme('dark', false), 'dark')
    assert.equal(resolveScheme('light', true), 'light')
})
