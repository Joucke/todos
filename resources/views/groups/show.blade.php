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
                {{ csrf_field() }}
                {{ method_field('DELETE') }}
                <input class="button button-red button-secondary button-xs" type="submit" value="{{ __('groups.delete') }}">
            </form>
        @endcan
    </div>
</header>
<div class="py-4">
    <div class="card-container">
        <div class="card-padding mb-4">
            <div class="card">
                <div class="card-header bg-grey-lighter border-b">
                    <p class="font-semibold">{{ __('task_lists.task_lists') }}</p>
                    <a class="button button-blue button-secondary button-xs" href="{{ route('groups.task_lists.create', $group) }}">{{ __('task_lists.create') }}</a>
                </div>

                <div class="card-body bg-white">
                    <ul class="list-reset leading-normal">
                        @foreach ($group->task_lists as $list)
                            <li>
                                <a href="{{ route('task_lists.show', ['task_list' => $list]) }}">{{ $list->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="card-padding mb-4">
            <div class="card">
                <div class="card-header bg-grey-lighter border-b">
                    <p class="font-semibold">{{ __('groups.members') }}</p>
                    <a class="button button-blue button-secondary button-xs" href="#">{{ __('groups.invite') }}</a>
                </div>

                <div class="card-body bg-white">
                    <ul class="list-reset leading-normal">
                        @foreach ($group->users as $user)
                            <li>
                                <a href="#{{-- TODO: route('users.show', $user) --}}">{{ $user->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
