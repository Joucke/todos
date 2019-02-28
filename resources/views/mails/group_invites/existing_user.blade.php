@component('mail::message')
# {{ __('groups.mails.existing.title') }}

{{ __('groups.mails.existing.message', ['group' => $group->title]) }}

@component('mail::button', ['url' => route('invitations')])
{{ __('groups.mails.existing.call_to_action') }}
@endcomponent

@endcomponent
