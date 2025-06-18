<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Scoreness</title>
    @vite('resources/css/app.css')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-white">
    <div class="min-h-screen bg-white py-12 px-4 sm:px-6 lg:px-8 flex items-center justify-center"
         x-data="{ isLogin: true }">
        <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
            {{-- Logo & Header --}}
            <div class="text-center">
                <div class="flex justify-center">
                    <div class="h-12 w-12 rounded-full bg-blue-600 flex items-center justify-center">
                        <span class="text-white text-xl font-bold">S</span>
                    </div>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-blue-900" x-text="isLogin ? 'Sign in to Scoreness' : 'Create Account'">
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    <span x-text="isLogin ? 'Don\'t have an account?' : 'Already have an account?'"></span>
                    <button type="button"
                            @click="isLogin = !isLogin"
                            class="ml-1 font-medium text-blue-600 hover:text-blue-900 transition-colors"
                            x-text="isLogin ? 'Sign up' : 'Sign in'">
                    </button>
                </p>
            </div>

            {{-- Form --}}
            <form method="POST"
                  :action="isLogin ? '{{ route('login') }}' : '{{ route('register') }}'"
                  class="mt-8 space-y-6"
                  enctype="multipart/form-data">
                @csrf

                {{-- Add error messages display --}}
                @if ($errors->any())
                    <div class="bg-red-50 text-red-500 p-4 rounded-md">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Registration Fields (Only shown when registering) --}}
                <template x-if="!isLogin">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   required
                                   class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600"
                                   placeholder="Enter your full name">
                        </div>

                        <div>
                            <label for="profile_picture" class="block text-sm font-medium text-gray-700">
                                Profile Picture
                            </label>
                            <input type="file"
                                   name="profile_picture"
                                   id="profile_picture"
                                   accept="image/*"
                                   class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm">
                            <p class="mt-1 text-sm text-gray-500">
                                Upload a profile picture (JPEG, PNG, JPG, max 2MB)
                            </p>
                        </div>
                    </div>
                </template>

                {{-- Common Fields --}}
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email') }}"
                               required
                               class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600"
                               placeholder="Enter your email">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password"
                               name="password"
                               id="password"
                               required
                               class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600"
                               placeholder="Enter your password">
                    </div>

                    <template x-if="!isLogin">
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input type="password"
                                   name="password_confirmation"
                                   id="password_confirmation"
                                   required
                                   class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-600 focus:border-blue-600"
                                   placeholder="Confirm your password">
                        </div>
                    </template>

                    <template x-if="isLogin">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox"
                                       name="remember"
                                       id="remember"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-600 border-gray-300 rounded">
                                <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                            </div>
                            <a href="{{ route('password.request') }}"
                               class="text-sm font-medium text-blue-600 hover:text-blue-900">
                                Forgot your password?
                            </a>
                        </div>
                    </template>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 transition-colors"
                        x-text="isLogin ? 'Sign in' : 'Create account'">
                </button>
            </form>



            {{-- Social Login Section --}}
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or continue with</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('login.google') }}"
                       class="w-full inline-flex justify-center items-center py-2.5 px-4 border border-gray-300 rounded-md shadow-sm bg-white hover:bg-gray-50 transition-colors">
                        <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span class="text-gray-700 font-medium">Continue with Google</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
