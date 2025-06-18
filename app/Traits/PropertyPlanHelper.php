<?php

namespace App\Traits;

trait PropertyPlanHelper
{
    /**
     * Check if the property has a Pro/Premium plan
     * Pro/Premium plan IDs: 3, 4, 6, 7
     *
     * @param int|null $planId
     * @return bool
     */
    protected function hasProOrPremiumPlan($planId = null)
    {
        // Premium plan IDs: 3, 4, 6, 7
        $premiumPlanIds = [3, 4, 6, 7];

        return in_array($planId, $premiumPlanIds);
    }

    /**
     * Get active premium plan for the property
     *
     * @param string|null $businessEmail
     * @return bool
     */
    protected function getActivePremiumPlan($businessEmail)
    {
        if (!$businessEmail) {
            return false;
        }

        // Normalize the email
        $normalizedEmail = strtolower(trim($businessEmail));

        // Find payments for this email
        $payments = \App\Models\Payment::whereRaw('LOWER(TRIM(business_email)) = ?', [$normalizedEmail])
            ->orderBy('created_at', 'desc')
            ->get();

        // Check if any payment has a premium plan
        foreach ($payments as $payment) {
            if ($this->hasProOrPremiumPlan($payment->plan_id)) {
                // Return the plan
                return \App\Models\Plan::find($payment->plan_id);
            }
        }

        return false;
    }
}
