@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
    <h1 class="page-header">{{ __('groups.sort') }}</h1>
</header>

<div class="py-4">
    <sort-table
        :list-data="{{ $groups }}"
        :columns="['title']"
        sort-url="{{ route('groups.sort', ['user' => auth()->user()]) }}"
        sort-key="user_group_order"
        >
    </sort-table>
</div>

@endsection
