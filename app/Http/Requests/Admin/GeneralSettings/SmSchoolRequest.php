<?php

namespace App\Http\Requests\Admin\GeneralSettings;

use Illuminate\Foundation\Http\FormRequest;

class SmSchoolRequest extends FormRequest
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
            'school_name' => "required",
            'school_code' => "required",
            'phone' => "required",
            'email' => "required",
            'district_name'   => "required",
            'address' => "required",
            'program_id' => "required|array",
        ];
    }
}
