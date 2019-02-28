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
    <div id="app" class="min-h-full lg:flex">
        <nav class="flex border-b-2 border-blue-darker bg-blue-dark text-white fixed lg:relative lg:border-b-0 lg:h-auto lg:w-64 lg:flex-col lg:flex-no-shrink pin-t pin-x z-40 h-16 items-center px-2 sm:px-0">
            @include ('layouts.nav')
        </nav>

        <main class="pt-16 lg:pt-0 lg:flex lg:flex-grow lg:px-4">
            <div class="mx-auto container py-4 px-2 sm:px-0">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                @auth
                    @foreach ($invitations as $invitation)
                        <div class="alert alert-notify" role="alert">
                            <a href="{{ route('invitations') }}">
                                {{ __('groups.invited', ['group' => $invitation->group->title])}}
                            </a>
                        </div>
                    @endforeach
                @endauth
                @yield('content')
            </div>
        </main>
        <portal-target name="modals"></portal-target>
    </div>
</body>
</html>
