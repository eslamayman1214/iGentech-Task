<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $this->route('user')->id,
            'phone' => 'sometimes|string|unique:users,phone,' . $this->route('user')->id,
            'avatar' => 'nullable|image',
        ];
    }
}
