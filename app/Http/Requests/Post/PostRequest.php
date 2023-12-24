<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'sometimes|custom_image_validation',
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',

        ];
    }

    public function messages(): array
    {
        return [
            'image.custom_image_validation' => 'Must be an image file',
        ];
    }
}
