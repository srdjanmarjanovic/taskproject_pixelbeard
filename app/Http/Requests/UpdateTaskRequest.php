<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required_without_all:description,completed|max:255',
            'description' => 'required_without_all:title,completed',
            'completed' => 'required_without_all:description,title',
        ];
    }
}
