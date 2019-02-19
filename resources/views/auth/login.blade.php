@extends('layouts.app')

@section('content')
<div class="card-container">
    <div class="card-padding lg:w-1/2 mx-auto">
        <div class="card">
            <div class="card-header bg-grey-lighter border-b">
                <p class="font-semibold">{{ __('auth.login') }}</p>

                <div class="flex">
                    @if (Route::has('register'))
                        <a class="button button-blue button-secondary button-xs" href="{{ route('register') }}">
                            {{ __('auth.register') }}
                        </a>
                    @endif
                </div>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="card-body bg-white">
                    <div class="flex flex-col justify-between mb-4">
                        <label for="email" class="text-sm mb-2">{{ __('users.email') }}</label>

                        <div class="flex flex-col">
                            <input id="email"
                                type="email"
                                class="border rounded outline-none p-2{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                >

                            @if ($errors->has('email'))
                                <p class="text-red text-sm my-2" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col justify-between mb-4">
                        <label for="password" class="text-sm mb-2">{{ __('users.password') }}</label>

                        <div class="flex flex-col">
                            <input id="password"
                                type="password"
                                class="border rounded outline-none p-2{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                name="password"
                                required
                                >

                            @if ($errors->has('password'))
                                <p class="text-red text-sm my-2" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember"
                            class=""
                            type="checkbox"
                            name="remember"
                            {{ old('remember') ? 'checked' : '' }}
                            >

                        <label class="text-sm pl-2" for="remember">
                            {{ __('auth.remember') }}
                        </label>
                    </div>
                </div>

                <div class="flex text-sm">
                    <button type="submit" class="button button-blue rounded-t-none rounded-r-none w-1/2">
                        {{ __('auth.login') }}
                    </button>
                    @if (Route::has('password.request'))
                        <a class="button button-blue button-secondary rounded-t-none rounded-l-none w-1/2" href="{{ route('password.request') }}">
                            {{ __('auth.password') }}
                        </a>
                    @endif

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
