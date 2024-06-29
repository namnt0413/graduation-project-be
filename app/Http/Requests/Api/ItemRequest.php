<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ItemRequest extends FormRequest
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
            'title' => '',
            'content' => 'required',
            'type' => '',
            'c_v_id' => 'required|integer',
            'subject_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'Tên không được phép để trống',
            'c_v_id.required' => 'Tên không được phép để trống',
            'subject_id.required' => 'Tên không được phép để trống',
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
