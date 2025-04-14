<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class CreatePostFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'topic' => 'required|string|max:50',
            'content' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id'
        ];
    }

    public function messages(): array
    {
        return [
            'topic.required' => 'El campo :attribute es obligatorio.',
            'topic.max' => 'El campo :attribute no debe superar los 50 caracteres.',
            'content.required' => 'El campo :attribute es obligatorio.',
            'content.max' => 'El campo :attribute no debe superar los 255 caracteres.',
            'user_id.required' => 'El campo :attribute es obligatorio.'
        ];
    }
}
