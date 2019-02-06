@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
    <h1 class="page-header">{{ __('groups.create') }}</h1>
</header>
<div class="py-4">
    <div class="card-container">
        <div class="card-padding mb-4">
            <form action="{{ route('groups.store') }}" method="POST" class="card bg-white">
                @csrf
                <div class="card-body flex flex-col">
                    <label class="mb-1" for="title">{{ __('groups.title') }}:</label>
                    <input
                        class="border rounded py-2 px-2"
                        placeholder="{{ __('groups.placeholders.title') }}"
                        type="text"
                        name="title"
                        value="{{ old('title') }}">
                </div>
                <div class="card-footer">
                    <input class="button button-blue w-full rounded-t-none" type="submit" value="{{ __('groups.create') }}">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
