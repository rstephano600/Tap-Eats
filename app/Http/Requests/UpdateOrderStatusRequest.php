<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'order_status' => [
                'required',
                Rule::in([
                    'pending',
                    'accepted',
                    'preparing',
                    'ready',
                    'dispatched',
                    'delivered',
                    'cancelled',
                    'rejected',
                    'failed'
                ])
            ],
            'cancellation_reason' => 'required_if:order_status,cancelled|string|max:500',
            'rejection_reason' => 'required_if:order_status,rejected|string|max:500',
            'delivery_notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'order_status.required' => 'Order status is required',
            'order_status.in' => 'Invalid order status',
            'cancellation_reason.required_if' => 'Cancellation reason is required',
            'rejection_reason.required_if' => 'Rejection reason is required',
        ];
    }
}


// ==========================================
// Sample Usage Examples
// ==========================================

/*
// Create Order API Call
POST /api/orders
{
    "supplier_id": 1,
    "service_type_id": 1,
    "order_type": "instant",
    "payment_method": "cash",
    "delivery_address_text": "123 Main St, Dar es Salaam",
    "delivery_latitude": -6.7924,
    "delivery_longitude": 39.2083,
    "delivery_phone": "+255123456789",
    "delivery_contact_name": "John Doe",
    "special_instructions": "Please ring the doorbell",
    "items": [
        {
            "menu_item_id": 1,
            "quantity": 2,
            "variant_id": 1,
            "selected_addons": [
                {
                    "id": 1,
                    "name": "Extra Cheese",
                    "price": 2.50,
                    "quantity": 1
                }
            ],
            "special_instructions": "Extra spicy"
        },
        {
            "menu_item_id": 2,
            "quantity": 1
        }
    ]
}

// Get Orders (with filters)
GET /api/orders?customer_id=1&order_status=pending&per_page=20

// Get Single Order
GET /api/orders/1

// Update Order
PUT /api/orders/1
{
    "delivery_phone": "+255987654321",
    "special_instructions": "Updated instructions"
}

// Update Order Status
PATCH /api/orders/1/status
{
    "order_status": "accepted"
}

// Cancel Order
PATCH /api/orders/1/status
{
    "order_status": "cancelled",
    "cancellation_reason": "Customer requested cancellation"
}

// Update Payment Status
PATCH /api/orders/1/payment-status
{
    "payment_status": "paid",
    "payment_reference": "TXN123456789"
}

// Get Order Statistics
GET /api/orders/statistics?from_date=2024-01-01&to_date=2024-12-31&supplier_id=1

// Delete Order
DELETE /api/orders/1
*/