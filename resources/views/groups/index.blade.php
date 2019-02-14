@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
    <h1 class="page-header">{{ __('groups.groups') }}</h1>
</header>
<div class="py-4 md:-mx-2">
    <ul class="list-reset md:px-2 card-container">
    	@foreach ($groups as $group)
    		<li class="card-padding mb-4">
                <div class="card">
                    <a class="card-body no-underline text-black bg-white" href="{{ route('groups.show', $group) }}">{{ $group->title }}</a>
                </div>
            </li>
    	@endforeach
    </ul>
</div>
<div>
    <a class="button button-blue button-secondary" href="{{ route('groups.create') }}">{{ __('groups.create') }}</a>
</div>
@endsection
