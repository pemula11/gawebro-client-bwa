<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'name' => ['required', 'string', 'max:255'],
            'skill_level' => ['required', 'string', 'max:255', 'in:beginner,intermediate,advanced'],
            'thumbnail' => ['sometimes', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'budget' => ['required', 'integer', 'min:1'],
            'about' => ['required', 'string', 'max:65535'],
        ];
    }
}
