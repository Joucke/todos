@extends('layouts.app')

@section('content')
<div class="card-container">
    <div class="card-padding mb-4 lg:w-1/2 mx-auto">
        <form action="{{ route('groups.update', $group) }}" method="POST" class="card bg-white">
            @csrf
            @method('PATCH')

            <div class="card-header bg-grey-lighter border-b">
                <p class="font-semibold">{{ __('groups.edit') }}</p>
            </div>

            <div class="card-body flex flex-col">
                <input
                    class="border rounded py-2 px-2"
                    placeholder="{{ __('groups.placeholders.title') }}"
                    type="text"
                    name="title"
                    value="{{ old('title', $group->title) }}">

                @if ($errors->has('title'))
                    <p class="text-red text-sm my-2" role="alert">
                        <strong>{{ $errors->first('title') }}</strong>
                    </p>
                @endif
            </div>

            <div class="card-footer">
                <input class="button button-blue w-full rounded-t-none" type="submit" value="{{ __('groups.update') }}">
            </div>
        </form>
    </div>
</div>
@endsection
