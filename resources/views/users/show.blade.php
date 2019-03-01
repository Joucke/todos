@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
    <h1 class="page-header">{{ $user->name }}</h1>
    <div class="flex">
        @can ('update', $user)
            <a class="button button-blue button-secondary button-xs" href="{{ route('users.edit', $user) }}">
                {{ __('users.edit') }}
            </a>
        @endcan
    </div>
</header>

<div class="py-4">
    <div class="card-container">

        <div class="card-padding mb-4 lg:w-1/2">
            <div class="card">
                <div class="card-header bg-grey-lighter border-b">
                    <p class="font-semibold">{{ __('users.data') }}</p>
                </div>

                <div class="card-body bg-white">
                    {{ $user->email }}
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
