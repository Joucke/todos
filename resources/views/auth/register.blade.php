@extends('layouts.app')

@section('content')
<div class="card-container">
    <div class="card-padding lg:w-1/2 mx-auto">
        <div class="card">
            <div class="card-header bg-grey-lighter border-b">
                <p class="font-semibold">{{ __('auth.register') }}</p>

                <div class="flex">
                    <a class="button button-blue button-secondary button-xs" href="{{ route('login') }}">
                        {{ __('auth.login') }}
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="card-body bg-white">
                    <div class="flex flex-col justify-between mb-4">
                        <label for="name" class="text-sm mb-2">{{ __('users.name') }}</label>

                        <div class="flex flex-col">
                            <input id="name" type="text" class="border rounded outline-none p-2{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                            @if ($errors->has('name'))
                                <p class="text-red text-sm my-2" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col justify-between mb-4">
                        <label for="email" class="text-sm mb-2">{{ __('users.email') }}</label>

                        <div class="flex flex-col">
                            <input id="email" type="email" class="border rounded outline-none p-2{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

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
                            <input id="password" type="password" class="border rounded outline-none p-2{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            @if ($errors->has('password'))
                                <p class="text-red text-sm my-2" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-col justify-between">
                        <label for="password-confirm" class="text-sm mb-2">{{ __('users.password_confirm') }}</label>

                        <div class="flex flex-col">
                            <input id="password-confirm" type="password" class="border rounded outline-none p-2" name="password_confirmation" required>
                        </div>
                    </div>

                </div>

                <div class="flex text-sm">
                        <button type="submit" class="button button-blue rounded-t-none w-full">
                            {{ __('auth.register') }}
                        </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
