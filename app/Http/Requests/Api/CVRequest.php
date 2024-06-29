<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CVRequest extends FormRequest
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
            'user_id' => 'required|integer',
            'template_id' => 'required|integer',
            'offset' => '',
            'text_font' => '',
            'text_size' => '',
            'theme_color' => '',
            'name' => 'required',
            'position' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            'birthday' => 'required',
            'avatar' => '',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tên không được phép để trống',
            'user_id.required' => 'Tên không được phép để trống',
            'template_id.required' => 'Tên không được phép để trống',
            'name.required' => 'Tên không được phép để trống',
            'position.required' => 'Tên không được phép để trống',
            'phone.required' => 'Tên không được phép để trống',
            'email.required' => 'Tên không được phép để trống',
            'address.required' => 'Tên không được phép để trống',
            'birthday.required' => 'Tên không được phép để trống',
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
