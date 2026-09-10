@auth
    @livewire(\App\Livewire\PageChat::class, ['page' => request()->path()], key('page-chat-' . hash('sha256', request()->path())))
@endauth
