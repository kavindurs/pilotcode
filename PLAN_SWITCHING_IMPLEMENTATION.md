# Plan Switching Implementation - Update Existing Records

## Problem
When a new plan was activated from the same property account, a new data row was being added to the Plans table instead of updating the existing Plan ID in the current data row.

## Solution Implemented

### 1. Enhanced PaymentController::checkout() Method
**File**: `app/Http/Controllers/PaymentController.php`
**Lines**: ~160-190

**Changes Made**:
- Replaced complex existing payment lookup logic with cleaner `Payment::findExistingPaymentForProperty()` helper method
- Removed status-based condition checking - now updates **any** existing payment record (not just confirmed ones)
- Enhanced logic to prioritize property_id over business_email when finding existing records

**Before**:
```php
$existingPayment = Payment::where('business_email', $request->customer_email)
    ->whereIn('status', ['confirmed', 'CONFIRMED', 'pending', 'PENDING'])
    ->first();

if ($existingPayment && in_array($existingPayment->status, ['confirmed', 'CONFIRMED'])) {
    // Only update confirmed payments
}
```

**After**:
```php
$existingPayment = Payment::findExistingPaymentForProperty($request->customer_email, $propertyId);

if ($existingPayment) {
    // Update any existing payment record
    $payment = $existingPayment;
    $payment->update([
        'plan_id' => $plan->id, // Update to new plan
        // ... other fields
    ]);
}
```

### 2. Added Payment::findExistingPaymentForProperty() Helper Method
**File**: `app/Models/Payment.php`
**Method**: `findExistingPaymentForProperty($businessEmail, $propertyId = null)`

**Functionality**:
1. **Priority Search**: First searches by `property_id` if provided
2. **Fallback Search**: Falls back to `business_email` if no property_id match found
3. **Single Record**: Returns the first matching payment record for updating
4. **Null Safe**: Handles cases where property_id might be null

```php
public static function findExistingPaymentForProperty($businessEmail, $propertyId = null)
{
    if ($propertyId) {
        // First try to find by property_id
        $payment = self::where('property_id', $propertyId)->first();
        if ($payment) {
            return $payment;
        }
    }

    // Fall back to business_email
    return self::where('business_email', $businessEmail)->first();
}
```

## Benefits

### 1. **Data Integrity**
- **Single Payment Record**: Each property account maintains only one payment record
- **No Duplicates**: Prevents multiple payment records for the same property
- **Clean Database**: Avoids payment table bloat over time

### 2. **Plan Switching Logic**
- **Seamless Updates**: Existing payment records are updated with new plan_id
- **Status Reset**: Payment status is reset to 'pending' for new payment processing
- **Order Tracking**: New order_id is assigned for tracking purposes

### 3. **Backward Compatibility**
- **Email Fallback**: Still works with business_email for properties without property_id
- **Existing Data**: Works with existing payment records in the database
- **Migration Safe**: No data migration required

## Implementation Flow

### When a Property Activates a New Plan:

1. **Session Check**: Get `property_id` from session
2. **Find Existing**: Use `findExistingPaymentForProperty()` to locate existing payment record
3. **Update vs Create**:
   - **If existing record found**: Update with new plan_id, amount, and reset status
   - **If no record found**: Create new payment record

### Search Priority:
1. **Primary**: Search by `property_id` (most specific)
2. **Secondary**: Search by `business_email` (fallback)
3. **Result**: Return first matching record or null

## Testing Approach

### Manual Verification:
1. Property account activates Basic plan → Creates payment record
2. Same property account switches to Pro plan → Updates existing record (not creates new)
3. Verify: Only one payment record exists for the property
4. Verify: Plan_id is updated to new plan

### Expected Database Behavior:
```sql
-- Before plan switch
SELECT * FROM payments WHERE property_id = 123;
-- Result: 1 record with plan_id = 1 (Basic)

-- After plan switch  
SELECT * FROM payments WHERE property_id = 123;
-- Result: 1 record with plan_id = 2 (Pro) - UPDATED, not new record
```

## Files Modified

1. **`app/Http/Controllers/PaymentController.php`**
   - Updated `checkout()` method logic
   - Cleaner existing payment lookup
   - Always update existing records when found

2. **`app/Models/Payment.php`**
   - Added `findExistingPaymentForProperty()` helper method
   - Property-priority search logic

3. **`tests/Feature/PlanSwitchingTest.php`**
   - Added comprehensive tests for new functionality
   - Verification of single record per property
   - Property_id priority testing

## Result
✅ **Problem Solved**: When a new plan is activated from the same property account, the existing payment record is updated with the new plan_id instead of creating a new data row.

✅ **Data Consistency**: Maintains single payment record per property account.

✅ **Future Proof**: System ready for property-based plan management scaling.
