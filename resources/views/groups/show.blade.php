@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
    <h1 class="page-header">{{ $group->title }}</h1>
    <div class="flex">
        @can ('update', $group)
            <a class="button button-blue button-secondary button-xs" href="{{ route('groups.edit', $group) }}">
                {{ __('groups.edit') }}
            </a>
        @endcan
        @can ('delete', $group)
            <form class="flex ml-2" action="{{ route('groups.destroy', $group) }}" method="POST">
                @csrf
                @method('DELETE')
                <input class="button button-red button-secondary button-xs" type="submit" value="{{ __('groups.delete') }}">
            </form>
        @endcan
    </div>
</header>

<div class="py-4">
    <div class="card-container">
        <div class="card-padding mb-4 lg:w-1/2">
            <div class="card">
                <div class="card-header bg-grey-lighter border-b">
                    <p class="font-semibold">{{ __('task_lists.task_lists') }}</p>
                </div>

                <div class="card-body bg-white">
                    <ul class="list-reset leading-normal">
                        @foreach ($group->task_lists as $list)
                            <li>
                                <a class="nav blue-light justify-between" href="{{ route('task_lists.show', ['task_list' => $list]) }}">
                                    <span>{{ $list->title }}</span>
                                    <span class="text-black">{{ trans_choice('tasks.count', $list->tasks->count()) }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-padding mb-4 lg:w-1/2">
            <div class="card">
                <div class="card-header bg-grey-lighter border-b">
                    <p class="font-semibold">{{ __('groups.members') }}</p>
                    <group-invite :group="{{ $group }}"></group-invite>
                </div>

                <div class="card-body bg-white">
                    <ul class="list-reset leading-normal">
                        @foreach ($group->users as $user)
                            <li class="flex items-center justify-between">
                                <a class="nav blue-light" href="{{ route('users.show', $user) }}">
                                    <span>{{ $user->name }}</span>
                                </a>
                                @can ('update', $group)
                                    @unless ($user->is(auth()->user()))
                                        <button class="button button-red button-secondary button-xs">Kick</button>
                                    @endunless
                                @endcan
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-padding mb-4 lg:w-1/2">
            <form action="{{ route('groups.task_lists.store', $group) }}" method="POST" class="card bg-white">
                @csrf

                <div class="card-header bg-grey-lighter border-b">
                    <p class="font-semibold">{{ __('task_lists.create') }}</p>
                </div>

                <div class="card-body flex flex-col">
                    <input
                        class="border rounded py-2 px-2"
                        placeholder="{{ __('task_lists.placeholders.title') }}"
                        type="text"
                        name="title"
                        value="{{ old('title') }}">

                    @if ($errors->has('title'))
                        <p class="text-red text-sm my-2" role="alert">
                            <strong>{{ $errors->first('title') }}</strong>
                        </p>
                    @endif
                </div>

                <div class="card-footer">
                    <input class="button button-blue w-full rounded-t-none" type="submit" value="{{ __('task_lists.add') }}">
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
