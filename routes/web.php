<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProfileController;
Route::get('/property/dashboard', function () {
    // Ensure the property owner is logged in
    if (!session('property_id')) {
        return redirect()->route('property.login')
               ->with('error', 'Please login to access your dashboard.');
    }

    // Retrieve the property owner's data
    $property = \App\Models\Property::find(session('property_id'));
    // Get related reviews using property id with user relationship
    $rates = \App\Models\Rate::where('property_id', $property->id)->with('user')->get();

    return view('property.dashboard', compact('property', 'rates'));
})->name('property.dashboard');

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PropertyController;  // Add this line
use App\Http\Controllers\RateController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PropertyAuthController;
use App\Http\Controllers\PropertyPasswordResetController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IntegrationController;
use App\Http\Controllers\ReviewInvitationController;
use Illuminate\Http\Request; // Add this at the top with other imports
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\WalletController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Property Comparison Route
Route::get('/compare-properties', function () {
    return view('property-comparison');
})->name('property.comparison');

// Write Review Route - points to the existing review page
Route::get('/review', function () {
    return view('review.review');
})->name('review');

Route::get('/register', function () {
    return view('authontication.user_registration');
})->name('register.show');

Route::get('/business', function () {
    return view('business.business_home');
})->name('register.show');

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register/business', [AuthController::class, 'registerBusiness'])->name('register.business');

// Authentication Routes
Route::get('/login', function () {
    return view('authontication.user_registration');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Google Authentication Routes
Route::get('/login/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('login.google.callback');

// Password Reset Routes
Route::get('/password/reset', function () {
    return view('authontication.password_reset');
})->name('password.request');

Route::post('/password/email', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

// OTP Email Verification Routes
Route::get('/verify-email', [AuthController::class, 'showOtpVerificationForm'])->name('otp.verify.form');
Route::post('/verify-email', [AuthController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');

Route::get('/dashboard', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    $firstName = Str::before($user->name, ' ');
    return view('Dashboard.reg_user_dashboard', ['firstName' => $firstName]);
})->name('dashboard')->middleware('auth');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Two-Factor Authentication Routes
    Route::post('/profile/two-factor/enable', [ProfileController::class, 'enableTwoFactor'])->name('profile.two-factor.enable');
    Route::post('/profile/two-factor/confirm', [ProfileController::class, 'confirmTwoFactor'])->name('profile.two-factor.confirm');
    Route::post('/profile/two-factor/disable', [ProfileController::class, 'disableTwoFactor'])->name('profile.two-factor.disable');
    Route::post('/profile/two-factor/recovery-codes', [ProfileController::class, 'generateRecoveryCodes'])->name('profile.two-factor.recovery-codes');

    // Referral Routes
    Route::prefix('referrals')->name('referrals.')->group(function () {
        Route::get('/', [ReferralController::class, 'index'])->name('index');
        Route::post('/update-settings', [ReferralController::class, 'updateSettings'])->name('update-settings');
        Route::post('/generate-new-code', [ReferralController::class, 'generateNewCode'])->name('generate-new-code');
        Route::post('/toggle-status', [ReferralController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/stats', [ReferralController::class, 'getStats'])->name('stats');
    });

    // Wallet Routes
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::post('/withdrawal', [WalletController::class, 'requestWithdrawal'])->name('withdrawal');
        Route::get('/withdrawal-test', [WalletController::class, 'testWithdrawal'])->name('withdrawal.test'); // For testing
        Route::get('/balance', [WalletController::class, 'getBalance'])->name('balance');
    });
});

// Category Routes
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/subcategories/{slug}', [CategoryController::class, 'showSubcategory'])->name('subcategories.show');

// Property Routes
Route::prefix('property')->group(function () {
    Route::get('/create/step1', [PropertyController::class, 'step1'])->name('property.create.step1');
    Route::post('/create/step1', [PropertyController::class, 'submitStep1'])->name('property.submit.step1');
    Route::get('/create/step2', [PropertyController::class, 'step2'])->name('property.create.step2');
    Route::post('/create/step2', [PropertyController::class, 'submitStep2'])->name('property.submit.step2');
    Route::get('/create/step3', [PropertyController::class, 'step3'])->name('property.create.step3');
    Route::post('/create/step3', [PropertyController::class, 'submitStep3'])->name('property.submit.step3');
    Route::get('/success', [PropertyController::class, 'success'])->name('property.success');
    Route::post('/property/resend-otp', [PropertyController::class, 'resendOTP'])->name('property.resend.otp');

    // Property owner authentication (accessible to everyone)
    Route::get('/login', [PropertyAuthController::class, 'showLoginForm'])->name('property.login');
    Route::post('/login', [PropertyAuthController::class, 'login'])->name('property.login.submit');
    Route::post('/logout', [PropertyAuthController::class, 'logout'])->name('property.logout');

    // Password reset routes for property owners.
    Route::get('/password/reset', [PropertyPasswordResetController::class, 'showLinkRequestForm'])->name('property.password.request');
    Route::post('/password/email', [PropertyPasswordResetController::class, 'sendResetLinkEmail'])->name('property.password.email');
    Route::get('/password/reset/form', [PropertyPasswordResetController::class, 'showResetForm'])->name('property.password.reset.form');
    Route::post('/password/reset', [PropertyPasswordResetController::class, 'reset'])->name('property.password.reset');

    Route::get('/settings', [PropertyController::class, 'settings'])->name('property.settings');
    Route::put('/settings', [PropertyController::class, 'updateSettings'])->name('property.settings.update');

    // Products routes
    Route::get('/products', [ProductController::class, 'index'])->name('property.products');
    Route::get('/products/create', [ProductController::class, 'create'])->name('property.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('property.products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('property.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('property.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('property.products.destroy');

    // HTML Integration routes
    Route::get('/integrations', [IntegrationController::class, 'index'])->name('property.integrations');
    Route::get('/integrations/create', [IntegrationController::class, 'create'])->name('property.integrations.create');
    Route::post('/integrations', [IntegrationController::class, 'store'])->name('property.integrations.store');
    Route::get('/integrations/{id}/edit', [IntegrationController::class, 'edit'])->name('property.integrations.edit');
    Route::put('/integrations/{id}', [IntegrationController::class, 'update'])->name('property.integrations.update');
    Route::delete('/integrations/{id}', [IntegrationController::class, 'destroy'])->name('property.integrations.destroy');

    // Review Invitation routes
    Route::get('/invitations', [ReviewInvitationController::class, 'index'])->name('property.invitations');
    Route::get('/invitations/create', [ReviewInvitationController::class, 'create'])->name('property.invitations.create');
    Route::post('/invitations', [ReviewInvitationController::class, 'store'])->name('property.invitations.store');
    Route::get('/invitations/{id}', [ReviewInvitationController::class, 'show'])->name('property.invitations.show');
    Route::delete('/invitations/{id}', [ReviewInvitationController::class, 'destroy'])->name('property.invitations.destroy');
    Route::post('/invitations/{id}/resend', [ReviewInvitationController::class, 'resend'])->name('property.invitations.resend');

    // Bulk invitation routes
    Route::get('/invitations/bulk/create', [ReviewInvitationController::class, 'bulkCreate'])->name('property.invitations.bulk');
    Route::post('/invitations/bulk/send', [ReviewInvitationController::class, 'bulkSend'])->name('property.invitations.bulk.send');

    // Widget routes
    Route::get('/widgets', [App\Http\Controllers\WidgetController::class, 'index'])->name('property.widgets');
    Route::get('/widgets/create', [App\Http\Controllers\WidgetController::class, 'create'])->name('property.widgets.create');
    Route::post('/widgets', [App\Http\Controllers\WidgetController::class, 'store'])->name('property.widgets.store');
    Route::get('/widgets/{widget}/edit', [App\Http\Controllers\WidgetController::class, 'edit'])->name('property.widgets.edit');
    Route::put('/widgets/{widget}', [App\Http\Controllers\WidgetController::class, 'update'])->name('property.widgets.update');
    Route::delete('/widgets/{widget}', [App\Http\Controllers\WidgetController::class, 'destroy'])->name('property.widgets.destroy');
    Route::get('/widgets/upgrade', [App\Http\Controllers\WidgetController::class, 'upgradePlans'])->name('property.widgets.upgrade');

    // Add this inside your property prefix group
    Route::get('/reviews', [PropertyController::class, 'reviews'])->name('property.reviews');
    Route::get('/review-analysis', [PropertyController::class, 'reviewAnalysis'])->name('property.review.analysis');
    Route::get('/review-analysis/excel', [PropertyController::class, 'downloadExcel'])->name('property.review.excel');
    Route::get('/review-analysis/pdf', [PropertyController::class, 'downloadPdf'])->name('property.review.pdf');
    Route::post('/review-analysis/chart-image', [PropertyController::class, 'downloadChartImage'])->name('property.review.chart-image');
    Route::get('/plans/upgrade', [PropertyController::class, 'planUpgrade'])->name('property.plan.upgrade');

    // Plans routes (requires property authentication)
    Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
    Route::post('/plans/select', [PlanController::class, 'select'])->name('plans.select');
    Route::get('/plans/activated', [PlanController::class, 'activated'])->name('plans.activated');
});

// Payment routes (requires property authentication via session check in controller)
Route::middleware(['web'])->group(function () {
    Route::get('/payment/checkout', [PaymentController::class, 'showCheckout'])->name('payment.checkout.show');
    Route::post('/payment/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');
    Route::post('/payment/webhook', [PaymentController::class, 'handleWebhook'])->name('payment.webhook');
    Route::get('/transaction-status/{transactionId}', [PaymentController::class, 'checkTransactionStatus']);
});
Route::middleware([\App\Http\Middleware\CheckPremiumPlan::class])->group(function () {
    Route::get('/property/review-analysis/pdf', [PropertyController::class, 'downloadPdf'])
        ->name('property.review.pdf');
    Route::post('/property/review-analysis/chart-image', [PropertyController::class, 'downloadChartImage'])
        ->name('property.review.chart-image');
    Route::get('/property/review-analysis/excel', [PropertyController::class, 'downloadExcel'])
        ->name('property.review.excel');
});

// The main analysis page is accessible to all, but will show different content
// based on plan status
Route::get('/property/review-analysis', [PropertyController::class, 'reviewAnalysis'])
    ->name('property.review.analysis');

Route::get('/property/{property}', [PropertyController::class, 'show'])
    ->name('property.show')
    ->where('property', '[0-9]+'); // Only match numeric property IDs

// Example of a protected property dashboard route using a simple session check:
Route::get('/property/dashboard', function () {
    // Ensure the property owner is logged in
    if (!session('property_id')) {
        return redirect()->route('property.login')
               ->with('error', 'Please login to access your dashboard.');
    }

    // Retrieve the property owner’s data
    $property = \App\Models\Property::find(session('property_id'));
    // Get related reviews using property id with user relationship
    $rates = \App\Models\Rate::where('property_id', $property->id)->with('user')->get();

    return view('property.dashboard', compact('property', 'rates'));
})->name('property.dashboard');

Route::get('/properties/subcategory/{subcategory}', [PropertyController::class, 'showBySubcategory'])
    ->name('properties.subcategory')
    ->where('subcategory', '[0-9]+'); // This ensures the parameter is numeric

Route::get('/properties', [PropertyController::class, 'index'])->name('property.properties');

Route::middleware(['auth'])->group(function () {
    Route::post('/rate', [RateController::class, 'store'])->name('rate.store');
    Route::get('/rate/{property}', [RateController::class, 'create'])->name('rate.create');
    Route::post('/rate/{property}', [RateController::class, 'store'])->name('rate.store');
});

// Admin login routes
Route::prefix('admin')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    // Dashboard Route
    Route::get('dashboard', [AdminDashboardController::class, 'index'])
         ->name('admin.dashboard')
         ->middleware('auth:admin');

    // Users Management
    Route::get('users', [\App\Http\Controllers\Admin\UserController::class, 'index'])
         ->name('admin.users.index')
         ->middleware('auth:admin');

    Route::get('users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show'])
         ->name('admin.users.show')
         ->middleware('auth:admin');

    Route::get('users/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])
         ->name('admin.users.edit')
         ->middleware('auth:admin');

    Route::put('users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])
         ->name('admin.users.update')
         ->middleware('auth:admin');

    Route::delete('users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])
         ->name('admin.users.destroy')
         ->middleware('auth:admin');

    Route::post('users/{id}/verify', [\App\Http\Controllers\Admin\UserController::class, 'verify'])
         ->name('admin.users.verify')
         ->middleware('auth:admin');

    Route::post('users/{id}/unverify', [\App\Http\Controllers\Admin\UserController::class, 'unverify'])
         ->name('admin.users.unverify')
         ->middleware('auth:admin');

    // Properties management routes
    Route::get('properties', [\App\Http\Controllers\Admin\PropertyController::class, 'index'])
         ->name('admin.properties.index')
         ->middleware('auth:admin');

    Route::get('properties/{property}', [\App\Http\Controllers\Admin\PropertyController::class, 'show'])
         ->name('admin.properties.show')
         ->middleware('auth:admin');

    Route::get('properties/{property}/edit', [\App\Http\Controllers\Admin\PropertyController::class, 'edit'])
         ->name('admin.properties.edit')
         ->middleware('auth:admin');

    Route::put('properties/{property}', [\App\Http\Controllers\Admin\PropertyController::class, 'update'])
         ->name('admin.properties.update')
         ->middleware('auth:admin');

    Route::delete('properties/{property}', [\App\Http\Controllers\Admin\PropertyController::class, 'destroy'])
         ->name('admin.properties.destroy')
         ->middleware('auth:admin');

    Route::post('properties/{id}/approve', [\App\Http\Controllers\Admin\PropertyController::class, 'approve'])
         ->name('admin.properties.approve')
         ->middleware('auth:admin');

    Route::post('properties/{id}/reject', [\App\Http\Controllers\Admin\PropertyController::class, 'reject'])
         ->name('admin.properties.reject')
         ->middleware('auth:admin');

    // Reviews management routes
    Route::get('reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])
         ->name('admin.reviews.index')
         ->middleware('auth:admin');

    Route::get('reviews/{id}', [\App\Http\Controllers\Admin\ReviewController::class, 'show'])
         ->name('admin.reviews.show')
         ->middleware('auth:admin');

    Route::get('reviews/{id}/edit', [\App\Http\Controllers\Admin\ReviewController::class, 'edit'])
         ->name('admin.reviews.edit')
         ->middleware('auth:admin');

    Route::put('reviews/{id}', [\App\Http\Controllers\Admin\ReviewController::class, 'update'])
         ->name('admin.reviews.update')
         ->middleware('auth:admin');

    Route::delete('reviews/{id}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])
         ->name('admin.reviews.destroy')
         ->middleware('auth:admin');

    Route::post('reviews/{id}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])
         ->name('admin.reviews.approve')
         ->middleware('auth:admin');

    Route::post('reviews/{id}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])
         ->name('admin.reviews.reject')
         ->middleware('auth:admin');

    // Email Templates Management
    Route::get('email-templates', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'index'])
         ->name('admin.email_templates.index')
         ->middleware('auth:admin');

    Route::get('email-templates/create', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'create'])
         ->name('admin.email_templates.create')
         ->middleware('auth:admin');

    Route::post('email-templates', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'store'])
         ->name('admin.email_templates.store')
         ->middleware('auth:admin');

    Route::get('email-templates/{id}', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'show'])
         ->name('admin.email_templates.show')
         ->middleware('auth:admin');

    Route::get('email-templates/{id}/edit', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'edit'])
         ->name('admin.email_templates.edit')
         ->middleware('auth:admin');

    Route::put('email-templates/{id}', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'update'])
         ->name('admin.email_templates.update')
         ->middleware('auth:admin');

    Route::delete('email-templates/{id}', [\App\Http\Controllers\Admin\EmailTemplateController::class, 'destroy'])
         ->name('admin.email_templates.destroy')
         ->middleware('auth:admin');

    // Categories Management
    Route::get('categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])
         ->name('admin.categories.index')
         ->middleware('auth:admin');

    Route::get('categories/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])
         ->name('admin.categories.create')
         ->middleware('auth:admin');

    Route::post('categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])
         ->name('admin.categories.store')
         ->middleware('auth:admin');

    Route::get('categories/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'show'])
         ->name('admin.categories.show')
         ->middleware('auth:admin');

    Route::get('categories/{id}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])
         ->name('admin.categories.edit')
         ->middleware('auth:admin');

    Route::put('categories/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])
         ->name('admin.categories.update')
         ->middleware('auth:admin');

    Route::post('categories/{id}/approve', [\App\Http\Controllers\Admin\CategoryController::class, 'approve'])
         ->name('admin.categories.approve')
         ->middleware('auth:admin');

    Route::post('categories/{id}/reject', [\App\Http\Controllers\Admin\CategoryController::class, 'reject'])
         ->name('admin.categories.reject')
         ->middleware('auth:admin');

    Route::delete('categories/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])
         ->name('admin.categories.destroy')
         ->middleware('auth:admin');

    // Subcategories Management
    Route::get('subcategories', [\App\Http\Controllers\Admin\SubcategoryController::class, 'index'])
         ->name('admin.subcategories.index')
         ->middleware('auth:admin');

    Route::get('subcategories/create', [\App\Http\Controllers\Admin\SubcategoryController::class, 'create'])
         ->name('admin.subcategories.create')
         ->middleware('auth:admin');

    Route::post('subcategories', [\App\Http\Controllers\Admin\SubcategoryController::class, 'store'])
         ->name('admin.subcategories.store')
         ->middleware('auth:admin');

    Route::get('subcategories/{id}', [\App\Http\Controllers\Admin\SubcategoryController::class, 'show'])
         ->name('admin.subcategories.show')
         ->middleware('auth:admin');

    Route::get('subcategories/{id}/edit', [\App\Http\Controllers\Admin\SubcategoryController::class, 'edit'])
         ->name('admin.subcategories.edit')
         ->middleware('auth:admin');

    Route::put('subcategories/{id}', [\App\Http\Controllers\Admin\SubcategoryController::class, 'update'])
         ->name('admin.subcategories.update')
         ->middleware('auth:admin');

    Route::post('subcategories/{id}/approve', [\App\Http\Controllers\Admin\SubcategoryController::class, 'approve'])
         ->name('admin.subcategories.approve')
         ->middleware('auth:admin');

    Route::post('subcategories/{id}/reject', [\App\Http\Controllers\Admin\SubcategoryController::class, 'reject'])
         ->name('admin.subcategories.reject')
         ->middleware('auth:admin');

    Route::delete('subcategories/{id}', [\App\Http\Controllers\Admin\SubcategoryController::class, 'destroy'])
         ->name('admin.subcategories.destroy')
         ->middleware('auth:admin');

    // Plans Management
    Route::get('plans', [\App\Http\Controllers\Admin\PlanController::class, 'index'])
         ->name('admin.plans.index')
         ->middleware('auth:admin');

    Route::get('plans/create', [\App\Http\Controllers\Admin\PlanController::class, 'create'])
         ->name('admin.plans.create')
         ->middleware('auth:admin');

    Route::post('plans', [\App\Http\Controllers\Admin\PlanController::class, 'store'])
         ->name('admin.plans.store')
         ->middleware('auth:admin');

    Route::get('plans/{id}', [\App\Http\Controllers\Admin\PlanController::class, 'show'])
         ->name('admin.plans.show')
         ->middleware('auth:admin');

    Route::get('plans/{id}/edit', [\App\Http\Controllers\Admin\PlanController::class, 'edit'])
         ->name('admin.plans.edit')
         ->middleware('auth:admin');

    Route::put('plans/{id}', [\App\Http\Controllers\Admin\PlanController::class, 'update'])
         ->name('admin.plans.update')
         ->middleware('auth:admin');

    // Referrals Management
    Route::get('referrals', [\App\Http\Controllers\Admin\ReferralController::class, 'index'])
         ->name('admin.referrals.index')
         ->middleware('auth:admin');

    Route::get('referrals/create', [\App\Http\Controllers\Admin\ReferralController::class, 'create'])
         ->name('admin.referrals.create')
         ->middleware('auth:admin');

    Route::post('referrals', [\App\Http\Controllers\Admin\ReferralController::class, 'store'])
         ->name('admin.referrals.store')
         ->middleware('auth:admin');

    Route::get('referrals/{id}', [\App\Http\Controllers\Admin\ReferralController::class, 'show'])
         ->name('admin.referrals.show')
         ->middleware('auth:admin');

    Route::get('referrals/{id}/edit', [\App\Http\Controllers\Admin\ReferralController::class, 'edit'])
         ->name('admin.referrals.edit')
         ->middleware('auth:admin');

    Route::put('referrals/{id}', [\App\Http\Controllers\Admin\ReferralController::class, 'update'])
         ->name('admin.referrals.update')
         ->middleware('auth:admin');

    Route::post('referrals/{id}/activate', [\App\Http\Controllers\Admin\ReferralController::class, 'activate'])
         ->name('admin.referrals.activate')
         ->middleware('auth:admin');

    Route::post('referrals/{id}/deactivate', [\App\Http\Controllers\Admin\ReferralController::class, 'deactivate'])
         ->name('admin.referrals.deactivate')
         ->middleware('auth:admin');

    Route::delete('referrals/{id}', [\App\Http\Controllers\Admin\ReferralController::class, 'destroy'])
         ->name('admin.referrals.destroy')
         ->middleware('auth:admin');

    Route::post('referrals/update-rate', [\App\Http\Controllers\Admin\ReferralController::class, 'updateReferralRate'])
         ->name('admin.referrals.update-rate')
         ->middleware('auth:admin');

    // Staff Management
    Route::get('staff', [\App\Http\Controllers\Admin\StaffController::class, 'index'])
         ->name('admin.staff.index')
         ->middleware('auth:admin');

    Route::get('staff/create', [\App\Http\Controllers\Admin\StaffController::class, 'create'])
         ->name('admin.staff.create')
         ->middleware('auth:admin');

    Route::post('staff', [\App\Http\Controllers\Admin\StaffController::class, 'store'])
         ->name('admin.staff.store')
         ->middleware('auth:admin');

    Route::get('staff/{id}', [\App\Http\Controllers\Admin\StaffController::class, 'show'])
         ->name('admin.staff.show')
         ->middleware('auth:admin');

    Route::get('staff/{id}/edit', [\App\Http\Controllers\Admin\StaffController::class, 'edit'])
         ->name('admin.staff.edit')
         ->middleware('auth:admin');

    Route::put('staff/{id}', [\App\Http\Controllers\Admin\StaffController::class, 'update'])
         ->name('admin.staff.update')
         ->middleware('auth:admin');

    Route::post('staff/{id}/activate', [\App\Http\Controllers\Admin\StaffController::class, 'activate'])
         ->name('admin.staff.activate')
         ->middleware('auth:admin');

    Route::post('staff/{id}/deactivate', [\App\Http\Controllers\Admin\StaffController::class, 'deactivate'])
         ->name('admin.staff.deactivate')
         ->middleware('auth:admin');

    Route::post('staff/{id}/suspend', [\App\Http\Controllers\Admin\StaffController::class, 'suspend'])
         ->name('admin.staff.suspend')
         ->middleware('auth:admin');

    // Payment Management
    Route::get('payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])
         ->name('admin.payments.index')
         ->middleware('auth:admin');

    Route::get('payments/{id}', [\App\Http\Controllers\Admin\PaymentController::class, 'show'])
         ->name('admin.payments.show')
         ->middleware('auth:admin');

    Route::get('payments/{id}/edit', [\App\Http\Controllers\Admin\PaymentController::class, 'edit'])
         ->name('admin.payments.edit')
         ->middleware('auth:admin');

    Route::put('payments/{id}', [\App\Http\Controllers\Admin\PaymentController::class, 'update'])
         ->name('admin.payments.update')
         ->middleware('auth:admin');

    Route::delete('payments/{id}', [\App\Http\Controllers\Admin\PaymentController::class, 'destroy'])
         ->name('admin.payments.destroy')
         ->middleware('auth:admin');

    Route::post('payments/{id}/confirm', [\App\Http\Controllers\Admin\PaymentController::class, 'confirm'])
         ->name('admin.payments.confirm')
         ->middleware('auth:admin');

    Route::post('payments/{id}/cancel', [\App\Http\Controllers\Admin\PaymentController::class, 'cancel'])
         ->name('admin.payments.cancel')
         ->middleware('auth:admin');

    Route::post('payments/{id}/refund', [\App\Http\Controllers\Admin\PaymentController::class, 'refund'])
         ->name('admin.payments.refund')
         ->middleware('auth:admin');

    // Wallet Management
    Route::get('wallets', [\App\Http\Controllers\Admin\WalletController::class, 'index'])
         ->name('admin.wallets.index')
         ->middleware('auth:admin');

    Route::get('wallets/{id}', [\App\Http\Controllers\Admin\WalletController::class, 'show'])
         ->name('admin.wallets.show')
         ->middleware('auth:admin');

    Route::get('wallets/{id}/edit', [\App\Http\Controllers\Admin\WalletController::class, 'edit'])
         ->name('admin.wallets.edit')
         ->middleware('auth:admin');

    Route::put('wallets/{id}', [\App\Http\Controllers\Admin\WalletController::class, 'update'])
         ->name('admin.wallets.update')
         ->middleware('auth:admin');

    Route::delete('wallets/{id}', [\App\Http\Controllers\Admin\WalletController::class, 'destroy'])
         ->name('admin.wallets.destroy')
         ->middleware('auth:admin');

    Route::post('wallets/{id}/adjust-balance', [\App\Http\Controllers\Admin\WalletController::class, 'adjustBalance'])
         ->name('admin.wallets.adjust')
         ->middleware('auth:admin');

    // Admin Profile Management
    Route::get('profile/edit', [\App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])
         ->name('admin.profile.edit')
         ->middleware('auth:admin');

    Route::put('profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])
         ->name('admin.profile.update')
         ->middleware('auth:admin');

    Route::delete('profile/remove-picture', [\App\Http\Controllers\Admin\AdminProfileController::class, 'removeProfilePicture'])
         ->name('admin.profile.remove-picture')
         ->middleware('auth:admin');
});

// Public routes for email tracking and reviews
Route::get('/track-email/{id}', [ReviewInvitationController::class, 'trackOpen']);
Route::get('/review/{token}', [ReviewInvitationController::class, 'showReviewForm'])->name('public.review.form');
Route::post('/review/{token}/submit', [ReviewInvitationController::class, 'submitReview'])->name('public.review.submit');

Route::get('/check-failed-jobs', function() {
    $failedJobs = DB::table('failed_jobs')->orderBy('failed_at', 'desc')->get();

    if ($failedJobs->isEmpty()) {
        return 'No failed jobs found.';
    }

    $output = '<h1>Failed Jobs</h1>';
    foreach ($failedJobs as $job) {
        $output .= '<div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;">';
        $output .= '<p><strong>ID:</strong> ' . $job->id . '</p>';
        $output .= '<p><strong>Failed At:</strong> ' . $job->failed_at . '</p>';
        $output .= '<p><strong>Error:</strong> ' . $job->exception . '</p>';
        $output .= '</div>';
    }

    return $output;
});

Route::get('/test-review-email', function () {
    try {
        $mail = new \App\Mail\ReviewInvitation(
            'Test User',
            'Test Business',
            'This is a test message for the review invitation.',
            url('/review/test-token'),
            999
        );

        Mail::to('your-email@example.com')->send($mail);

        return 'Test email sent successfully! Check your inbox.';
    } catch (\Exception $e) {
        return 'Error sending test email: ' . $e->getMessage() .
               '<br><br>Stack trace: <pre>' . $e->getTraceAsString() . '</pre>';
    }
});

Route::get('/top-rated-businesses', [App\Http\Controllers\HomeController::class, 'getTopRatedBusinesses'])->name('properties.top_rated');

// You'll also want to make sure you have a property show route defined
Route::get('/property/{id}', [App\Http\Controllers\PropertyController::class, 'show'])->name('property.show');

// Search route
Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');

// Route for viewing businesses in a subcategory
Route::get('/category/{slug}', [App\Http\Controllers\SubcategoryController::class, 'showBusinesses'])->name('subcategory.businesses');

// Add this route for debugging the search API
Route::get('/debug-search', function() {
    return view('debug.search');
});

// Add these routes for search debugging and regular search functionality

use App\Http\Controllers\SearchController;
use App\Http\Controllers\Api\SearchController as ApiSearchController;

// Debug route for testing the search API
Route::get('/debug-search', function() {
    return view('debug.search');
});

// Regular search route (fallback for when JS is disabled or for direct use)
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Direct test of the API search function (useful for debugging)
Route::get('/test-api-search', function(Request $request) {
    $controller = new SearchController();
    return $controller->search($request);
});

// Add these routes for profile management

// Profile routes
Route::middleware(['auth'])->group(function () {
    // Basic profile update
    Route::put('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');

    // Preference updates
    Route::put('/profile/preferences', [App\Http\Controllers\ProfileController::class, 'updatePreferences'])
        ->name('profile.update.preferences');

    // Password update
    Route::put('/profile/password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])
        ->name('profile.update.password');

    // Privacy settings update
    Route::put('/profile/privacy', [App\Http\Controllers\ProfileController::class, 'updatePrivacy'])
        ->name('profile.update.privacy');

    // Account deletion
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::get('/pricing', [App\Http\Controllers\HomeController::class, 'pricing'])->name('pricing');

Route::get('/privacy-policy', function () {
    return view('home.privacy_policy');
})->name('privacy.policy');

Route::get('/terms-and-conditions', function () {
    return view('home.terms_conditions');
})->name('terms.conditions');

Route::get('/contact-us', function () {
    return view('home.contact_us');
})->name('contact.us');

// Contact form submission handler
Route::post('/contact-us', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ]);

    // Here you would typically send an email with the contact form data
    // Mail::to('info@scoreness.com')->send(new ContactFormMail($validated));

    return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
})->name('contact.submit');

Route::post('/properties', [App\Http\Controllers\PropertyController::class, 'store'])->name('properties.store');

Route::get('/api/subcategories/{categoryId}', function($categoryId) {
    try {
        // Use raw query builder for reliability
        $subcategories = DB::select("SELECT id, name, category_id FROM subcategories WHERE category_id = ?", [$categoryId]);
        return response()->json($subcategories);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('subcategories.get');

// Add this to your web.php routes file
Route::get('/add-business', function () {
    return view('properties.add');
})->name('properties.add');

// Add this to your web.php
Route::get('/mock-payment/{transaction_id}', function($transactionId) {
    return view('payments.mock-payment', ['transactionId' => $transactionId]);
})->name('mock.payment.page');

Route::post('/mock-payment/{transaction_id}/complete', function($transactionId) {
    // Find and update the payment
    $payment = \App\Models\Payment::where('genie_transaction_id', $transactionId)->first();

    if ($payment) {
        $payment->update([
            'status' => 'success',
            'completed_at' => now()
        ]);

        Log::info('Mock payment completed', [
            'payment_id' => $payment->id,
            'transaction_id' => $transactionId
        ]);
    }

    return redirect()->route('payment.success');
})->name('mock.payment.complete');

// Test route for payment gateway
Route::get('/test-payment', function () {
    $plans = \App\Models\Plan::whereIn('id', [2, 3])->get();
    return view('test-payment', compact('plans'));
})->name('test.payment');

Route::post('/mock-payment/{transaction_id}/complete', function($transactionId) {
    // Find and update the payment
    $payment = \App\Models\Payment::where('genie_transaction_id', $transactionId)->first();

    if ($payment) {
        $payment->update([
            'status' => 'success',
            'completed_at' => now()
        ]);

        Log::info('Mock payment completed', [
            'payment_id' => $payment->id,
            'transaction_id' => $transactionId
        ]);
    }

    return redirect()->route('payment.success');
})->name('mock.payment.complete');

Route::get('/test-subcategories-direct/{categoryId}', function($categoryId) {
    try {
        // Try using raw query builder first
        $subcategories = DB::select("SELECT id, name, category_id FROM subcategories WHERE category_id = ?", [$categoryId]);
        return response()->json($subcategories);
    } catch (\Exception $e) {
        // Fallback to hardcoded data
        $testData = [
            ['id' => 1, 'name' => 'Animal Health', 'category_id' => 1],
            ['id' => 2, 'name' => 'Animal Parks & Zoo', 'category_id' => 1],
            ['id' => 3, 'name' => 'Cats & Dogs', 'category_id' => 1],
        ];
        return response()->json($testData);
    }
});


