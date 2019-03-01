@extends('layouts.app')

@section('content')
<div class="card-container">

    <div class="card-padding mb-4 lg:w-1/2 mx-auto">
        <form action="{{ route('users.update', $user) }}" method="POST" class="card bg-white">
            @csrf
            @method('PATCH')

            <div class="card-header bg-grey-lighter border-b">
                <p class="font-semibold">{{ __('users.data') }}</p>
            </div>

            <div class="card-body flex flex-col">
                <label class="uppercase text-xs tracking-wide px-1" for="name">
                    {{ __('users.name') }}
                </label>
                <input
                    class="border rounded py-2 px-2"
                    placeholder="{{ __('users.name') }}"
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}">

                @if ($errors->has('name'))
                    <p class="text-red text-sm my-2" role="alert">
                        <strong>{{ $errors->first('name') }}</strong>
                    </p>
                @endif

                <label class="uppercase text-xs tracking-wide px-1 mt-4" for="email">
                    {{ __('users.email') }}
                </label>
                <input
                    class="border rounded py-2 px-2"
                    placeholder="{{ __('users.email') }}"
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}">

                @if ($errors->has('email'))
                    <p class="text-red text-sm my-2" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </p>
                @endif
            </div>

            <div class="card-footer">
                <input class="button button-blue w-full rounded-t-none" type="submit" value="{{ __('users.update') }}">
            </div>
        </form>
    </div>

</div>
@endsection
