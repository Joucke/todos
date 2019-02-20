@extends('layouts.app')

@section('content')
<tabbed-cards
    class="card-container"
    :tabs="{{ $tabs }}"
    :cards="{{ $tasks }}"
    >
</tabbed-cards>
@endsection
