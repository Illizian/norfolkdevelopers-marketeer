<dropdown-trigger class="h-9 flex items-center">
    @isset($user->email)
        <img
            src="https://secure.gravatar.com/avatar/{{ md5(\Illuminate\Support\Str::lower($user->email)) }}?size=512"
            class="rounded-full w-8 h-8 mr-3"
        />
    @endisset

    <span class="text-90">
        {{ $user->name ?? $user->email ?? __('Nova User') }}
    </span>
</dropdown-trigger>

<dropdown-menu slot="menu" width="200" direction="rtl">
    <ul class="list-reset">
        <li>
            <a href="{{ route('profile.show') }}" class="block no-underline text-90 hover:bg-30 p-3">
                {{ __('Profile') }}
            </a>
        </li>
        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="block no-underline text-90 hover:bg-30 p-3">
                    {{ __('Logout') }}
                </button>
            </form>
        </li>
    </ul>
</dropdown-menu>
