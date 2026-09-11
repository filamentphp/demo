<div
    aria-label="Project owner and viewers"
    x-on:project-focus-field.window="
        const link = [...document.querySelectorAll('[data-project-field-link]')].find(el => el.dataset.projectFieldLink === $event.detail.field)
        if (link) {
            const panel = link.closest('[role=tabpanel]')
            if (panel) [...panel.closest('.fi-sc-tabs').querySelectorAll('[data-tab-key]')].find(tab => panel.id.endsWith(tab.dataset.tabKey))?.click()
            $nextTick(() => { link.scrollIntoView({ block: 'center', behavior: 'smooth' }); link.focus() })
        }
    "
    x-data="{
        channel: null,
        members: {},
        focuses: {},
        status: 'connecting',
        tabId: crypto.randomUUID ? crypto.randomUUID() : `${Date.now()}-${Math.random()}`,
        userId: @js((string) auth()->id()),
        connection: null,
        connectionHandlers: {},
        focusInHandler: null,
        focusOutHandler: null,
        onlineHandler: null,
        offlineHandler: null,
        people() {
            return Object.values(this.members).filter(person => person.id !== this.userId).sort((first, second) => first.name.localeCompare(second.name))
        },
        memberId(member) {
            return String(member.id)
        },
        setMembers(members) {
            this.members = Object.fromEntries(members.map(member => [this.memberId(member), {
                id: this.memberId(member),
                name: member.name,
            }]))
            this.status = 'connected'
            this.announceFocus(this.fieldName(document.activeElement))
        },
        focusFor(userId) {
            const tabs = Object.values(this.focuses[userId] ?? {})
            return tabs.sort((first, second) => second.at - first.at)[0]?.field ?? null
        },
        receiveFocus(payload) {
            const userId = String(payload?.userId ?? '')
            const tabId = String(payload?.tabId ?? '')
            if (! this.members[userId] || ! tabId) return

            this.focuses[userId] ??= {}
            if (typeof payload.field === 'string' && payload.field) {
                this.focuses[userId][tabId] = { field: payload.field.slice(0, 80), at: Date.now() }
            } else {
                delete this.focuses[userId][tabId]
            }
        },
        fieldName(element) {
            if (! element?.closest('form.fi-sc-form')) return null
            if (! element?.matches('input, textarea, select, [contenteditable=true], [role=textbox]')) return null
            const wrapper = element.closest('.fi-fo-field')
            const label = wrapper?.querySelector('label')?.textContent?.trim()
                ?? element.getAttribute('aria-label')?.trim()
            if (label) return label.replace(/\s+/g, ' ').replace(/\*$/, '').slice(0, 80)

            const name = element.getAttribute('name')
            if (! name) return null
            return name.replace(/^data\.?|\[|\]/g, ' ').replace(/[._-]+/g, ' ').trim().replace(/\b\w/g, letter => letter.toUpperCase()).slice(0, 80)
        },
        announceFocus(field) {
            this.receiveFocus({ userId: this.userId, tabId: this.tabId, field })
            this.channel?.whisper('field-focus', { userId: this.userId, tabId: this.tabId, field })
        },
        bindConnection() {
            this.connection = window.Echo?.connector?.pusher?.connection ?? null
            if (! this.connection?.bind) return
            this.connectionHandlers.connected = () => { this.status = 'connecting' }
            this.connectionHandlers.connecting = () => { this.status = 'reconnecting' }
            this.connectionHandlers.unavailable = () => { this.status = 'reconnecting' }
            this.connectionHandlers.disconnected = () => { this.status = 'reconnecting' }
            Object.entries(this.connectionHandlers).forEach(([event, handler]) => this.connection.bind(event, handler))
        },
        init() {
            this.channel = window.Echo.join(@js('project.' . $record->getKey()))
                .here(members => this.setMembers(members))
                .joining(member => {
                    this.members[this.memberId(member)] = { id: this.memberId(member), name: member.name }
                    this.announceFocus(this.fieldName(document.activeElement))
                })
                .leaving(member => {
                    delete this.members[this.memberId(member)]
                    delete this.focuses[this.memberId(member)]
                })
                .listenForWhisper('field-focus', payload => this.receiveFocus(payload))
                .error(() => { this.status = navigator.onLine ? 'reconnecting' : 'offline' })

            this.focusInHandler = event => {
                const field = this.fieldName(event.target)
                if (field) this.announceFocus(field)
            }
            this.focusOutHandler = event => {
                setTimeout(() => {
                    if (! this.fieldName(document.activeElement)) this.announceFocus(null)
                })
            }
            this.onlineHandler = () => { this.status = 'reconnecting' }
            this.offlineHandler = () => { this.status = 'offline' }
            document.addEventListener('focusin', this.focusInHandler)
            document.addEventListener('focusout', this.focusOutHandler)
            window.addEventListener('online', this.onlineHandler)
            window.addEventListener('offline', this.offlineHandler)
            this.bindConnection()
        },
        destroy() {
            this.announceFocus(null)
            document.removeEventListener('focusin', this.focusInHandler)
            document.removeEventListener('focusout', this.focusOutHandler)
            window.removeEventListener('online', this.onlineHandler)
            window.removeEventListener('offline', this.offlineHandler)
            Object.entries(this.connectionHandlers).forEach(([event, handler]) => this.connection?.unbind?.(event, handler))
            this.channel?.stopListeningForWhisper?.('field-focus')
            window.Echo.leave(@js('project.' . $record->getKey()))
        },
    }"
    class="flex min-h-9 flex-wrap items-center gap-2"
>
    <div class="me-auto flex flex-wrap items-center gap-x-3 gap-y-1 text-sm">
        <span class="text-gray-500 dark:text-gray-400">Owner <span class="ms-1 font-medium text-gray-700 dark:text-gray-200">{{ $owner?->name ?? 'Unassigned' }}</span></span>
        {{ $this->assignOwnerAction }}
    </div>
    <template x-for="person in people()" :key="person.id">
        <span class="inline-flex max-w-full items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs text-gray-700 dark:bg-white/5 dark:text-gray-300">
            <span class="size-1.5 shrink-0 rounded-full bg-success-500" aria-hidden="true"></span>
            <span class="truncate font-medium" x-text="person.name"></span>
            <span x-show="focusFor(person.id)" class="truncate text-gray-500 dark:text-gray-400" x-text="`· ${focusFor(person.id)}`"></span>
        </span>
    </template>

    <span x-show="status !== 'connected'" x-text="status === 'offline' ? 'Offline' : (status === 'reconnecting' ? 'Reconnecting…' : 'Connecting…')" class="text-xs text-gray-400" role="status"></span>
    <x-filament-actions::modals />
</div>
