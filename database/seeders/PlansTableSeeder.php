<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlansTableSeeder extends Seeder
{
    public function run(): void
    {
        Plan::create([
            'name' => 'Free',
            'price' => 0,
            'product_limit' => 0,
            'widget_limit' => 0,
            'html_integration_limit' => 100,
            'review_invitation_limit' => 0,
            'referral_code' => false,
        ]);

        Plan::create([
            'name' => 'Basic',
            'price' => 6,
            'product_limit' => 2,
            'widget_limit' => 2,
            'html_integration_limit' => 250,
            'review_invitation_limit' => 30,
            'referral_code' => false,
        ]);

        Plan::create([
            'name' => 'Pro',
            'price' => 9,
            'product_limit' => 5,
            'widget_limit' => 5,
            'html_integration_limit' => 500,
            'review_invitation_limit' => 75,
            'referral_code' => true,
        ]);

        Plan::create([
            'name' => 'Premium',
            'price' => 15,
            'product_limit' => 10,
            'widget_limit' => 8,
            'html_integration_limit' => 1000,
            'review_invitation_limit' => 200,
            'referral_code' => true,
        ]);
    }
}
