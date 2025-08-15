<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvitationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $group = $this->route('group');
        return $this->user()->can('invite', $group);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $groupId = $this->route('group')?->id;

        return [
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                // Prevent duplicate pending invitations for the same group
                Rule::unique('invitations')->where(function ($query) use ($groupId) {
                    return $query->where('group_id', $groupId)
                                 ->whereNull('accepted');
                }),
                // Prevent inviting existing group members
                Rule::notIn($this->getExistingGroupMemberEmails()),
            ],
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'email' => 'email address',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'An email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This person has already been invited to this group.',
            'email.not_in' => 'This person is already a member of this group.',
        ];
    }

    /**
     * Get existing group member emails to prevent duplicate invitations.
     */
    protected function getExistingGroupMemberEmails(): array
    {
        $group = $this->route('group');

        if (!$group) {
            return [];
        }

        return $group->members->pluck('email')->toArray();
    }
}
