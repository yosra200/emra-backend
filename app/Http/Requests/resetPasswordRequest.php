<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class resetPasswordRequest extends FormRequest
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
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-zA-Z])(?=.*[0-9])/|confirmed',

        ];
    }

    public function messages(): array
    {
        return [
            'password.regex' => 'يجب أن تحتوي كلمة المرور على أحرف وأرقام على الأقل.',
        ];
    }
}
