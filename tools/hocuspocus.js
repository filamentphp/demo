import { createHmac, timingSafeEqual } from 'node:crypto'
import { Server } from '@hocuspocus/server'
import { getSchema } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import { prosemirrorJSONToYDoc } from 'y-prosemirror'
import * as Y from 'yjs'

const key = createHmac('sha256', process.env.APP_KEY).update('project-collaboration').digest('hex')
const schema = getSchema([StarterKit])

async function request(projectId) {
    const response = await fetch(`http://127.0.0.1:8001/api/collaboration/projects/${projectId}`, {
        headers: { Authorization: `Bearer ${key}`, Accept: 'application/json', 'Content-Type': 'application/json' },
    })
    if (!response.ok) throw new Error(`Document request failed (${response.status})`)
    return response.json()
}

const server = new Server({
    address: '127.0.0.1',
    port: Number(process.env.PORT || 8090),
    debounce: 750,
    maxDebounce: 2500,
    async onAuthenticate({ token, documentName }) {
        const [payload, signature = ''] = token.split('.')
        const expected = createHmac('sha256', key).update(payload || '').digest('hex')
        if (signature.length !== expected.length || !timingSafeEqual(Buffer.from(signature), Buffer.from(expected))) {
            throw new Error('Unauthorized')
        }
        const user = JSON.parse(Buffer.from(payload, 'base64').toString())
        const version = user.version || 0
        const expectedDocument = `project.${user.projectId}${version ? `.v${version}` : ''}`
        if (user.expires < Date.now() / 1000 || documentName !== expectedDocument) throw new Error('Unauthorized')
        const initial = await request(user.projectId)
        if (initial.version !== version) throw new Error('Description has been replaced. Reload the project.')
        return { ...user, initial }
    },
    async onLoadDocument({ context }) {
        if (context.initial.state) {
            const doc = new Y.Doc()
            Y.applyUpdate(doc, Buffer.from(context.initial.state, 'base64'))
            return doc
        }
        return prosemirrorJSONToYDoc(schema, context.initial.content, 'default')
    },
})

await server.listen()
