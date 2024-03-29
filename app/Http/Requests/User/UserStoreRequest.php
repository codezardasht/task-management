<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
                'name' => 'required',
                'role' => ['required','exists:roles,id'],
                'email' =>['required',Rule::unique('users')->whereNull('deleted_at'),'email:rfc,dns'],
                'password' => 'required|string|min:8',
        ];
    }
}
