@extends('layouts.app')

@section('content')
<header class="flex justify-between items-center">
    <h1 class="page-header">{{ __('invitations.invitations') }}</h1>
</header>

<div class="py-4">
    <div class="card-container">
        @forelse ($invitations as $invite)
            <action-card
                title="{{ $invite->group->title }}"
                body="{{ $invite->email }}"
                created_at="{{ $invite->created_at }}"
                :action="{
                    label: '{{ __('invitations.accept') }}',
                    url: '{{ route('groups.invites.update', ['group' => $invite->group, 'invite' => $invite]) }}',
                    method: 'PATCH',
                    fields: [{
                        name: 'accepted',
                        value: 1,
                    }],
                }"
                :cancel="{
                    label: '{{ __('invitations.decline') }}',
                    url: '{{ route('groups.invites.update', ['group' => $invite->group, 'invite' => $invite]) }}',
                    method: 'PATCH',
                    fields: [{
                        name: 'accepted',
                        value: 0,
                    }],
                }"
                >
            </action-card>
        @empty
            No invites yet.
        @endforelse
    </div>
</div>
@endsection
