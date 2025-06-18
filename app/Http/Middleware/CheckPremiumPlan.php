<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Payment;

class CheckPremiumPlan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if property is logged in
        if (!session('property_id')) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to access your business dashboard.');
        }

        // Get the property
        $property = Property::find(session('property_id'));

        if (!$property) {
            return redirect()->route('property.login')->with('error', 'Property not found.');
        }

        // Premium plan IDs: 3, 4, 6, 7
        $premiumPlanIds = [3, 4, 6, 7];
        $hasProAccess = in_array($property->plan_id, $premiumPlanIds);

        // If plan_id is not premium, check if they have premium payment
        if (!$hasProAccess && $property->business_email) {
            $propertyEmail = strtolower(trim($property->business_email));

            // Get payments for this email
            $payments = Payment::whereRaw('LOWER(TRIM(business_email)) = ?', [$propertyEmail])->get();

            // Check if any payment has a premium plan
            foreach ($payments as $payment) {
                if (in_array($payment->plan_id, $premiumPlanIds)) {
                    $hasProAccess = true;
                    break;
                }
            }
        }

        if (!$hasProAccess) {
            return redirect()->route('property.review.analysis')
                ->with('error', 'This feature requires a Pro or Premium plan. Please upgrade to access it.');
        }

        return $next($request);
    }
}
