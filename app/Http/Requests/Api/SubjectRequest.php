<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class SubjectRequest extends FormRequest
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
            'title' => 'required|max:255',
            'c_v_id' => 'required|integer',
            'offset' => '',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tên không được phép để trống',
            'c_v_id.required' => 'Tên không được phép để trống',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([

            'status' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ], 400));
    }
}
