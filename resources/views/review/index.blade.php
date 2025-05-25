@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">{{ $title }}</h1>

            @if($reviews->isEmpty())
            <div class="text-center py-8">
                <i class="fas fa-star text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-600">Anda belum memberikan review untuk produk apapun.</p>
                <a href="{{ route('order.history') }}" 
                   class="inline-block mt-4 text-blue-600 hover:text-blue-800">
                    Lihat Riwayat Pesanan
                </a>
            </div>
            @else
            <div class="grid gap-6">
                @foreach($reviews as $review)
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $review->product->image) }}" 
                                 alt="{{ $review->product->product_name }}"
                                 class="w-20 h-20 object-cover rounded-lg">
                        </div>
                        <div class="flex-grow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">{{ $review->product->product_name }}</h2>
                                    <div class="flex items-center space-x-1 text-yellow-400 mt-1">
                                        @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('review.edit', $review->id) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('review.destroy', $review->id) }}" 
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus review ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <p class="text-gray-600 mt-2">{{ $review->comment }}</p>
                            
                            @if($review->image)
                            <div class="mt-4">
                                <img src="{{ asset('storage/' . $review->image) }}" 
                                     alt="Review Image"
                                     class="max-w-xs rounded-lg">
                            </div>
                            @endif
                            
                            <div class="mt-4 text-sm text-gray-500">
                                {{ $review->created_at->diffForHumans() }}
                                @if($review->created_at != $review->updated_at)
                                • Diedit {{ $review->updated_at->diffForHumans() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 