<?php

namespace App\Http\Controllers;

use App\Models\Widget;
use App\Models\Property;
use App\Models\Subscription; // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WidgetController extends Controller
{
    /**
     * Get widget limit based on the active plan.
     *
     * @param \App\Models\Property $property
     * @return int
     */
    private function getWidgetLimit(Property $property)
    {
        $plan = $property->getActivePlan();

        if (!$plan) {
            return 0;
        }

        switch ($plan->plan->name) {
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
     * Check widget limit based on plan and return information
     *
     * @param int $propertyId
     * @return array
     */
    private function checkWidgetLimit($propertyId)
    {
        try {
            $property = Property::findOrFail($propertyId);
            $plan = $property->getActivePlan();

            // If no active plan, default to Free
            $planType = $plan ? $plan->plan->name : 'Free';

            // Get widget limit
            $widgetLimit = $this->getWidgetLimit($property);

            // Get current widget count
            $widgetCount = Widget::where('property_id', $propertyId)->count();

            // Calculate remaining and check if can add more
            $remainingWidgets = max(0, $widgetLimit - $widgetCount);
            $canAddWidget = $widgetCount < $widgetLimit;

            return [
                'planType' => $planType,
                'widgetLimit' => $widgetLimit,
                'widgetCount' => $widgetCount,
                'canAddWidget' => $canAddWidget,
                'remainingWidgets' => $remainingWidgets
            ];
        } catch (\Exception $e) {
            Log::error('Error checking widget limit: ' . $e->getMessage());
            return [
                'planType' => 'Unknown',
                'widgetLimit' => 0,
                'widgetCount' => 0,
                'canAddWidget' => false,
                'remainingWidgets' => 0
            ];
        }
    }

    /**
     * Display a listing of the widgets.
     */
    public function index()
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to access widgets.');
        }

        try {
            $property = Property::findOrFail($propertyId);
            $widgets = Widget::where('property_id', $propertyId)
                        ->orderBy('order')
                        ->get();

            // Get plan information and limits
            $planInfo = $this->checkWidgetLimit($propertyId);

            return view('property.widgets.index', compact('property', 'widgets', 'planInfo'));
        } catch (\Exception $e) {
            Log::error('Error in widget index: ' . $e->getMessage());
            return redirect()->route('property.login')
                ->with('error', 'Error loading widgets. Please try again.');
        }
    }

    /**
     * Show the form for creating a new widget.
     */
    public function create()
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to create widgets.');
        }

        try {
            $property = Property::findOrFail($propertyId);

            // Check widget limit based on plan
            $planInfo = $this->checkWidgetLimit($propertyId);

            // If limit reached, redirect back with error
            if (!$planInfo['canAddWidget']) {
                return redirect()->route('property.widgets')
                    ->with('error', "You've reached the widget limit for your {$planInfo['planType']} plan. Upgrade your plan to add more widgets.");
            }

            return view('property.widgets.create', compact('property', 'planInfo'));
        } catch (\Exception $e) {
            Log::error('Error in widget create: ' . $e->getMessage());
            return redirect()->route('property.login')
                ->with('error', 'Error loading widget creation page.');
        }
    }

    /**
     * Store a newly created widget in storage.
     */
    public function store(Request $request)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to create widgets.');
        }

        // Check widget limit based on plan
        $planInfo = $this->checkWidgetLimit($propertyId);

        // If limit reached, redirect back with error
        if (!$planInfo['canAddWidget']) {
            return redirect()->route('property.widgets')
                ->with('error', "You've reached the widget limit for your {$planInfo['planType']} plan. Upgrade your plan to add more widgets.");
        }

        // Rest of your store method continues unchanged...
        // Log entire request for debugging
        Log::info('Widget store request', [
            'all_data' => $request->all(),
            'has_file' => $request->hasFile('image'),
            'files' => $request->allFiles()
        ]);

        try {
            // Create new widget
            $widget = new Widget();
            $widget->property_id = $propertyId;
            $widget->widget_type = $request->widget_type;
            $widget->title = $request->title;
            $widget->is_active = $request->has('is_active');

            // Set order (use max+1 if not provided)
            if ($request->filled('order')) {
                $widget->order = $request->order;
            } else {
                $maxOrder = Widget::where('property_id', $propertyId)->max('order');
                $widget->order = $maxOrder ? $maxOrder + 1 : 1;
            }

            // Process based on widget type
            switch ($request->widget_type) {
                case 'badge':
                    // Validate badge requirements
                    $request->validate([
                        'image' => 'required|image|max:2048',
                    ]);

                    if ($request->hasFile('image')) {
                        $widget->image_path = $request->file('image')->store('widgets', 'public');
                    }
                    break;

                case 'instagram':
                case 'linkedin':
                case 'youtube':
                case 'facebook':
                    // Validate social media requirements
                    $request->validate([
                        'link_url' => 'required|string|max:255',
                    ]);

                    $widget->link_url = $request->link_url;
                    break;

                case 'cover_image':
                    // Validate cover image requirements
                    $request->validate([
                        'image' => 'required|image|max:2048',
                    ]);

                    if ($request->hasFile('image')) {
                        $widget->image_path = $request->file('image')->store('widgets', 'public');
                    }
                    break;

                case 'text':
                    // Validate text requirements
                    $request->validate([
                        'content' => 'required|string',
                    ]);

                    $widget->content = $request->content;
                    break;

                case 'faq':
                    // Validate FAQ requirements
                    $request->validate([
                        'faq_content' => 'required|string',
                    ]);

                    // Process FAQ content
                    $faqData = json_decode($request->faq_content, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return redirect()->back()
                            ->with('error', 'Invalid FAQ data format: ' . json_last_error_msg())
                            ->withInput();
                    }

                    if (!is_array($faqData) || count($faqData) === 0) {
                        return redirect()->back()
                            ->with('error', 'FAQ must contain at least one question and answer.')
                            ->withInput();
                    }

                    $widget->content = $request->faq_content;
                    break;

                default:
                    return redirect()->back()
                        ->with('error', 'Invalid widget type selected.')
                        ->withInput();
            }

            // Save widget
            $widget->save();

            Log::info('Widget created successfully', [
                'widget_id' => $widget->id,
                'plan_info' => $planInfo
            ]);

            return redirect()->route('property.widgets')
                ->with('success', 'Widget created successfully! You have ' . $planInfo['remainingWidgets'] . ' widgets remaining on your ' . $planInfo['planType'] . ' plan.');
        } catch (\Exception $e) {
            Log::error('Error creating widget: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('property.widgets.create')
                ->with('error', 'Error creating widget: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified widget.
     */
    public function edit(Widget $widget)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to edit widgets.');
        }

        // Check if widget belongs to this property
        if ($widget->property_id != $propertyId) {
            return redirect()->route('property.widgets')
                ->with('error', 'You do not have permission to edit this widget.');
        }

        try {
            $property = Property::findOrFail($propertyId);

            // Get all widgets for this property to display in the widget list section
            $widgets = Widget::where('property_id', $propertyId)
                        ->orderBy('order')
                        ->get();

            return view('property.widgets.edit', compact('property', 'widget', 'widgets'));
        } catch (\Exception $e) {
            Log::error('Error in widget edit: ' . $e->getMessage());
            return redirect()->route('property.widgets')
                ->with('error', 'Error loading widget edit page.');
        }
    }

    /**
     * Update the specified widget in storage.
     */
    public function update(Request $request, Widget $widget)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to update widgets.');
        }

        // Check if widget belongs to this property
        if ($widget->property_id != $propertyId) {
            return redirect()->route('property.widgets')
                ->with('error', 'You do not have permission to update this widget.');
        }

        try {
            // Update widget basic info
            $widget->title = $request->title;
            $widget->is_active = $request->has('is_active');

            if ($request->filled('order')) {
                $widget->order = $request->order;
            }

            // Process based on widget type
            switch ($widget->widget_type) {
                case 'badge':
                case 'cover_image':
                    // Handle image update if provided
                    if ($request->hasFile('image')) {
                        // Validate image
                        $request->validate([
                            'image' => 'required|image|max:2048',
                        ]);

                        // Delete old image if it exists
                        if ($widget->image_path) {
                            Storage::disk('public')->delete($widget->image_path);
                        }

                        $widget->image_path = $request->file('image')->store('widgets', 'public');
                    }
                    break;

                case 'instagram':
                case 'linkedin':
                case 'youtube':
                case 'facebook':
                    // Validate social media requirements
                    $request->validate([
                        'link_url' => 'required|string|max:255',
                    ]);

                    $widget->link_url = $request->link_url;
                    break;

                case 'text':
                    // Validate text requirements
                    $request->validate([
                        'content' => 'required|string',
                    ]);

                    $widget->content = $request->content;
                    break;

                case 'faq':
                    // Validate FAQ requirements
                    $request->validate([
                        'faq_content' => 'required|string',
                    ]);

                    // Process FAQ content
                    $faqData = json_decode($request->faq_content, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        return redirect()->back()
                            ->with('error', 'Invalid FAQ data format: ' . json_last_error_msg())
                            ->withInput();
                    }

                    if (!is_array($faqData) || count($faqData) === 0) {
                        return redirect()->back()
                            ->with('error', 'FAQ must contain at least one question and answer.')
                            ->withInput();
                    }

                    $widget->content = $request->faq_content;
                    break;
            }

            // Save widget
            $widget->save();

            return redirect()->route('property.widgets')
                ->with('success', 'Widget updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating widget: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('property.widgets.edit', $widget)
                ->with('error', 'Error updating widget: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified widget from storage.
     */
    public function destroy(Widget $widget)
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to delete widgets.');
        }

        // Check if widget belongs to this property
        if ($widget->property_id != $propertyId) {
            return redirect()->route('property.widgets')
                ->with('error', 'You do not have permission to delete this widget.');
        }

        try {
            // Delete associated image if it exists
            if ($widget->image_path) {
                Storage::disk('public')->delete($widget->image_path);
            }

            $widget->delete();

            return redirect()->route('property.widgets')
                ->with('success', 'Widget deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting widget: ' . $e->getMessage());

            return redirect()->route('property.widgets')
                ->with('error', 'Error deleting widget: ' . $e->getMessage());
        }
    }

    /**
     * Show upgrade plans page (redirects to the plans page)
     */
    public function upgradePlans()
    {
        $propertyId = session('property_id');

        if (!$propertyId) {
            return redirect()->route('property.login')
                ->with('error', 'Please login to view plans.');
        }

        // Simply redirect to the plans page
        return redirect()->route('plans.index')
            ->with('message', 'Please select a plan to increase your widget limit.');
    }
}
