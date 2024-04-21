<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            "stocks" => "required|array|min:1",
            "stocks .*.stock" => "required|min:0",
            "stocks.*.location_id" => "required",
            "stocks.*.product_id" => "required",
            "stocks.*.variation_name_ar" => "nullable",
            "stocks.*.variation_name_en" => "nullable"
        ];
    }
}
