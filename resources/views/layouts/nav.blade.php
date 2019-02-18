<div class="container mx-auto">
    <div class="flex items-center justify-between lg:flex-col lg:py-3 lg:px-4">
        {{-- home link --}}
        <div class="w-1/4 justify-start lg:w-full lg:mb-4">
            <a class="text-white no-underline hover:underline lg:py-3" href="{{ auth()->check() ? route('dashboard') : url('/') }}">
                {{ config('app.name') }}
            </a>
        </div>
        {{-- hamburger nav --}}
        <div class="lg:w-full lg:mb-4">
            <pull-down
                class="flex justify-start items-center w-full"
                menu-container="absolute lg:relative mt-16 lg:mt-0 pin-t pin-x lg:w-full"
                menu-classes="absolute lg:relative bg-blue-dark border border-blue-darker border-t-0 lg:border-0 px-2 sm:px-0 py-4 rounded-b text-left w-full"
                title="{{ __('nav.menu') }}"
                icon="menu"
                >
                <div class="container mx-auto">
                    @auth
                        <p class="uppercase tracking-wide leading-normal text-xs">{{ __('groups.groups') }}</p>
                        <ul class="list-reset mb-4">
                            @foreach ($groups as $group)
                                <li>
                                    <a class="nav blue{{ current_url(route('groups.show', $group)) ? ' active' : '' }}" href="{{ route('groups.show', $group) }}">{{ $group->title }}</a>
                                </li>
                            @endforeach
                            <li>
                                <a class="nav blue{{ current_url(route('groups.create')) ? ' active' : '' }}" href="{{ route('groups.create') }}">
                                    <svg-icon name="icon-add" class="w-6 h-6 primary-transparent secondary-white"></svg-icon>
                                    <span>{{ __('groups.create') }}</span>
                                </a>
                            </li>
                        </ul>
                        <p class="uppercase tracking-wide leading-normal text-xs">{{ auth()->user()->name }}</p>
                        <ul class="list-reset">
                            <li>
                                <a class="nav blue{{ current_url(route('users.show', auth()->user())) ? ' active' : '' }}" href="{{ route('users.show', auth()->user()) }}">
                                    {{ __('users.profile') }}
                                </a>
                            </li>
                            <li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="nav blue button rounded-none w-full py-3">{{ __('auth.logout') }}</button>
                                </form>
                            </li>
                        </ul>
                    @else
                        <ul class="list-reset">
                            <li>
                                <a class="flex hover:bg-blue-darker hover:underline no-underline px-4 py-3 text-white" href="{{ route('login') }}">
                                    {{ __('auth.login') }}
                                </a>
                            </li>
                            @if (Route::has('register'))
                                <li>
                                    <a class="flex hover:bg-blue-darker hover:underline no-underline px-4 py-3 text-white" href="{{ route('register') }}">
                                        {{ __('auth.register') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    @endauth
                </div>
            </pull-down>
        </div>
    </div>
</div>
