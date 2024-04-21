<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            "id"=>"required",
            "name_ar" => "required",
            "name_en" => "required",
            "media_manager_id" => "nullable",
            "base_category_id" => "nullable",
            "brand_ids" => "nullable|array",
            "priority" => "nullable"
        ];
    }
}
