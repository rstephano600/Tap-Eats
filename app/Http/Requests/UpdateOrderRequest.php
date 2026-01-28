<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UpdateOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'delivery_address_text' => 'sometimes|string|max:500',
            'delivery_latitude' => 'sometimes|nullable|numeric|between:-90,90',
            'delivery_longitude' => 'sometimes|nullable|numeric|between:-180,180',
            'delivery_phone' => 'sometimes|string|max:20',
            'delivery_contact_name' => 'sometimes|string|max:100',
            'scheduled_at' => 'sometimes|nullable|date|after:now',
            'special_instructions' => 'sometimes|nullable|string|max:1000',
        ];
    }
}

