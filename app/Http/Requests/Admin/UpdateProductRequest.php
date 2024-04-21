<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            "id" => "required",
            "name_ar" => "required|unique:products,name_ar," . $this->id,
            "name_en" => "required|unique:products,name_en," . $this->id,
            "short_description" => "nullable",
            "description" => "nullable",
            "media_manager_ids" => "nullable|array",
            "media_manager_id" => "nullable",
            "categories_ids" => "nullable|array",
            "tags_ids" => "nullable|array",
            "brand_id" => "nullable",
            "unit_id" => "nullable",
            "from_date" => "nullable",
            "to_date" => "nullable",
            "discount" => "nullable",
            "percent" => "nullable",
            "taxes" => "nullable|array",
            "taxes.*.tax_id" => "nullable",
            "taxes.*.value" => "nullable",
            "taxes.*.percent" => "nullable",
            "sell_target" => "nullable",
            "published" => "nullable",
            "has_variations" => "nullable",
            //Variations ids
            "variation_ids" => "nullable|array",
            "variation_ids.*.variation_id" => "nullable",
            "variation_ids.*.variation_values_ids" => "nullable|array", //eg:[1,2,3]
            //Variations
            "variations" => "nullable|array",
            "variations.*.variation_name_ar" => "nullable",
            "variations.*.variation_name_en" => "nullable",
            "variations.*.price" => "nullable",
            "variations.*.stock" => "nullable",
            "variations.*.sku" => "nullable",
            "variations.*.code" => "nullable",

        ];
    }
}
