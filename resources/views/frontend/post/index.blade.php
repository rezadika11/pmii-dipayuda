@extends('frontend.layouts.main')
@section('title', $post->title)
@section('content')
    <main  class="flex-grow container mx-auto md:max-w-screen-md lg:max-w-screen-xl">
        <!-- Breadcrumb -->
        {{-- <nav class="flex items-center pt-6 sm:px-0 px-4 overflow-hidden relative" aria-label="Breadcrumb">
        <ol class="flex items-center space-x-1 font-poppins">
            <!-- Link Beranda -->
            <li>
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-700">
                Home
            </a>
            </li>
            <!-- Separator -->
            <li>
            <span class="mx-2 text-gray-500">/</span>
            </li>
            <!-- Halaman Saat Ini -->
            <li>
            <span class="text-gray-500">
               {{ $post->title }}
            </span>
            </li>
        </ol>
        </nav> --}}

      <!--content-->
      <div class="container mx-auto py-8 sm:px-0 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Kolom Kiri: Detail Post -->
            <div class="lg:col-span-2">
                @if($post)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <!-- Gambar Post -->
                        <div class="overflow-hidden">
                            <img
                                src="{{ asset('storage/' . $post->image) }}"
                                alt="{{ $post->title }}"
                                class="w-full h-80 md:h-[35em] object-cover transition-transform duration-500 group-hover:scale-105"
                            >
                        </div>

                        <!-- Informasi Post -->
                        <div class="p-6">
                            <h1 class="text-2xl md:text-3xl font-bold mb-4 font-inter">{{ $post->title }}</h1>
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <time datetime="{{ \Carbon\Carbon::parse($post->published)->translatedFormat('Y-m-d') }}">
                                    {{ \Carbon\Carbon::parse($post->published)->translatedFormat('d F Y') }}
                                </time>
                                <span class="mx-2">|</span>
                                <span class="font-medium">
                                    {{-- Pastikan relasi author atau field penulis sudah ada --}}
                                    {{ $post->users->name ?? 'Admin' }}
                                </span>
                                <span class="mx-2">|</span>
                                <span class="font-medium">
                                    {{-- Pastikan relasi kategori sudah ada --}}
                                    <a href="{{ route('postCategory', $post->category->name) }}" class="text-blue-600 hover:text-blue-700">{{ $post->category->name ?? 'Uncategorized' }}</a>
                                </span>
                            </div>

                            <!-- Konten Post dengan Tailwind Typography -->
                            <div class="prose max-w-none font-poppins">
                                {!! $post->content !!}
                            </div>
                             <!-- Tags -->
                            @if($post->tags->count())
                                <div class="mt-6">
                                    {{-- <h5 class="font-semibold mb-2">Tags:</h5> --}}
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($post->tags as $tag)
                                            <a href="#" class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs hover:bg-gray-300">
                                                {{ $tag->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Placeholder jika tidak ada data -->
                    <div class="flex items-center justify-center h-full bg-gray-200 text-gray-500">
                        <p>Tidak ada berita terbaru saat ini.</p>
                    </div>
                @endif
            </div>


            <!-- Kolom Kanan: Postingan Terbaru -->
             <div class="lg:col-span-1">
                <!-- Artikel Terbaru -->
                <div class="mb-6">
                    <h3 class="text-xl font-inter font-semibold mb-4 relative pb-2">
                        Postingan Terbaru
                        <div class="absolute left-0 right-0 bottom-0 flex">
                        <hr class="w-24 h-[1px] bg-blue-600 border-0" />
                        <hr class="flex-1 h-[1px] bg-gray-300 border-0" />
                        </div>
                    </h3>
                    <div class="space-y-7">
                        @foreach($postsPopuler as $post)
                            <article class="flex space-x-4">
                                <img
                                    src="{{ asset('storage/' . $post->image) }}"
                                    alt="{{ $post->title }}"
                                    class="w-20 h-16 object-cover"
                                />
                                <div class="flex flex-col justify-start">
                                    <h4 class="text-sm font-poppins font-medium text-gray-800 hover:text-blue-600">
                                        <a href="{{ route('detailPost',$post->slug) }}" class="line-clamp-2">
                                            {{ $post->title }}
                                        </a>
                                    </h4>
                                    <time class="text-xs text-gray-500" datetime="{{ \Carbon\Carbon::parse($post->published)->translatedFormat('Y-m-d') }}">
                                        {{ \Carbon\Carbon::parse($post->published)->translatedFormat('Y-m-d') }}
                                    </time>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
      </div>
    </main>
@endsection