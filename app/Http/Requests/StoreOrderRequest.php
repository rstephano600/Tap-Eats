<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'service_type_id' => 'required|exists:service_types,id',
            'order_type' => 'required|in:instant,scheduled,catering,subscription',
            'payment_method' => 'required|in:cash,card,mobile_money,wallet',
            
            // Delivery information
            'delivery_address_id' => 'nullable|exists:customer_addresses,id',
            'delivery_address_text' => 'required|string|max:500',
            'delivery_latitude' => 'nullable|numeric|between:-90,90',
            'delivery_longitude' => 'nullable|numeric|between:-180,180',
            'delivery_phone' => 'required|string|max:20',
            'delivery_contact_name' => 'nullable|string|max:100',
            
            // Timing
            'scheduled_at' => 'nullable|date|after:now',
            'estimated_delivery_time' => 'nullable|integer|min:0',
            
            // Pricing
            'delivery_fee' => 'nullable|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'coupon_code' => 'nullable|string|max:50',
            
            // Instructions
            'special_instructions' => 'nullable|string|max:1000',
            
            // Order items
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1|max:100',
            'items.*.variant_id' => 'nullable|exists:menu_item_variants,id',
            'items.*.selected_addons' => 'nullable|array',
            'items.*.selected_addons.*.id' => 'required|integer',
            'items.*.selected_addons.*.name' => 'required|string',
            'items.*.selected_addons.*.price' => 'required|numeric|min:0',
            'items.*.selected_addons.*.quantity' => 'required|integer|min:1',
            'items.*.special_instructions' => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'supplier_id.required' => 'Please select a supplier',
            'supplier_id.exists' => 'The selected supplier does not exist',
            'service_type_id.required' => 'Please select a service type',
            'items.required' => 'Please add at least one item to your order',
            'items.min' => 'Please add at least one item to your order',
            'items.*.menu_item_id.required' => 'Menu item is required',
            'items.*.menu_item_id.exists' => 'The selected menu item does not exist',
            'items.*.quantity.required' => 'Quantity is required',
            'items.*.quantity.min' => 'Quantity must be at least 1',
            'delivery_address_text.required' => 'Delivery address is required',
            'delivery_phone.required' => 'Delivery phone is required',
        ];
    }

    protected function prepareForValidation()
    {
        // Clean phone number
        if ($this->has('delivery_phone')) {
            $phone = preg_replace('/[^0-9+]/', '', $this->delivery_phone);
            $this->merge(['delivery_phone' => $phone]);
        }
    }
}
