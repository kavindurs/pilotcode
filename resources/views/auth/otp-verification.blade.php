<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Email Verification - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope text-blue-600 text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Verify Your Email</h1>
                <p class="text-gray-600">We've sent a 6-digit verification code to:</p>
                <p class="text-blue-600 font-medium">{{ $email }}</p>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <p class="text-green-700 text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center mb-1">
                            <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                            <p class="text-red-700 text-sm">{{ $error }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- OTP Form -->
            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf

                <div class="mb-6">
                    <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">
                        Enter Verification Code
                    </label>
                    <input type="text"
                           id="otp"
                           name="otp"
                           maxlength="6"
                           pattern="[0-9]{6}"
                           autocomplete="one-time-code"
                           class="block w-full px-4 py-3 text-center text-2xl font-mono border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('otp') border-red-500 @enderror"
                           placeholder="000000"
                           required
                           autofocus>

                    @error('otp')
                        <div class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-check mr-2"></i>
                    Verify Email
                </button>
            </form>

            <!-- Resend Option -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 mb-3">Didn't receive the code?</p>

                @if ($can_resend)
                    <form method="POST" action="{{ route('otp.resend') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            <i class="fas fa-paper-plane mr-1"></i>
                            Resend Code
                        </button>
                    </form>
                @else
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        Please wait before requesting a new code
                    </p>
                @endif
            </div>

            <!-- Back to Registration -->
            <div class="mt-8 text-center">
                <a href="{{ route('register.show') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Back to Registration
                </a>
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                <div>
                    <h3 class="text-sm font-medium text-blue-800 mb-1">Tips:</h3>
                    <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                        <li>Check your spam/junk folder if you don't see the email</li>
                        <li>The verification code expires in 10 minutes</li>
                        <li>Enter the 6-digit code exactly as received</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit when 6 digits are entered
        document.getElementById('otp').addEventListener('input', function(e) {
            const value = e.target.value;
            if (value.length === 6 && /^\d{6}$/.test(value)) {
                // Add a small delay to show the complete code
                setTimeout(() => {
                    e.target.closest('form').submit();
                }, 100);
            }
        });

        // Auto-focus and select all on page load
        window.addEventListener('load', function() {
            const otpInput = document.getElementById('otp');
            otpInput.focus();
            otpInput.select();
        });
    </script>
</body>
</html>
