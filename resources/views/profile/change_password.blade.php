@extends('/layouts/main')

@push('css-dependencies')
<link href="/css/profile.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">

    <!-- Breadcrumb (tetap sama) -->
    @include('/partials/breadcumb')

    <!-- Notifikasi (fungsi tetap sama, hanya styling yang diupdate) -->
    <div class="max-w-4xl mx-auto mb-6">
        @if(session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
            {!! session("message") !!}
        </div>
        @endif
    </div>

    <!-- Panel Utama (semua fungsi tetap ada) -->
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
        <div class="md:flex">
            <!-- Bagian Foto Profil (fungsi tetap sama) -->
            <div class="md:w-1/3 p-6 flex flex-col items-center border-b md:border-b-0 md:border-r border-gray-200 bg-gray-50">
                <img alt="gambar profile user" 
                     class="w-40 h-40 object-cover rounded-full border-4 border-white shadow-md" 
                     src="{{ asset('storage/' . auth()->user()->image) }}">
            </div>

            <!-- Form Ganti Password (SEMUA FUNGSI TETAP ADA) -->
            <div class="md:w-2/3 p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Ganti Password</h2>
                
                <!-- Form yang sama persis seperti aslinya, hanya styling yang diubah -->
                <form action="/profile/change_password" method="post">
                    @csrf
                    
                    <!-- Current Password -->
                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password Saat Ini
                        </label>
                        <input type="password" name="current_password" id="current_password"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('current_password') border-red-500 @enderror">
                        @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            Password Baru
                        </label>
                        <input type="password" name="password" id="password"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror">
                        @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            Ulangi Password Baru
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tombol Submit -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Ganti Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tailwind CSS CDN -->
<script src="https://cdn.tailwindcss.com"></script>
@endsection