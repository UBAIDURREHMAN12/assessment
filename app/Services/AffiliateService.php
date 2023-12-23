<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AffiliateService
{
    public function register(Merchant $merchant, string $email, string $name, string $password, float $commissionRate, string $discountCode): Affiliate
    {
        // Validation rules for the input data
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'commission_rate' => $commissionRate,
            'discount_code' => $discountCode,
        ];

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6', // Define password requirements as needed
            'commission_rate' => 'required|numeric|min:0',
            'discount_code' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            // Handle validation errors
            // throw an exception, return a response, or handle it in any suitable way
            // For example, throwing an exception:
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'type' => 'affiliate'
        ]);

        return Affiliate::create([
            'user_id' => $user->id,
            'merchant_id' => $merchant->id,
            'commission_rate' => $commissionRate,
            'discount_code' => $discountCode
        ]);
    }
}
