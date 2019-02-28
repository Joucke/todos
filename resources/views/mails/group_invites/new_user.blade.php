@component('mail::message')
# {{ __('groups.mails.new_user.title') }}

{{ __('groups.mails.new_user.message', ['group' => $group->title]) }}

@component('mail::button', ['url' => route('register')])
{{ __('groups.mails.new_user.call_to_action') }}
@endcomponent

@endcomponent
