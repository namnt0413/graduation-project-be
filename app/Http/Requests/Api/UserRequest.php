<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;
class UserRequest extends FormRequest
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
     * Get the validation rules that apply to the requaest.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id'  => 'required',
            // 'email' => ['email', 'string', Rule::unique('users')->ignore($this->user_id )],
        ];
    }

    public function messages()
    {
        return [
            'user_id.required'  => 'User is required',
            // 'email.unique'  => 'Email is unique',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([

            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ]));
    }
}
