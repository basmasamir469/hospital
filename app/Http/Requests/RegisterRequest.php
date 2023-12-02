<?php

namespace App\Http\Requests;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'           => 'required',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'required|min:8|confirmed',
            'mobile'         => 'required|regex:/(01)[0-9]{9}/',
            'image'          => 'required|image',
            'department_id'  => $this->header('X-Role')=='doctor'? 'required':'nullable'

        ];
    }
}
