@auth
    @livewire(\App\Livewire\PageChat::class, ['page' => request()->path()], key('page-chat-' . hash('sha256', request()->path())))

    <script>
        if (! window.__pageAgentBridgeInstalled) {
            window.__pageAgentBridgeInstalled = true

            document.addEventListener('page-agent-request', async (event) => {
                const replyId = event.detail.replyId
                const chatElement = event.target.closest?.('[wire\\:id]')
                const chat = chatElement ? Livewire.find(chatElement.getAttribute('wire:id')) : null
                const page = Livewire.all().find((component) => component.name.startsWith('App\\Filament\\Resources\\') && component.name.includes('\\Pages\\'))

                if (! page) {
                    await chat?.agentUnavailable(replyId)
                    return
                }

                try {
                    await page.$wire.pageAgentReply(replyId)
                } catch (error) {
                    console.error('The Agent request could not be completed. Its status may be checked before trying again.', error)
                }
            })
        }
    </script>
@endauth
