#!/bin/bash

echo "=== PAYMENT SESSION FLOW TEST ==="
echo "Testing session-based payment flow without payment record creation upfront"
echo ""

BASE_URL="http://127.0.0.1:8000"
COOKIES_FILE="/tmp/payment_test_cookies.txt"

# Clean up any existing cookies
rm -f "$COOKIES_FILE"

echo "Step 1: Login as property..."

# Get login page to get CSRF token
LOGIN_PAGE=$(curl -s -c "$COOKIES_FILE" "$BASE_URL/property/login")
CSRF_TOKEN=$(echo "$LOGIN_PAGE" | grep -o 'name="_token" value="[^"]*"' | cut -d'"' -f4)

if [ -z "$CSRF_TOKEN" ]; then
    echo "✗ Failed to get CSRF token"
    exit 1
fi

echo "✓ Got CSRF token: $CSRF_TOKEN"

# Login
LOGIN_RESPONSE=$(curl -s -b "$COOKIES_FILE" -c "$COOKIES_FILE" \
    -X POST \
    -H "X-CSRF-TOKEN: $CSRF_TOKEN" \
    -d "property_id=1" \
    -d "password=password123" \
    -d "_token=$CSRF_TOKEN" \
    -w "HTTPSTATUS:%{http_code}" \
    "$BASE_URL/property/login")

LOGIN_STATUS=$(echo "$LOGIN_RESPONSE" | grep -o "HTTPSTATUS:[0-9]*" | cut -d: -f2)

if [ "$LOGIN_STATUS" = "302" ]; then
    echo "✓ Login successful"
else
    echo "✗ Login failed (Status: $LOGIN_STATUS)"
    exit 1
fi

echo ""
echo "Step 2: Check payment record before payment process..."

# Check payment table before
PAYMENT_BEFORE=$(curl -s "$BASE_URL/debug_payment_table.php")
echo "Payment records before:"
echo "$PAYMENT_BEFORE" | grep -A5 -B5 "Property ID: 1" || echo "No payment record for property 1"

echo ""
echo "Step 3: Load checkout page..."

# Get checkout page
CHECKOUT_PAGE=$(curl -s -b "$COOKIES_FILE" -c "$COOKIES_FILE" \
    "$BASE_URL/plans/checkout?plan_id=2&amount=2500")

# Extract CSRF token from checkout page
CSRF_TOKEN=$(echo "$CHECKOUT_PAGE" | grep -o 'name="_token" value="[^"]*"' | cut -d'"' -f4)

if [ -z "$CSRF_TOKEN" ]; then
    echo "✗ Failed to get CSRF token from checkout page"
    exit 1
fi

echo "✓ Checkout page loaded, CSRF token: $CSRF_TOKEN"

echo ""
echo "Step 4: Submit payment form..."

# Submit payment form
PAYMENT_RESPONSE=$(curl -s -b "$COOKIES_FILE" -c "$COOKIES_FILE" \
    -X POST \
    -H "X-CSRF-TOKEN: $CSRF_TOKEN" \
    -d "plan_id=2" \
    -d "amount=2500" \
    -d "payment_method=genie_business" \
    -d "_token=$CSRF_TOKEN" \
    -w "HTTPSTATUS:%{http_code}||LOCATION:%{redirect_url}" \
    "$BASE_URL/plans/payment/process")

PAYMENT_STATUS=$(echo "$PAYMENT_RESPONSE" | grep -o "HTTPSTATUS:[0-9]*" | cut -d: -f2)
PAYMENT_LOCATION=$(echo "$PAYMENT_RESPONSE" | grep -o "LOCATION:.*" | cut -d: -f2-)

echo "Payment response status: $PAYMENT_STATUS"
echo "Payment redirect location: $PAYMENT_LOCATION"

if [ "$PAYMENT_STATUS" = "302" ]; then
    if [[ "$PAYMENT_LOCATION" == *"sandbox"* ]] || [[ "$PAYMENT_LOCATION" == *"genie"* ]] || [[ "$PAYMENT_LOCATION" == *"payment"* ]]; then
        echo "✓ Successfully redirected to payment gateway"
    else
        echo "✗ Not redirected to payment gateway: $PAYMENT_LOCATION"
        exit 1
    fi
else
    echo "✗ Payment process failed"
    echo "Response body:"
    echo "$PAYMENT_RESPONSE" | sed 's/HTTPSTATUS:.*||LOCATION:.*//'
    exit 1
fi

echo ""
echo "Step 5: Check payment record after payment initiation..."

# Check payment table after payment initiation
PAYMENT_AFTER_INIT=$(curl -s "$BASE_URL/debug_payment_table.php")
echo "Payment records after payment initiation:"
echo "$PAYMENT_AFTER_INIT" | grep -A5 -B5 "Property ID: 1" || echo "No payment record for property 1"

echo ""
echo "Step 6: Simulate payment success..."

# Simulate payment success
SUCCESS_RESPONSE=$(curl -s -b "$COOKIES_FILE" -c "$COOKIES_FILE" \
    -w "HTTPSTATUS:%{http_code}||LOCATION:%{redirect_url}" \
    "$BASE_URL/plans/payment/success?transaction_id=TEST_SUCCESS_123")

SUCCESS_STATUS=$(echo "$SUCCESS_RESPONSE" | grep -o "HTTPSTATUS:[0-9]*" | cut -d: -f2)
SUCCESS_LOCATION=$(echo "$SUCCESS_RESPONSE" | grep -o "LOCATION:.*" | cut -d: -f2-)

echo "Success response status: $SUCCESS_STATUS"
echo "Success redirect location: $SUCCESS_LOCATION"

if [ "$SUCCESS_STATUS" = "302" ]; then
    if [[ "$SUCCESS_LOCATION" == *"/plans/activated"* ]]; then
        echo "✓ Successfully redirected to plans activated page"
    else
        echo "✗ Not redirected to activated page: $SUCCESS_LOCATION"
        exit 1
    fi
else
    echo "✗ Payment success failed"
    echo "Response body:"
    echo "$SUCCESS_RESPONSE" | sed 's/HTTPSTATUS:.*||LOCATION:.*//'
    exit 1
fi

echo ""
echo "Step 7: Check payment record after payment success..."

# Check payment table after success
PAYMENT_AFTER_SUCCESS=$(curl -s "$BASE_URL/debug_payment_table.php")
echo "Payment records after payment success:"
echo "$PAYMENT_AFTER_SUCCESS" | grep -A5 -B5 "Property ID: 1" || echo "No payment record for property 1"

echo ""
echo "Step 8: Check property plan update..."

PROPERTY_DATA=$(curl -s "$BASE_URL/check_property_data.php?property_id=1")
echo "Property data:"
echo "$PROPERTY_DATA"

echo ""
echo "=== TEST ANALYSIS ==="

# Check if payment record was created only on success
BEFORE_COUNT=$(echo "$PAYMENT_BEFORE" | grep -c "Property ID: 1" || echo "0")
AFTER_INIT_COUNT=$(echo "$PAYMENT_AFTER_INIT" | grep -c "Property ID: 1" || echo "0")
AFTER_SUCCESS_COUNT=$(echo "$PAYMENT_AFTER_SUCCESS" | grep -c "Property ID: 1" || echo "0")

echo "Payment record count:"
echo "  Before payment: $BEFORE_COUNT"
echo "  After payment initiation: $AFTER_INIT_COUNT"
echo "  After payment success: $AFTER_SUCCESS_COUNT"

if [ "$BEFORE_COUNT" = "0" ] && [ "$AFTER_INIT_COUNT" = "0" ] && [ "$AFTER_SUCCESS_COUNT" = "1" ]; then
    echo ""
    echo "✓ SUCCESS: Payment record was created only on payment success!"
    echo "✓ This is the correct behavior - no premature payment record creation"
else
    echo ""
    echo "✗ ISSUE: Payment record creation timing is not as expected"
    echo "  Expected: 0 -> 0 -> 1"
    echo "  Actual: $BEFORE_COUNT -> $AFTER_INIT_COUNT -> $AFTER_SUCCESS_COUNT"
fi

# Check property plan
if echo "$PROPERTY_DATA" | grep -q "Plan ID: 2"; then
    echo "✓ Property plan updated correctly to plan 2"
else
    echo "✗ Property plan not updated correctly"
fi

# Clean up
rm -f "$COOKIES_FILE"

echo ""
echo "=== TEST COMPLETED ==="
