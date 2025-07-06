<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Payment Interface</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        input, select, button {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }

        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
            margin-top: 10px;
            transition: transform 0.2s;
        }

        button:hover {
            transform: translateY(-2px);
        }

        button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .quick-actions {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #f0f0f0;
        }

        .quick-actions h3 {
            margin-bottom: 15px;
            color: #333;
            text-align: center;
        }

        .quick-btn {
            margin-bottom: 10px;
            font-size: 14px;
            padding: 10px;
        }

        .success { background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); }
        .warning { background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); }
        .danger { background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); }

        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 10px;
            display: none;
        }

        .result.success {
            background: #d4edda;
            border: 2px solid #c3e6cb;
            color: #155724;
        }

        .result.error {
            background: #f8d7da;
            border: 2px solid #f5c6cb;
            color: #721c24;
        }

        .payment-url {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            word-break: break-all;
            font-family: monospace;
            font-size: 12px;
        }

        .loading {
            display: inline-block;
            animation: spin 1s linear infinite;
        }

        .countdown {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            color: #856404;
            margin-top: 15px;
            padding: 10px;
            text-align: center;
        }

        .countdown button {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 5px;
            font-size: 12px;
            width: auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔥 Test Payment Interface</h1>

        <form id="paymentForm">
            <div class="form-group">
                <label for="amount">Amount (USD)</label>
                <input type="number" id="amount" value="100" min="1" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="currency">Currency</label>
                <select id="currency">
                    <option value="USD" selected>USD - US Dollar</option>
                    <option value="USD">USD - US Dollar</option>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" value="Test Payment" required>
            </div>

            <div class="form-group">
                <label for="customer_name">Customer Name</label>
                <input type="text" id="customer_name" value="Test Customer" required>
            </div>

            <div class="form-group">
                <label for="customer_email">Customer Email</label>
                <input type="email" id="customer_email" value="test@example.com" required>
            </div>

            <button type="submit" id="createBtn">
                <span id="btnText">Create Payment</span>
                <span id="btnLoader" class="loading" style="display: none;">⏳</span>
            </button>
        </form>

        <div id="result" class="result"></div>

        <div class="quick-actions">
            <h3>Quick Actions</h3>
            <button type="button" class="quick-btn success" onclick="quickPayment(100)">
                Create USD 100 Payment
            </button>
            <button type="button" class="quick-btn warning" onclick="quickPayment(500)">
                Create USD 500 Payment
            </button>
            <button type="button" class="quick-btn danger" onclick="quickPayment(1000)">
                Create USD 1000 Payment
            </button>
        </div>
    </div>

    <script>
        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let redirectTimer = null;

        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            createPayment();
        });

        function createPayment() {
            const form = document.getElementById('paymentForm');
            const createBtn = document.getElementById('createBtn');
            const btnText = document.getElementById('btnText');
            const btnLoader = document.getElementById('btnLoader');
            const result = document.getElementById('result');

            // Clear any existing redirect timer
            if (redirectTimer) {
                clearTimeout(redirectTimer);
                redirectTimer = null;
            }

            // Show loading state
            createBtn.disabled = true;
            btnText.style.display = 'none';
            btnLoader.style.display = 'inline';
            result.style.display = 'none';

            // Collect form data
            const formData = {
                amount: document.getElementById('amount').value,
                currency: document.getElementById('currency').value,
                description: document.getElementById('description').value,
                customer_name: document.getElementById('customer_name').value,
                customer_email: document.getElementById('customer_email').value,
                _token: token
            };

            // Make API call
            fetch('/test-payment-create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {                // Reset loading state
                createBtn.disabled = false;
                btnText.style.display = 'inline';
                btnLoader.style.display = 'none';

                if (data.success) {
                    showResult('success', `
                        <strong>✅ Payment Created Successfully!</strong><br>
                        <strong>Payment ID:</strong> ${data.data.id}<br>
                        <strong>Amount:</strong> ${data.data.amount} cents (${formData.amount} ${formData.currency})<br>
                        <strong>Status:</strong> ${data.data.status}<br>
                        ${data.data.sandbox ? '<strong>Mode:</strong> 🏖️ Sandbox (Development)<br>' : ''}
                        <div class="payment-url">
                            <strong>Payment URL:</strong><br>
                            <a href="${data.data.payment_url}" target="_blank">${data.data.payment_url}</a>
                        </div>
                        <div id="countdown" class="countdown">
                            🚀 <strong>Redirecting to payment gateway in <span id="countdownTimer">5</span> seconds...</strong><br>
                            <small>You will be taken to the Genie Business payment page.</small><br>
                            <button onclick="cancelRedirect()">Cancel Redirect</button>
                        </div>
                    `);

                    // Start countdown and redirect
                    startCountdownRedirect(data.data.payment_url, 5);
                } else {
                    showResult('error', `
                        <strong>❌ Payment Creation Failed</strong><br>
                        Error: ${data.error}
                    `);
                }
            })
            .catch(error => {
                // Reset loading state
                createBtn.disabled = false;
                btnText.style.display = 'inline';
                btnLoader.style.display = 'none';

                showResult('error', `
                    <strong>❌ Network Error</strong><br>
                    ${error.message}
                `);
            });
        }

        function startCountdownRedirect(paymentUrl, seconds) {
            let countdown = seconds;
            const countdownElement = document.getElementById('countdownTimer');

            const updateCountdown = () => {
                if (countdownElement) {
                    countdownElement.textContent = countdown;
                }

                if (countdown <= 0) {
                    window.location.href = paymentUrl;
                    return;
                }

                countdown--;
                redirectTimer = setTimeout(updateCountdown, 1000);
            };

            updateCountdown();
        }

        function cancelRedirect() {
            if (redirectTimer) {
                clearTimeout(redirectTimer);
                redirectTimer = null;
            }

            const countdownElement = document.getElementById('countdown');
            if (countdownElement) {
                countdownElement.innerHTML = `
                    <strong>✋ Redirect Cancelled</strong><br>
                    <small>You can manually click the payment URL above to proceed.</small>
                `;
            }
        }

        function showResult(type, message) {
            const result = document.getElementById('result');
            result.className = `result ${type}`;
            result.innerHTML = message;
            result.style.display = 'block';
        }

        function quickPayment(amount) {
            document.getElementById('amount').value = amount;
            document.getElementById('description').value = `Test Payment - USD ${amount}`;
            createPayment();
        }

        // Auto-focus on amount field
        document.getElementById('amount').focus();
    </script>
</body>
</html>
