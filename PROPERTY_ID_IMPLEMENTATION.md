# Property ID Association in Payment System

## Problem
The payment records were not storing the `property_id` from the logged-in property account, resulting in NULL values in the `property_id` column. This made it difficult to associate payments with specific properties.

## Solution Implemented

### 1. Updated PaymentController::checkout()
- Now retrieves `property_id` from the session using `session('property_id', null)`
- Stores the property_id in both new payment creation and existing payment updates
- Added logging to track property_id in payment checkout process

### 2. Enhanced PlanController Logic
- Updated `index()` method to query payments by both property_id and business_email
- Updated `activated()` method with the same logic
- Uses preference for records with property_id over business_email-only records

### 3. Improved Payment Model Helper Methods
- Enhanced `getActivePlanForCustomer()` to accept optional property_id parameter
- Enhanced `hasActivePlan()` to accept optional property_id parameter
- Both methods now search by property_id first, then fall back to business_email

### 4. Added Property Model Methods
- `activePayment()` - relationship to get active payment
- `getActivePlan()` - get the active plan for the property
- `hasActivePlan()` - check if property has active plan

### 5. Data Migration
- Created migration to update existing payment records
- Updated NULL property_id values by matching business_email with properties
- Existing payments now have proper property_id associations

## Benefits

1. **Better Data Integrity**: Payments are now properly linked to specific properties
2. **Improved Querying**: Can find payments by property_id for better performance and accuracy
3. **Future-Proof**: System can handle cases where same email is used for multiple properties
4. **Backward Compatible**: Still works with business_email fallback for edge cases

## Usage

```php
// Get active plan for a property
$property = Property::find($propertyId);
$activePlan = $property->getActivePlan();

// Check if property has active plan
if ($property->hasActivePlan()) {
    // Property has an active plan
}

// Get active plan using helper method
$activePlan = Payment::getActivePlanForCustomer($email, $propertyId);
```

## Testing
- Verified existing payment records now have property_id values
- Confirmed new payments will store property_id from session
- System maintains single active plan per property logic
