<?php

namespace App\Http\Requests\Tasks;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class SaveTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
       return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:4',
            'description' => 'required|min:8',
            // 'priority' => 'nullable|integer|min:1|max:5',
            // 'status' => 'required|in:pending,in_progress,done',
            'due_date' => 'nullable|date|after_or_equal:today',
        ];
    }
}
