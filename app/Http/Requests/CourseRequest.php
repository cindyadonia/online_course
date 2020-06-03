<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CourseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    
    public function rules()
    {
        return [
            'name' => 'required',
            'lecturer_id' => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'First name is required',
            'lecturer_id.required' => 'Lecturer is required!',
        ];
    }
    
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
