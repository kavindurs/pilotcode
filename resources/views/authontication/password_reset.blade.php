<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Scoreness</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-white">
    <div class="min-h-screen bg-white py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-bold text-blue-900">Reset Password</h2>
                <p class="mt-2 text-sm text-gray-600">Enter your email to reset your password</p>
            </div>

            @if (session('status'))
                <div class="bg-green-50 text-green-600 p-4 rounded-md">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 text-red-500 p-4 rounded-md">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email"
                           name="email"
                           id="email"
                           required
                           class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm">
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Send Password Reset Link
                    </button>
                </div>

                <div class="text-center">
                    <a href="{{ route('login') }}"
                       class="text-sm text-blue-600 hover:text-blue-900">
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
