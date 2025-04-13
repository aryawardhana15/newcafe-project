<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} Page - GAFH</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-[#f4e6d4] to-[#d4a037]">
    <div class="min-h-screen flex items-center justify-center p-4 animate-fade-in">
        <!-- Card Container -->
        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-500 hover:scale-105">
            <div class="p-8">
                <!-- Header -->
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $title }} Page</h1>
                    <p class="text-gray-600">Create your account to get started.</p>
                </div>

                <!-- Session Message -->
                @if(session()->has('message'))
                <div class="mt-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg">
                    {!! session("message") !!}
                </div>
                @endif

                <!-- Form -->
                <form class="mt-6" method="post" action="/auth/register">
                    @csrf
                    <!-- Full Name Input -->
                    <div class="mb-6">
                        <label for="fullname" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="fullname" name="fullname"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#d4a037] focus:border-[#d4a037] transition-all @error('fullname') border-red-500 @enderror"
                            placeholder="Enter your full name" value="{{ @old('fullname') }}">
                        @error('fullname')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username Input -->
                    <div class="mb-6">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" id="username" name="username"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#d4a037] focus:border-[#d4a037] transition-all @error('username') border-red-500 @enderror"
                            placeholder="Enter your username" value="{{ @old('username') }}">
                        @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Input -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="text" id="email" name="email"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#d4a037] focus:border-[#d4a037] transition-all @error('email') border-red-500 @enderror"
                            placeholder="Enter your email" value="{{ @old('email') }}">
                        @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Input -->
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#d4a037] focus:border-[#d4a037] transition-all @error('password') border-red-500 @enderror"
                            placeholder="Enter your password">
                        @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation Input -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#d4a037] focus:border-[#d4a037] transition-all @error('password_confirmation') border-red-500 @enderror"
                            placeholder="Confirm your password">
                        @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                        <div class="mt-2 space-y-2">
                            <div class="flex items-center">
                                <input type="radio" id="male" name="gender" value="M" class="form-radio h-4 w-4 text-[#d4a037] focus:ring-[#d4a037] border-gray-300"
                                    {{ old('gender') == 'M' ? 'checked' : '' }}>
                                <label for="male" class="ml-2 text-sm text-gray-700">Male</label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="female" name="gender" value="F" class="form-radio h-4 w-4 text-[#d4a037] focus:ring-[#d4a037] border-gray-300"
                                    {{ old('gender') == 'F' ? 'checked' : '' }}>
                                <label for="female" class="ml-2 text-sm text-gray-700">Female</label>
                            </div>
                        </div>
                        @error('gender')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Input -->
                    <div class="mb-6">
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" id="phone" name="phone"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#d4a037] focus:border-[#d4a037] transition-all @error('phone') border-red-500 @enderror"
                            placeholder="Enter your phone number" value="{{ @old('phone') }}">
                        @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address Input -->
                    <div class="mb-6">
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <input type="text" id="address" name="address"
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-[#d4a037] focus:border-[#d4a037] transition-all @error('address') border-red-500 @enderror"
                            placeholder="Enter your address" value="{{ @old('address') }}">
                        @error('address')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-[#d4a037] text-white py-2 px-4 rounded-lg font-semibold hover:bg-[#b8862f] transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#d4a037] focus:ring-offset-2">
                        Submit
                    </button>
                </form>

                <!-- Divider -->
                <hr class="my-6 border-gray-300">

                <!-- Login Link -->
                <div class="text-center">
                    <a href="/auth/login" class="text-sm text-[#d4a037] hover:text-[#b8862f] font-semibold">
                        Already have an account? Login now!
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>