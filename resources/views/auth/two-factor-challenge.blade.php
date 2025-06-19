<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Two-Factor Authentication - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-blue-600 text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Two-Factor Authentication</h1>
                <p class="text-gray-600">Please enter the 6-digit code from your authenticator app to continue.</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('two-factor.verify') }}">
                @csrf

                <div class="mb-6">
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                        Authentication Code
                    </label>
                    <input type="text"
                           id="code"
                           name="code"
                           maxlength="6"
                           pattern="[0-9]{6}"
                           autocomplete="one-time-code"
                           class="block w-full px-4 py-3 text-center text-2xl font-mono border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('code') border-red-500 @enderror"
                           placeholder="000000"
                           required
                           autofocus>

                    @error('code')
                        <div class="mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-check mr-2"></i>
                    Verify Code
                </button>
            </form>

            <!-- Recovery Code Option -->
            <div class="mt-6 text-center">
                <button type="button"
                        onclick="toggleRecoveryForm()"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    <i class="fas fa-key mr-1"></i>
                    Use recovery code instead
                </button>
            </div>

            <!-- Recovery Code Form (Hidden by default) -->
            <form id="recovery-form" method="POST" action="{{ route('two-factor.verify') }}" class="hidden mt-6">
                @csrf

                <div class="mb-6">
                    <label for="recovery_code" class="block text-sm font-medium text-gray-700 mb-2">
                        Recovery Code
                    </label>
                    <input type="text"
                           id="recovery_code"
                           name="code"
                           maxlength="8"
                           class="block w-full px-4 py-3 text-center font-mono border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="XXXXXXXX">

                    <p class="mt-2 text-sm text-gray-500">
                        Enter one of your 8-character recovery codes.
                    </p>
                </div>

                <button type="submit"
                        class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-key mr-2"></i>
                    Use Recovery Code
                </button>

                <button type="button"
                        onclick="toggleRecoveryForm()"
                        class="w-full mt-3 bg-gray-200 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Authenticator
                </button>
            </form>

            <!-- Logout Option -->
            <div class="mt-8 text-center">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                        <i class="fas fa-sign-out-alt mr-1"></i>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleRecoveryForm() {
            const authForm = document.querySelector('form:not(#recovery-form):not([action*="logout"])');
            const recoveryForm = document.getElementById('recovery-form');

            if (recoveryForm.classList.contains('hidden')) {
                authForm.style.display = 'none';
                recoveryForm.classList.remove('hidden');
                document.getElementById('recovery_code').focus();
            } else {
                authForm.style.display = 'block';
                recoveryForm.classList.add('hidden');
                document.getElementById('code').focus();
            }
        }

        // Auto-submit when 6 digits are entered
        document.getElementById('code').addEventListener('input', function(e) {
            const value = e.target.value;
            if (value.length === 6 && /^\d{6}$/.test(value)) {
                // Add a small delay to show the complete code
                setTimeout(() => {
                    e.target.closest('form').submit();
                }, 100);
            }
        });
    </script>
</body>
</html>
