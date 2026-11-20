<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', 'string', Rule::in(Task::STATUSES)],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a task title.',
            'title.max' => 'The task title cannot exceed 255 characters.',
            'status.required' => 'Please select whether the task is Pending or Completed.',
            'status.in' => 'The status must be either Pending or Completed.',
        ];
    }
}
