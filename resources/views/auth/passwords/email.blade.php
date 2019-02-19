@extends('layouts.app')

@section('content')
<div class="card-container">
    <div class="card-padding lg:w-1/2 mx-auto">
        <div class="card">
            <div class="card-header bg-grey-lighter border-b">
                <p class="font-semibold">{{ __('auth.reset_password') }}</p>

                <div class="flex">
                    <a class="button button-blue button-secondary button-xs" href="{{ route('login') }}">
                        {{ __('auth.login') }}
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="card-body bg-white">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="flex flex-col justify-between">
                        <label for="email" class="text-sm mb-2">{{ __('users.email') }}</label>

                        <div class="flex flex-col">
                            <input id="email" type="email" class="border rounded outline-none p-2{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <p class="text-red text-sm my-2" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex text-sm">
                    <button type="submit" class="button button-blue rounded-t-none w-full">
                        {{ __('auth.send_link') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
