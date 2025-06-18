<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Your Password</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-center">Reset Your Password</h2>
        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('property.password.reset') }}" method="POST">
            @csrf
            <!-- Hidden fields to pass token and email -->
            <input type="hidden" name="business_email" value="{{ $email }}">
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-4">
                <label for="password" class="block text-gray-700">New Password</label>
                <input id="password" name="password" type="password" required
                       class="mt-1 block w-full p-2 border border-gray-300 rounded-md" placeholder="New Password">
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block text-gray-700">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="mt-1 block w-full p-2 border border-gray-300 rounded-md" placeholder="Confirm Password">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md">
                Reset Password
            </button>
        </form>
    </div>
</body>
</html>
