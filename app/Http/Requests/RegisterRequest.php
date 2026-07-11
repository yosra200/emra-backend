<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
   public function rules(): array
{
    return [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',

        'phone' => 'required|string|max:20|unique:users,phone',

        'second_phone' => 'nullable|string|max:20',

        'address' => 'nullable|string|max:255',

        'governorate' => 'nullable|string|max:255',

        'email' => 'required|email|unique:users,email',

        'password' => 'required|string|min:8|confirmed',
    ];
}
}
