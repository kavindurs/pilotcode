<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Property Password</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold mb-4 text-center">Reset Your Password</h2>
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('property.password.email') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="business_email" class="block text-gray-700">Business Email</label>
                <input type="email" name="business_email" id="business_email" required
                       class="mt-1 block w-full p-2 border border-gray-300 rounded-md" placeholder="Your email">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-md">
                Send Reset Link
            </button>
        </form>
    </div>
</body>
</html>
