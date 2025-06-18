<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout Payment</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Include the PayHere JavaScript library -->
  <script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="max-w-2xl w-full bg-white shadow-lg rounded-lg p-8">
    <h1 class="text-3xl font-bold mb-4 text-center">Checkout for Plan: {{ $plan->name }}</h1>
    <p class="text-xl text-center mb-6">Total Amount (Annual): USD {{ number_format($total, 2) }}</p>

    <div class="text-center">
      <button id="payhere-payment" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-full transition duration-300">
        PayNow
      </button>
    </div>
  </div>

  <script>
      // Prepare the payment details from server-passed variables.
      var payment = {
          "sandbox": true, // Change to false when in production.
          "merchant_id": "1226143", // Your PayHere Merchant ID.
          "return_url": "{{ $return_url }}",   // URL to redirect on success.
          "cancel_url": "{{ $cancel_url }}",   // URL to redirect on cancel.
          "notify_url": "{{ $notify_url }}",   // URL for server-to-server payment notification.
          "order_id": "{{ $orderId }}",          // Unique order id generated on the server.
          "items": "{{ $plan->name }} Plan",      // Description or name of the plan.
          "amount": "{{ number_format($total, 2, '.', '') }}",  // Total amount in decimal format.
          "currency": "{{ $currency }}",         // Currency code, e.g. USD.
          "hash": "{{ $hash }}",                 // Generated hash from server.
          // Optional: Customer details for a better user experience.
          "first_name": "{{ auth()->user()->first_name ?? 'FirstName' }}",
          "last_name": "{{ auth()->user()->last_name ?? 'LastName' }}",
          "email": "{{ $businessEmail }}",
          "phone": "{{ auth()->user()->phone ?? '0770000000' }}",
          "address": "",
          "city": "",
          "country": ""
      };

      // When the button is clicked, start the PayHere payment process.
      document.getElementById('payhere-payment').addEventListener('click', function () {
          payhere.startPayment(payment);
      });

      // Redirect using the named route (plans.index) on success or cancellation.
      payhere.onCompleted = function(orderId) {
          console.log("Payment completed. OrderID:" + orderId);
          window.location.href = "{{ route('plans.index') }}";
      };

      payhere.onDismissed = function() {
          console.log("Payment dismissed by user");
          window.location.href = "{{ route('plans.index') }}";
      };

      payhere.onError = function(error) {
          console.log("Error: " + error);
          // Optionally, handle errors or show an error message to the user.
      };
  </script>
</body>
</html>

