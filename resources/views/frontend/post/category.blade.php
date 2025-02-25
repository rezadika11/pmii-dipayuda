@extends('frontend.layouts.main')
@section('title', $category->name)
@section('content')
    <main  class="flex-grow container mx-auto md:max-w-screen-md lg:max-w-screen-xl">
      <div class="container mx-auto py-6 sm:px-0 px-4">
        <h3 class="text-xl font-semibold mb-4 relative pb-2">
            {{ ucwords($category->name) }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <!-- Card 1 -->
             @if($posts->isNotEmpty())
             @foreach ($posts as $item)
                  <div class="bg-white shadow rounded overflow-hidden">
                    <img
                        src="{{ asset('storage/' . $item->image) }}"
                        alt="{{ $item->title }}"
                        class="w-full h-64 object-cover"
                    />
                    <div class="p-4">
                        <h2 class="text-2xl font-bold text-gray-800 mb-3 font-inter">
                        <a href="{{ route('detailPost',$item->slug) }}" class="hover:text-blue-500"> {{ Str::limit($item->title, 60, '...') }}</a>
                        </h2>
                        <p class="text-gray-600 mb-2">
                        By <strong>{{ $item->users->name ?? 'Admin' }}</strong> -    {{ \Carbon\Carbon::parse($item->published)->translatedFormat('d F Y') }}
                        </p>
                        <p class="text-gray-600 mb-4 font-poppins">
                            {{ Str::limit($item->excerpt, 120, '...') }}
                        </p>
                        <a
                        href="{{ route('detailPost',$item->slug) }}"
                        class="inline-block px-2 py-2.5 border border-gray-400 text-gray-800 hover:bg-blue-500 hover:text-white transition"
                        >
                        Baca Selengkapnya
                        </a>
                    </div>
                </div>
             @endforeach
               
            @else
                <p class="text-gray-500">Tidak ada kategori.</p>
            @endif
        </div>
      </div>
    </main>
@endsection