@extends('layouts.app')

@section('content')
    @if ($tabs->count())
        <tabbed-cards
            class="card-container"
            :tabs="{{ $tabs }}"
            :cards="{{ $tasks }}"
            >
        </tabbed-cards>
    @endif
@endsection
