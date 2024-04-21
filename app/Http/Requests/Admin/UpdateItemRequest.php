<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
            "name_ar" => "required|unique:items,name_ar," . request()->id,
            "name_en" => "required|unique:items,name_en," . request()->id,
            "image" => "nullable|image",
            "list" => "required|array|min:1",
            "list.*.name_ar" => "required",
            "list.*.name_en" => "required"
        ];
    }
}
