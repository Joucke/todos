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
                    <sortable-list
                        :data="{{ json_encode($group->task_lists) }}"
                        sort-url="{{ route('groups.task_lists.sort', ['group' => $group]) }}"
                        sort-key="task_list_order"
                        >
                        <div class="w-full flex flex-col" slot-scope="{ items }">
                            <sortable-item
                                v-for="item in items"
                                :key="item.id"
                                >
                                <div class="w-full flex justify-between items-center bg-white">
                                    <a
                                        class="nav blue-light justify-between"
                                        :href="'{{ route('task_lists.show', ['task_list' => '__list__']) }}'.replace('__list__', item.id)"
                                        v-text="item.title"
                                        >
                                    </a>
                                    <p class="flex items-center">
                                        <span v-text="t_count('tasks.count', item.tasks ? item.tasks.length : 0)"></span>
                                        <sortable-handle>
                                            <svg class="ml-4 w-6 h-6 cursor-move" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <path class="fill-current text-red" d="M7 18.59V9a1 1 0 0 1 2 0v9.59l2.3-2.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 1 1 1.4-1.42L7 18.6z"/>
                                                <path class="fill-current text-green" d="M17 5.41V15a1 1 0 1 1-2 0V5.41l-2.3 2.3a1 1 0 1 1-1.4-1.42l4-4a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1-1.4 1.42L17 5.4z"/>
                                            </svg>
                                        </sortable-handle>
                                    </p>
                                </div>
                            </sortable-item>
                        </div>
                    </sortable-list>
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
                                        <form action="{{ route('groups.users.destroy', compact('group', 'user')) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="button button-red button-secondary button-xs">
                                                {{ __('groups.remove_member') }}
                                            </button>
                                        </form>
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
