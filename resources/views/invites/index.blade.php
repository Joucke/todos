@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
    <h1 class="page-header">{{ __('invitations.invitations') }}</h1>
</header>

<div class="py-4">
    <div class="card-container">
        @forelse ($invites as $invite)
            <action-card
                title="{{ $invite->group->title }}"
                body="{{ $invite->email }}"
                created_at="{{ $invite->created_at }}"
                :action="null"
                :cancel="{
                    label: '{{ __('invitations.cancel') }}',
                    url: '{{ route('invites.destroy', $invite) }}',
                    method: 'DELETE'
                }"
                >
            </action-card>
        @empty
            No invites yet.
        @endforelse
    </div>
</div>
@endsection
