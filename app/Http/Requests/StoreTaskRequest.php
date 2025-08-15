<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Task::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'min:1'],
            'task_list_id' => ['required', 'exists:task_lists,id'],
            'interval' => ['required', 'integer', 'min:1', 'max:365'],
            'optional' => ['sometimes', 'boolean'],
            'starts_at' => ['nullable', 'date', 'after_or_equal:today'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'days' => ['nullable', 'array'],
            'days.*' => ['string', Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])],
            'data' => ['nullable', 'array'],
            'data.custom_interval' => ['nullable', 'integer', 'min:1', 'max:100'],
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
            'title' => 'task title',
            'task_list_id' => 'task list',
            'starts_at' => 'start date',
            'ends_at' => 'end date',
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
            'title.required' => 'The task title is required.',
            'title.min' => 'The task title must not be empty.',
            'interval.required' => 'The task interval is required.',
            'interval.min' => 'The task must repeat at least every day.',
            'interval.max' => 'The task cannot repeat more than once per year.',
            'ends_at.after' => 'The end date must be after the start date.',
            'starts_at.after_or_equal' => 'The start date cannot be in the past.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (!$this->has('optional')) {
            $this->merge(['optional' => false]);
        }
    }
}
