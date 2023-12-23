<?php
use App\Models\Order;

class OrderService
{
    public function processOrder(array $data)
    {
        // Validate input or handle any necessary validation

        // Create a new order
        $order = Order::create([
            'merchant_id' => $data['merchant_id'],
            'affiliate_id' => $data['affiliate_id'],
            'subtotal' => $data['subtotal'],
            'commission_owed' => 0.00, // Default commission owed
            'payout_status' => Order::STATUS_UNPAID,
            'discount_code' => $data['discount_code']
        ]);

        // You might perform other actions related to order processing here

        return $order;
    }
}
