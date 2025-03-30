@extends('/layouts/main')

@push('css-dependencies')
<link href="/css/profile.css" rel="stylesheet" />
@endpush

@push('scripts-dependencies')
<script src="/js/profile.js" type="module"></script>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">

    @include('/partials/breadcumb')

    <!-- Notification -->
    @if(session()->has('message'))
    <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
        {!! session("message") !!}
    </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Profile Picture Card -->
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Profile Picture</h3>
                </div>
                <div class="p-6 text-center">
                    <img class="w-40 h-40 rounded-full mx-auto mb-4 object-cover border-4 border-white shadow-md" 
                         id="image-preview" 
                         src="{{ asset('storage/' . auth()->user()->image) }}" 
                         alt="Profile picture">
                    <p class="text-sm text-gray-500 mb-4">Must be an image no more than 2 MB</p>
                    
                    <form method="post" action="/profile/edit_profile/{{ auth()->user()->id }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="oldImage" value="{{ auth()->user()->image }}">
                        <div class="relative">
                            <input type="file" 
                                   class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-md file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100
                                          @error('image') border-red-500 @enderror" 
                                   id="image" 
                                   name="image">
                            @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                </div>
            </div>
        </div>

        <!-- Profile Details Card -->
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Profile Details</h3>
                </div>
                <div class="p-6">
                    <!-- Email -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="email">Email</label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100" 
                               id="email" 
                               name="email" 
                               type="text" 
                               value="{{ auth()->user()->email }}" 
                               readonly>
                    </div>

                    <!-- Username -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="username">
                            Username <span class="text-red-500">will show as your identity in the website</span>
                        </label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('username') border-red-500 @enderror" 
                               id="username" 
                               name="username" 
                               type="text" 
                               value="{{ auth()->user()->username }}">
                        @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Full Name -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="fullname">Full Name</label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('fullname') border-red-500 @enderror" 
                               id="fullname" 
                               name="fullname" 
                               type="text" 
                               value="{{ auth()->user()->fullname }}">
                        @error('fullname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone and Gender -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="phone">No. HP</label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   type="text" 
                                   value="{{ auth()->user()->phone }}">
                            @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1" for="gender">Gender</label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100" 
                                   id="gender" 
                                   name="gender" 
                                   type="text" 
                                   value="{{ auth()->user()->gender == 'M' ? 'Male' : 'Female' }}" 
                                   readonly>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1" for="address">Address</label>
                        <input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror" 
                               id="address" 
                               name="address" 
                               type="text" 
                               value="{{ auth()->user()->address }}">
                        @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center gap-3">
                        <a href="/profile/my_profile" 
                           class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Back
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Changes
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        50: '#eff6ff',
                        100: '#dbeafe',
                        200: '#bfdbfe',
                        300: '#93c5fd',
                        400: '#60a5fa',
                        500: '#3b82f6',
                        600: '#2563eb',
                        700: '#1d4ed8',
                        800: '#1e40af',
                        900: '#1e3a8a',
                    }
                }
            }
        }
    }
</script>
@endsection