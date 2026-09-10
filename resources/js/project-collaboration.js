import { Extension } from '@tiptap/core'
import Collaboration from '@tiptap/extension-collaboration'
import CollaborationCaret from '@tiptap/extension-collaboration-caret'
import { HocuspocusProvider } from '@hocuspocus/provider'
import * as Y from 'yjs'

export default () => {
    let provider, doc, editor, wire

    const updateSharedState = () => {
        wire.$set('data.description_state', btoa(Array.from(Y.encodeStateAsUpdate(doc), byte => String.fromCharCode(byte)).join('')), false)
    }

    return Extension.create({
        // Replace Filament's local history with Yjs's collaborative undo/redo.
        name: 'undoRedo',
        addExtensions() {
            const container = document.querySelector('[data-collaboration]')
            const config = JSON.parse(atob(container.dataset.collaboration))
            doc = new Y.Doc()
            wire = Livewire.find(container.closest('[wire\\:id]').getAttribute('wire:id'))

            provider = new HocuspocusProvider({
                url: `${location.protocol === 'https:' ? 'wss:' : 'ws:'}//${location.host}/collaboration`,
                name: config.document,
                token: config.token,
                document: doc,
                onSynced: () => {
                    editor?.setEditable(true)
                    updateSharedState()
                },
                onStatus: ({ status: connection }) => {
                    if (connection !== 'connected') {
                        editor?.setEditable(false)
                    }
                },
                onAuthenticationFailed: () => {
                    editor?.setEditable(false)
                },
            })

            return [
                Collaboration.configure({ document: doc }),
                CollaborationCaret.configure({
                    provider,
                    user: { name: config.user.name, color: ['#2563eb', '#c026d3', '#059669', '#ea580c'][doc.clientID % 4] },
                }),
            ]
        },
        onBeforeCreate() {
            editor = this.editor
            this.editor.options.content = ''
            this.editor.options.editable = false
        },
        onUpdate() {
            if (provider?.synced) updateSharedState()
        },
        onDestroy() {
            provider?.destroy()
            doc?.destroy()
        },
    })
}
