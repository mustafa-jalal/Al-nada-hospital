<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeNewVisitRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'national_number' => 'required|unique:patients',
            'gender' => 'required',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
        ];
    }
}
