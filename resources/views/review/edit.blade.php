@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ $title }}</h1>

            <div class="mb-6">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        <img src="{{ asset('storage/' . $review->product->image) }}" 
                             alt="{{ $review->product->product_name }}"
                             class="w-20 h-20 object-cover rounded-lg">
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ $review->product->product_name }}</h2>
                        <p class="text-gray-600">Order #{{ $review->order_id }}</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('review.update', $review->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Rating</label>
                    <div class="flex items-center space-x-2">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" {{ old('rating', $review->rating) == $i ? 'checked' : '' }}>
                            <i class="fas fa-star text-2xl peer-checked:text-yellow-400 text-gray-300 hover:text-yellow-400 transition-colors"></i>
                        </label>
                        @endfor
                    </div>
                    @error('rating')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="comment" class="block text-gray-700 text-sm font-bold mb-2">Komentar</label>
                    <textarea id="comment" 
                              name="comment" 
                              rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Bagaimana pengalaman Anda dengan produk ini?">{{ old('comment', $review->comment) }}</textarea>
                    @error('comment')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="image" class="block text-gray-700 text-sm font-bold mb-2">Foto (Opsional)</label>
                    @if($review->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $review->image) }}" 
                             alt="Current Review Image"
                             class="w-32 h-32 object-cover rounded-lg">
                    </div>
                    @endif
                    <input type="file" 
                           id="image" 
                           name="image" 
                           accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-gray-500 text-xs mt-1">Format: JPG, PNG. Maksimal 2MB</p>
                    @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2">
                    <a href="{{ route('review.index') }}" 
                       class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 