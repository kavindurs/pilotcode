<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_type',
        'business_name',
        'city',
        'country',
        'zip_code',
        'annual_revenue',
        'employee_count',
        'category',
        'subcategory',
        'domain',
        'business_email',
        'document_path',
        'first_name',
        'last_name',
        'password',
        'status',
        'plan_id',
        'profile_picture',
        'referred_by',
        'notification_email',
        'language',
        'timezone',
        'contact_phone',
        'office_address',
        'website_url',
        'twitter',
        'facebook',
        'linkedin',
        'business_description',
    ];

    protected $hidden = [
        'password',
    ];

    public function approve()
    {
        $this->update(['status' => 'Approved']);
    }

    public function reject()
    {
        $this->update(['status' => 'Rejected']);
    }

    public function isApproved()
    {
        return $this->status === 'Approved';
    }

    public function isPending()
    {
        return $this->status === 'Not Approved';
    }

    /**
     * Get the ratings for the property.
     */
    public function rates()
    {
        return $this->hasMany(Rate::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category', 'id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory', 'id');
    }

    // Add accessor methods to get category and subcategory names
    // Note: These accessors are commented out to avoid issues when relationships aren't loaded
    // Category and subcategory names are handled in the controller instead

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the products for the property.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the widgets for the property.
     */
    public function widgets()
    {
        return $this->hasMany(Widget::class);
    }

    /**
     * Get the active payment for this property
     */
    public function activePayment()
    {
        return $this->hasOne(Payment::class)
            ->whereIn('status', ['confirmed', 'CONFIRMED', 'success'])
            ->latest();
    }

    /**
     * Get the active plan for this property
     */
    public function getActivePlan()
    {
        return Payment::getActivePlanForCustomer($this->business_email, $this->id);
    }

    /**
     * Check if property has an active plan
     */
    public function hasActivePlan()
    {
        return Payment::hasActivePlan($this->business_email, $this->id);
    }

    /**
     * Get the maximum number of products allowed for this property based on its plan.
     *
     * @return int
     */
    public function getProductLimit()
    {
        $plan = $this->getActivePlan();

        if (!$plan) {
            return 0;
        }

        switch ($plan->name) {
            case 'Free':
                return 0; // No products allowed
            case 'Basic':
                return 2; // 2 products allowed
            case 'Pro':
                return 5; // 5 products allowed
            case 'Premium':
                return 10; // 10 products allowed
            default:
                return 0;
        }
    }

    /**
     * Check if the property can add more products based on its plan limit.
     *
     * @return bool
     */
    public function canAddMoreProducts()
    {
        $currentProductCount = $this->products()->count();
        $limit = $this->getProductLimit();

        return $currentProductCount < $limit;
    }

    /**
     * Check if the property can use HTML integrations based on its plan.
     *
     * @return bool
     */
    public function canUseHtmlIntegrations()
    {
        // All plans can use HTML integrations with their respective character limits
        return true;
    }

    /**
     * Get the HTML character limit based on the active plan.
     *
     * @return int
     */
    public function getHtmlCharacterLimit()
    {
        $plan = $this->getActivePlan();

        if (!$plan) {
            return 0;
        }

        switch ($plan->name) {
            case 'Free':
                return 100; // 100 characters for Free plan
            case 'Basic':
                return 250; // 250 characters for Basic plan
            case 'Pro':
                return 500; // 500 characters for Pro plan
            case 'Premium':
                return 1000; // 1000 characters for Premium plan
            default:
                return 100; // Default to Free plan limit
        }
    }

    /**
     * Get the currently active HTML integration.
     *
     * @return \App\Models\HtmlIntegration|null
     */
    public function getActiveHtmlIntegration()
    {
        return HtmlIntegration::where('property_id', $this->id)
                        ->where('is_active', true)
                        ->first();
    }

    /**
     * Get the HTML content for a specific placement.
     *
     * @param string $placement
     * @return string|null
     */
    public function getHtmlContentForPlacement($placement)
    {
        $integration = HtmlIntegration::where('property_id', $this->id)
                            ->where('is_active', true)
                            ->where('placement', $placement)
                            ->first();

        return $integration ? $integration->html_content : null;
    }

    /**
     * Get the review invitations for the property.
     */
    public function reviewInvitations()
    {
        return $this->hasMany(ReviewInvitation::class);
    }

    /**
     * Get the review invitation limit based on the active plan.
     *
     * @return int
     */
    public function getReviewInvitationLimit()
    {
        $plan = $this->getActivePlan();

        if (!$plan) {
            return 0;
        }

        switch ($plan->name) {
            case 'Free':
                return 5; // 5 invitations per month
            case 'Basic':
                return 20; // 20 invitations per month
            case 'Pro':
                return 100; // 100 invitations per month
            case 'Premium':
                return 500; // 500 invitations per month
            default:
                return 5; // Default to Free plan limit
        }
    }

    /**
     * Get the number of review invitations sent this month.
     *
     * @return int
     */
    public function getMonthlyInvitationCount()
    {
        $startOfMonth = now()->startOfMonth();

        return $this->reviewInvitations()
                    ->where('created_at', '>=', $startOfMonth)
                    ->count();
    }

    /**
     * Check if the property can send more review invitations this month.
     *
     * @return bool
     */
    public function canSendMoreInvitations()
    {
        $currentCount = $this->getMonthlyInvitationCount();
        $limit = $this->getReviewInvitationLimit();

        return $currentCount < $limit;
    }

    /**
     * Get the monthly email invitation limit based on the active plan.
     *
     * @return int
     */
    public function getEmailInvitationLimit()
    {
        $plan = $this->getActivePlan();

        if (!$plan) {
            return 0; // Default to 0 if no plan is active
        }

        switch ($plan->name) {
            case 'Free':
                return 0; // Free plan: 0 emails per month
            case 'Basic':
                return 30; // Basic plan: 30 emails per month
            case 'Pro':
                return 75; // Pro plan: 75 emails per month
            case 'Premium':
                return 200; // Premium plan: 200 emails per month
            default:
                return 0; // Default to 0 for unknown plans
        }
    }

    /**
     * Get the number of review invitations sent this month.
     *
     * @return int
     */
    public function getEmailInvitationsSentThisMonth()
    {
        return ReviewInvitation::where('property_id', $this->id)
                   ->whereMonth('created_at', now()->month)
                   ->whereYear('created_at', now()->year)
                   ->count();
    }

    /**
     * Get the widget limit based on the active plan.
     *
     * @return int
     */
    public function getWidgetLimit()
    {
        $plan = $this->getActivePlan();

        if (!$plan) {
            return 0;
        }

        switch ($plan->name) {
            case 'Free':
                return 0; // No widgets allowed
            case 'Basic':
                return 2; // 2 widgets allowed
            case 'Pro':
                return 5; // 5 widgets allowed
            case 'Premium':
                return 8; // 8 widgets allowed
            default:
                return 0;
        }
    }

    /**
     * Check if the property can add more widgets based on its plan limit.
     *
     * @return bool
     */
    public function canAddMoreWidgets()
    {
        $currentWidgetCount = $this->widgets()->count();
        $limit = $this->getWidgetLimit();

        return $currentWidgetCount < $limit;
    }

    /**
     * Check if the property has a Pro or Premium plan
     *
     * @return bool
     */
    public function hasProOrPremiumPlan()
    {
        // If the property has a plan relationship
        if ($this->plan) {
            // Check if the plan name is Pro or Premium
            return in_array($this->plan->name, ['Pro', 'Premium']);
        }

        return false;
    }
}
