<div class="container mx-auto">
    <div class="flex items-center justify-between">
        {{-- home link --}}
        <div class="w-1/4 justify-start">
            <a class="text-white no-underline hover:underline" href="{{ auth()->check() ? route('dashboard') : url('/') }}">
                {{ config('app.name') }}
            </a>
        </div>
        {{-- group nav --}}
        <div class="w-1/2 justify-around">
            @auth
                <pull-down class="flex justify-start items-center w-full" menu-classes="bg-blue-dark border border-blue-darker border-t-0 rounded-b text-left w-1/2 mx-auto" title="{{ __('groups.groups') }}">
                    @foreach ($groups as $group)
                        <a class="flex hover:bg-blue-darker hover:underline no-underline px-4 py-3 text-white" href="{{ route('groups.show', $group) }}">{{ $group->title }}</a>
                    @endforeach
                    <a class="flex hover:bg-blue-darker hover:underline no-underline px-4 py-3 rounded-b text-white" href="{{ route('groups.create') }}">{{ __('groups.create') }}</a>
                </pull-down>
            @endauth
        </div>
        {{-- user nav --}}
        <div class="w-1/4 flex justify-end">
            @guest
                <pull-down class="flex justify-end items-center w-full" menu-classes="bg-blue-dark border border-blue-darker border-t-0 rounded-b text-right w-1/4 pin-r" icon="user">
                    <a class="flex hover:bg-blue-darker hover:underline no-underline px-4 py-3 text-white" href="{{ route('login') }}">
                        {{ __('auth.login') }}
                    </a>
                    @if (Route::has('register'))
                        <a class="flex hover:bg-blue-darker hover:underline no-underline px-4 py-3 rounded-b text-white" href="{{ route('register') }}">{{ __('auth.register') }}</a>
                    @endif
                </pull-down>
            @else
                <pull-down class="flex justify-end items-center w-full" menu-classes="bg-blue-dark border border-blue-darker border-t-0 rounded-b text-right w-1/4 pin-r" title="{{ auth()->user()->name }}">
                    <a class="flex py-3 px-4 hover:bg-blue-darker text-white no-underline hover:underline" href="#{{-- TODO: route('users.show', $user) --}}">
                        {{ __('users.profile') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="flex button hover:bg-blue-darker hover:underline rounded-t-none text-white w-full">{{ __('auth.logout') }}</button>
                    </form>
                </pull-down>
            @endguest
        </div>
    </div>
</div>
