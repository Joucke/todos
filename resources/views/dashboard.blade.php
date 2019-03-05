@extends('layouts.app')

@section('content')
    @if ($tabs->count())
        <tabbed-cards
            class="card-container"
            :tabs="{{ $tabs }}"
            :cards="{{ $tasks }}"
            >
        </tabbed-cards>
    @else
        <div class="card-container">
            <div class="card-padding">
                <a href="{{ route('groups.create') }}" class="card text-blue-dark no-underline">
                    <span class="card-body">
                        {{ __('groups.no_groups') }}
                    </span>
                </a>
            </div>
        </div>
    @endif
@endsection
