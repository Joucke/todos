<!DOCTYPE html>
<html class="h-full" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="h-full">
    <div id="app" class="bg-blue-lightest h-full">
        <nav class="flex border-b-2 border-blue-dark bg-white shadow fixed pin-t pin-x z-100 h-16 items-center">
            <div class="container relative mx-auto">
                <div class="flex items-center -mx-6">
                    <div class="lg:w-1/4 xl:w-1/5 px-6 lg:pr-8">
                        <a class="" href="{{ url('/') }}">
                            {{ config('app.name') }}
                        </a>
                    </div>
                    <div class="flex flex-grow justify-between items-center lg:w-3/4 xl:w-4/5 px-6">
                        {{-- TODO: nav toggler, collapsable menu --}}
                        @guest
                            <a class="" href="{{ route('login') }}">{{ __('auth.login') }}</a>
                            @if (Route::has('register'))
                                <a class="" href="{{ route('register') }}">{{ __('auth.register') }}</a>
                            @endif
                        @else
                            <a class="" href="{{ route('groups.index') }}">{{ __('groups.groups') }}</a>
                            {{-- TODO: click on user name to toggle pulldown w/ logout in it --}}
                            <p>{{ Auth::user()->name }}</p>
                            <div class="hidden">
                                <a class="" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                    {{ __('auth.logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <main class="mt-16">
            <div class="mx-auto container py-4">
                {{-- TODO: design an alert --}}
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
