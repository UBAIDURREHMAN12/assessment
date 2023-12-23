<?php

use Illuminate\Support\Facades\Hash;

class MerchantService
{
    public function register(array $data): Merchant
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'type' => 'merchant', // Set the user type as 'merchant'
        ]);

        return Merchant::create([
            'user_id' => $user->id,
            'domain' => $data['domain'],
            'display_name' => $data['display_name'],
            'turn_customers_into_affiliates' => $data['turn_customers_into_affiliates'],
            'default_commission_rate' => $data['default_commission_rate']
        ]);
    }

    public function updateMerchant(User $user, array $data)
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'type' => 'merchant'
        ]);
    }

    public function findMerchantByEmail(string $email): ?Merchant
    {
        $user = User::where('email', $email)->first();

        return $user ? $user->merchant : null;
    }

    public function payout(Affiliate $affiliate)
    {
        $unpaidOrders = $affiliate->orders()->where('payout_status', false)->get();

        foreach ($unpaidOrders as $order) {
            dispatch(new PayoutOrderJob($order));
        }
    }
}
