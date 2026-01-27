<header class="flex h-14 items-center border-b border-black/10 bg-white px-6">
    <div class="tp-muted flex-1 text-sm">
        {{ $title ?? 'Admin' }}
    </div>

    <div class="flex items-center gap-3">
        @auth
            <span class="tp-muted text-sm">Hello, {{ auth()->user()->name ?? 'User' }}</span>
            <form method="POST" action="{{ route('tp.logout') }}">
                @csrf
                <button type="submit" class="tp-button-secondary">Log out</button>
            </form>
        @endauth
    </div>
</header>
