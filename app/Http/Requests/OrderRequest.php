<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            "shipping_charge" => "nullable|numeric|min:0",
            "additional_discount" => "nullable|numeric|min:0",
            "additional_discount_percent" => "nullable",
            "status" => "required",
            "customer_id" => "required",
            "location_id" => "required",
            "order_details" => "nullable|array",
            "order_details.*.stock_id" => "nullable",
            "order_details.*.quantity" => "nullable|numeric|min:0",
        ];
    }
}
