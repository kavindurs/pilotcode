<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class BusinessClaimEmailTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Business Claim Approved Email Template
        EmailTemplate::updateOrCreate(
            ['slug' => 'business-claim-approved'],
            [
                'subject' => 'Business Claim Approved - Login Details',
                'body' => 'Your business claim has been approved! Please check your email for login credentials.'
            ]
        );

        // Business Claim Rejected Email Template
        EmailTemplate::updateOrCreate(
            ['slug' => 'business-claim-rejected'],
            [
                'subject' => 'Business Claim - Update Required',
                'body' => 'Your business claim requires some updates. Please review the feedback and resubmit.'
            ]
        );
    }
}
