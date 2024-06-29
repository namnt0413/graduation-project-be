<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ApplyRequest extends FormRequest
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
            'user_id'  => 'required',
            'job_id'      => 'required',
            'file_url'      => 'nullable|string',
            'date'     => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required'        => 'Vui lòng đăng nhập trước khi ứng tuyển',
            'job_id.required'        => 'Công việc ứng tuyển không được phép để trống',
            'file_url.string'        => 'Link khong hop le',
            'deadline.required'     => 'Deadline ứng tuyển không được phép để trống',
            'deadline.date'         => 'Tên không được phép để trống',
            'deadline.after:today'  => 'Deadline ứng tuyển không được kết thúc trước hôm nay',
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
