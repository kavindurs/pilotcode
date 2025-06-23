<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BusinessClaimApproved;
use App\Mail\BusinessClaimRejected;
use App\Models\BusinessClaim;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BusinessClaimController extends Controller
{
    /**
     * Display a listing of business claim invitations
     */
    public function index()
    {
        $claims = BusinessClaim::with(['property', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.business-claims.index', compact('claims'));
    }

    /**
     * Show the details of a specific business claim
     */
    public function show(BusinessClaim $businessClaim)
    {
        $businessClaim->load(['property', 'reviewer']);

        return view('admin.business-claims.show', compact('businessClaim'));
    }

    /**
     * Approve a business claim invitation
     */
    public function approve(Request $request, BusinessClaim $businessClaim)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Update the business claim status
            $businessClaim->update([
                'status' => 'approved',
                'admin_notes' => $request->admin_notes,
                'reviewed_at' => now(),
                // 'reviewed_by' => null // Temporarily disable due to foreign key constraint
            ]);

            // Update the property with the claim information
            $property = $businessClaim->property;
            if ($property) {
                // Generate a random password for the property
                $randomPassword = Str::random(12); // Generate 12-character random password

                $property->update([
                    'business_name' => $businessClaim->business_name,
                    'business_email' => $businessClaim->business_email,
                    'property_type' => $businessClaim->property_type,
                    'first_name' => $businessClaim->first_name,
                    'last_name' => $businessClaim->last_name,
                    'zip_code' => $businessClaim->zip_code,
                    'country' => $businessClaim->country,
                    'annual_revenue' => $businessClaim->annual_revenue,
                    'employee_count' => $businessClaim->employee_count,
                    'category' => $businessClaim->category_id,
                    'subcategory' => $businessClaim->subcategory_id,
                    'domain' => $businessClaim->domain,
                    'password' => Hash::make($randomPassword), // Hash the password before storing
                    'status' => 'Approved'
                ]);

                // Prepare login credentials for email
                $loginCredentials = [
                    'email' => $businessClaim->business_email,
                    'password' => $randomPassword // Send plain password in email
                ];

                // Send approval email with login credentials
                try {
                    Mail::to($businessClaim->business_email)->send(
                        new BusinessClaimApproved($businessClaim, $property, $loginCredentials)
                    );
                } catch (\Exception $emailError) {
                    // Log email error but don't fail the approval process
                    \Log::error('Failed to send business claim approval email: ' . $emailError->getMessage());
                }

                // Handle business document if present
                if ($businessClaim->business_document) {
                    // Copy the document to property's documents if needed
                    // This preserves the document in the business_claims context
                }
            }

            DB::commit();

            return redirect()->route('admin.business-claims.index')
                ->with('success', 'Business claim approved successfully! Property updated and login credentials sent to ' . $businessClaim->business_email);

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to approve business claim: ' . $e->getMessage());
        }
    }

    /**
     * Reject a business claim invitation
     */
    public function reject(Request $request, BusinessClaim $businessClaim)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000'
        ]);

        try {
            $businessClaim->update([
                'status' => 'rejected',
                'admin_notes' => $request->admin_notes,
                'reviewed_at' => now(),
                // 'reviewed_by' => null // Temporarily disable due to foreign key constraint
            ]);

            // Send rejection email with admin feedback
            try {
                Mail::to($businessClaim->business_email)->send(
                    new BusinessClaimRejected($businessClaim, $request->admin_notes)
                );
            } catch (\Exception $emailError) {
                // Log email error but don't fail the rejection process
                \Log::error('Failed to send business claim rejection email: ' . $emailError->getMessage());
            }

            return redirect()->route('admin.business-claims.index')
                ->with('success', 'Business claim rejected successfully. Notification email sent to ' . $businessClaim->business_email);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to reject business claim: ' . $e->getMessage());
        }
    }

    /**
     * Claim a business directly (separate from approve workflow)
     */
    public function claim(Request $request, BusinessClaim $businessClaim)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Update the business claim status to claimed
            $businessClaim->update([
                'status' => 'claimed',
                'admin_notes' => $request->admin_notes,
                'reviewed_at' => now(),
                // 'reviewed_by' => null // Temporarily disable due to foreign key constraint
            ]);

            // Update the property with the claim information
            $property = $businessClaim->property;
            if ($property) {
                $property->update([
                    'business_name' => $businessClaim->business_name,
                    'business_email' => $businessClaim->business_email,
                    'property_type' => $businessClaim->property_type,
                    'first_name' => $businessClaim->first_name,
                    'last_name' => $businessClaim->last_name,
                    'zip_code' => $businessClaim->zip_code,
                    'country' => $businessClaim->country,
                    'annual_revenue' => $businessClaim->annual_revenue,
                    'employee_count' => $businessClaim->employee_count,
                    'category' => $businessClaim->category_id,
                    'subcategory' => $businessClaim->subcategory_id,
                    'domain' => $businessClaim->domain,
                    'status' => 'Approved'
                ]);
            }

            DB::commit();

            return redirect()->route('admin.business-claims.index')
                ->with('success', 'Business claimed successfully! Property has been updated with claim information.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Failed to claim business: ' . $e->getMessage());
        }
    }

    /**
     * Delete a business claim invitation
     */
    public function destroy(BusinessClaim $businessClaim)
    {
        try {
            // Delete associated document if exists
            if ($businessClaim->business_document && Storage::exists($businessClaim->business_document)) {
                Storage::delete($businessClaim->business_document);
            }

            $businessClaim->delete();

            return redirect()->route('admin.business-claims.index')
                ->with('success', 'Business claim deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete business claim: ' . $e->getMessage());
        }
    }
}
