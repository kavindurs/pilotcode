<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function index()
    {
        // Ensure the property owner is logged in using the session value
        if (!session('property_id')) {
            return redirect()->route('property.login')
                   ->with('error', 'Please login to access pricing plans.');
        }

        $plans = Plan::all();

        // Initialize an empty array for activated plan IDs.
        $activatedPlanIds = [];

        // Check if the property account is logged in via session.
        if (session('property_id')) {
            $propertyId = session('property_id');
            $property = Property::find($propertyId);
            if ($property) {
                $propertyEmail = $property->business_email ? strtolower(trim($property->business_email)) : null;

                if ($propertyEmail) {
                    // Get only the active (confirmed/success) payment for this property
                    $activePayment = Payment::getActivePlanForCustomer($propertyEmail, $propertyId);

                    // If there's an active payment, add its plan ID to activated plans
                    if ($activePayment && $activePayment->plan) {
                        $activatedPlanIds[] = $activePayment->plan->id;
                    }
                }
            }
        }

        return view('plans.index', compact('plans', 'activatedPlanIds'));
    }

    public function select(Request $request)
    {
        // Check if property owner is logged in using session
        if (!session('property_id')) {
            return redirect()->route('property.login')
                   ->with('error', 'Please login to select a plan.');
        }

        $propertyId = session('property_id');
        $property = Property::find($propertyId);

        if (!$property) {
            return redirect()->route('property.login')
                   ->with('error', 'Property not found. Please login again.');
        }

        $plan = Plan::findOrFail($request->plan_id);

        // For now, we'll redirect to the payment checkout instead of directly updating
        // because plan selection should go through the payment process
        return redirect()->route('payment.checkout.show', [
            'plan_id' => $plan->id,
            'amount' => $plan->price
        ])->with('success', 'Please complete the payment to activate your ' . $plan->name . ' plan.');
    }

    // New method to fetch activated plans based on the logged in property's business_email.
    public function activated()
    {
        // Ensure the property owner is logged in using the session value
        if (!session('property_id')) {
            return redirect()->route('property.login')
                   ->with('error', 'Please login to access activated plans.');
        }

        // Retrieve the property record from the session property_id
        $propertyId = session('property_id');
        $property = \App\Models\Property::find($propertyId);
        if (!$property) {
            abort(404, 'Property not found.');
        }

        // Get the business_email from the property record and normalize it.
        $propertyEmail = $property->business_email ? strtolower(trim($property->business_email)) : null;
        $activatedPlans = [];

        if ($propertyEmail) {
            // Get only the active (confirmed/success) payment for this property
            $activePayment = \App\Models\Payment::getActivePlanForCustomer($propertyEmail, $propertyId);

            // If there's an active payment, add its plan to activated plans
            if ($activePayment && $activePayment->plan) {
                $activatedPlans[] = $activePayment->plan;
            }
        }

        return view('plans.activated', compact('activatedPlans'));
    }
}
