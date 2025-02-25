@extends('frontend.layouts.main')
@section('title','Home')
@section('content')
    <main  class="flex-grow container mx-auto md:max-w-screen-md lg:max-w-screen-xl">
      <!-- News Ticker -->
      <div class="flex items-center pt-6 sm:px-0 px-4 overflow-hidden relative">
          <div class="bg-blue-600 text-white px-3 py-1 mr-4 flex items-center space-x-1">
              <i class="bi bi-fire"></i>
              <span>Terkini</span>
          </div>
          <div class="news-ticker-container w-full overflow-hidden relative h-6">
              <div class="news-ticker-content absolute w-full">
                  <!-- Berita akan dimuat di sini -->
                  @if($newsItems->isNotEmpty())
                      @foreach($newsItems as $index => $item)
                          <a href="{{ route('detailPost', $item->slug) }}" class="text-black hover:underline hover:text-yellow-500 block" style="display: none;">
                              {{ $item->title }}
                          </a>
                      @endforeach
                  @else
                      <p class="text-gray-500">Tidak ada berita terkini saat ini.</p>
                  @endif
              </div>
          </div>
          <div class="ml-4 flex space-x-2">
              <button class="news-prev bg-gray-200 p-2 rounded">
                  <i class="bi bi-chevron-left text-blue-600"></i>
              </button>
              <button class="news-next bg-gray-200 p-2 rounded">
                  <i class="bi bi-chevron-right text-blue-600"></i>
              </button>
          </div>
      </div>

      <!--content-->
      <div data-aos="fade-up" data-aos-duration="1000" class="container mx-auto py-8 sm:px-0 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Kolom Kiri: Hero dan Berita Utama -->
          <div class="lg:col-span-2">
            <!-- Hero Image Statis -->
            <div class="relative overflow-hidden group h-[400px] md:h-[500px]">
              @if($post)
                <img
                  src="{{ asset('storage/' . $post->image) }}"
                  alt="{{ $post->title }}"
                  class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                />
                <div class="absolute inset-0 bg-black/50 flex items-end p-4 md:p-8">
                  <div class="text-white">
                    <div class="flex items-center mb-3">
                      <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-xs mr-3">
                        Terkini
                      </span>
                      <time class="text-sm" datetime="{{ \Carbon\Carbon::parse($post->published)->translatedFormat('Y-m-d') }}">
                        {{ \Carbon\Carbon::parse($post->published)->translatedFormat('d F Y') }}
                      </time>
                    </div>
                    <h2 class="text-2xl font-inter md:text-3xl font-bold mb-2">
                      {{ $post->title }}
                    </h2>
                    <p class="text-xs md:text-sm opacity-80 max-w-xl mb-4">
                      {{ Str::limit($post->excerpt, 100, '...') }}
                    </p>
                    <a
                      href="{{ route('detailPost', $post->slug) }}"
                      class="inline-block bg-blue-600 px-4 py-2 rounded-full text-xs md:text-sm hover:bg-blue-700 transition"
                    >
                      Baca Selengkapnya
                    </a>
                  </div>
                </div>
              @else
                <!-- Placeholder jika tidak ada data -->
                <div class="flex items-center justify-center h-full bg-gray-200 text-gray-500">
                  <p>Tidak ada berita terbaru saat ini.</p>
                </div>
              @endif
            </div>
          </div>

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
                                <a href="{{ route('detailPost', $post->slug) }}" class="line-clamp-2">
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

      <!--Berita Terbaru-->
      <div data-aos="fade-up" data-aos-duration="800" class="container mx-auto py-4 sm:px-0 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
          <!-- Berita Terbaru -->
          <div class="lg:col-span-2">
            <h3 class="text-xl font-semibold mb-4 relative pb-2">
              Berita
              <div class="absolute left-0 right-0 bottom-0 flex">
                <hr class="w-24 h-[1px] bg-yellow-500 border-0" />
                <hr class="flex-1 h-[1px] bg-gray-300 border-0" />
              </div>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
               @if($latestNews->isNotEmpty())
                <div class="overflow-hidden">
                  <img
                    src="{{ asset('storage/' . $latestNews->first()->image) }}"
                    alt="{{ $latestNews->first()->title }}"
                    class="w-full h-64 object-cover"
                  />
                  <div class="mt-3">
                    <h2 class="text-2xl font-inter font-bold text-gray-800 mb-3">
                        <a href="{{ route('detailPost', $latestNews->first()->slug) }}" class="hover:text-blue-500">{{ $latestNews->first()->title }}</a>
                    </h2>
                    <p class="text-gray-600 mb-2">By {{ $latestNews->first()->users->name ?? 'Admin' }} - {{ \Carbon\Carbon::parse($latestNews->first()->published)->translatedFormat('d F Y') }}</p>
                    <p class="text-gray-600 mb-4 font-poppins">
                       {{ Str::limit($latestNews->first()->excerpt, 120, '...') }}
                    </p>
                    <a
                      href="{{ route('detailPost',$latestNews->first()->slug) }}"
                      class="inline-block px-2 py-2.5 border border-gray-400 text-gray-800 hover:bg-blue-500 hover:text-white transition"
                    >
                      Baca Selengkapnya
                    </a>
                  </div>
                </div>

                <div class="space-y-7">
                  <!-- Artikel-artikel -->
                   @foreach($latestNews->skip(1) as $news)
                      <article class="flex space-x-4">
                          <img
                              src="{{ asset('storage/' . $news->image) }}"
                              alt="{{ $news->title }}"
                              class="w-28 h-16 object-cover"
                          />
                          <div class="flex flex-col justify-start">
                              <h4 class="text-sm font-inter font-medium text-gray-800 hover:text-blue-600">
                                  <a href="{{ route('detailPost', $news->slug) }}" class="line-clamp-2 font-inter">
                                      {{ $news->title }}
                                  </a>
                              </h4>
                              <time class="text-xs text-gray-500" datetime="{{ $news->published }}">
                                  By {{ $news->users->name ?? 'Admin' }} - 
                                  {{ \Carbon\Carbon::parse($news->published)->translatedFormat('d F Y') }}
                              </time>
                          </div>
                      </article>
                    @endforeach
                </div>
              @else
                <p class="text-gray-500">Tidak ada berita terbaru saat ini.</p>
              @endif
            </div>
          </div>

          <!-- Sosial Media -->
          <div class="lg:col-span-1">
            <h3 class="text-xl font-semibold mb-4 relative pb-2">
              Sosial Media
              <div class="absolute left-0 right-0 bottom-0 flex">
                <hr class="w-24 h-[1px] bg-yellow-500 border-0" />
                <hr class="flex-1 h-[1px] bg-gray-300 border-0" />
              </div>
            </h3>
            <div class="space-y-4">
              <a
                href="#"
                class="block bg-blue-600 text-white px-4 py-3 rounded-lg flex items-center justify-between hover:bg-blue-700 transition"
              >
                <div class="flex items-center">
                  <i class="bi bi-facebook mr-3"></i> <span>Facebook</span>
                </div>
                <span>15000 Followers</span>
              </a>
              <a
                href="#"
                class="block bg-pink-600 text-white px-4 py-3 rounded-lg flex items-center justify-between hover:bg-pink-700 transition"
              >
                <div class="flex items-center">
                  <i class="bi bi-instagram mr-3"></i>
                  Instagram
                </div>
                <span>16000 Followers</span>
              </a>
              <a
                href="#"
                class="block bg-red-600 text-white px-4 py-3 rounded-lg flex items-center justify-between hover:bg-red-700 transition"
              >
                <div class="flex items-center">
                  <i class="bi bi-youtube mr-3"></i>
                  YouTube
                </div>
                <span>15000 Subscribe</span>
              </a>
            </div>
            <div class="py-5">
              <h3 class="text-xl font-semibold mb-4 relative pb-2">
                Kategori
                <div class="absolute left-0 right-0 bottom-0 flex">
                  <hr class="w-24 h-[1px] bg-yellow-500 border-0" />
                  <hr class="flex-1 h-[1px] bg-gray-300 border-0" />
                </div>
              </h3>
              <ul class="space-y-3">
                @foreach ($kategoriList as $kategori)
                    <li class="flex justify-between items-center">
                        <a href="{{ route('postCategory', $kategori->slug) }}" class="text-gray-700 hover:text-blue-500">
                            {{ $kategori->name }}
                        </a>
                        @php
                            $color = $kategori->posts_count > 50 ? 'bg-green-500' : 'bg-red-500';
                        @endphp
                        <span class="{{ $color }} text-white px-3 py-1 rounded-full text-xs">
                            {{ $kategori->posts_count }}
                        </span>
                    </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!--Berita Opini-->
      <div data-aos="fade-up" data-aos-duration="800" class="container mx-auto py-4 sm:px-0 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
          <!-- Berita Terbaru -->
          <div class="lg:col-span-2">
            <h3 class="text-xl font-semibold mb-4 relative pb-2">
              Opini
              <div class="absolute left-0 right-0 bottom-0 flex">
                <hr class="w-24 h-[1px] bg-yellow-500 border-0" />
                <hr class="flex-1 h-[1px] bg-gray-300 border-0" />
              </div>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              @if($latestOpini->isNotEmpty())
                @foreach ($latestOpini as $opini)
                  <div class="overflow-hidden">
                    <img
                       src="{{ asset('storage/' . $opini->image) }}"
                        alt="{{ $opini->title }}"
                        class="w-full h-64 object-cover"
                    />
                    <div class="mt-3">
                      <h2 class="text-2xl font-inter font-bold text-gray-800 mb-3">
                         <a href="{{ route('detailPost',$opini->slug) }}" class="line-clamp-2 hover:text-blue-500">
                            {{ Str::limit($opini->title, 70, '...') }}
                          </a>
                      </h2>
                      <p class="text-gray-600 mb-2">  By {{ $opini->users->name ?? 'Admin' }} - 
                         {{ \Carbon\Carbon::parse($opini->published)->translatedFormat('d F Y') }}</p>
                      <p class="text-gray-600 mb-4 font-poppins">
                          {{ Str::limit($opini->excerpt, 120, '...') }}
                      </p>
                       <a
                      href="{{ route('detailPost',$opini->slug) }}"
                      class="inline-block px-2 py-2.5 border border-gray-400 text-gray-800 hover:bg-blue-500 hover:text-white transition"
                    >
                      Baca Selengkapnya
                    </a>
                    </div>
                  </div>
                @endforeach
              @else
              <p class="text-gray-500">Tidak ada berita opini terbaru saat ini.</p>
              @endif
            </div>
          </div>
        </div>
      </div>

      <!---Banner Iklan-->
      <div
        class="flex-grow container mx-auto md:max-w-screen-md lg:max-w-screen-xl px-24 py-5"
      >
        <div class="w-full overflow-hidden">
          <img
            src="/img/iklan.png"
            alt="Banner Iklan Panjang"
            class="w-full h-16 md:h-20 lg:h-32 object-cover object-center"
          />
        </div>
      </div>

      <!---Artikel-->
      <div data-aos="fade-up" data-aos-duration="800" class="container mx-auto py-4 sm:px-0 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
          <!-- Berita Terbaru -->
          <div class="lg:col-span-2">
            <h3 class="text-xl font-semibold mb-4 relative pb-2">
              Artikel
              <div class="absolute left-0 right-0 bottom-0 flex">
                <hr class="w-24 h-[1px] bg-yellow-500 border-0" />
                <hr class="flex-1 h-[1px] bg-gray-300 border-0" />
              </div>
            </h3>
            <div class="space-y-7">
              @if ($latestArtikel->isNotEmpty())
                @foreach ($latestArtikel as $artikel)
                <article class="flex space-x-6 transition-shadow duration-300">
                  <img
                     src="{{ asset('storage/' . $artikel->image) }}"
                      alt="{{ $artikel->title }}"
                    class="w-64 h-48 object-cover"
                  />
                  <div class="flex flex-col justify-start">
                    <h2
                      class="text-2xl font-bold text-gray-800 hover:text-blue-500 font-inter mb-2 leading-tight"
                    >
                      <a href="{{ route('detailPost',$artikel->slug) }}" class="line-clamp-2">
                        {{ Str::limit($artikel->title, 70, '...') }}
                      </a>
                    </h2>

                    <div class="flex items-center mb-3">
                      <span class="text-sm text-gray-600 mr-3">
                        <strong>{{ $artikel->users->name ?? 'Admin' }}</strong>
                      </span>
                      <time class="text-sm text-gray-500" datetime="{{ \Carbon\Carbon::parse($artikel->published)->translatedFormat('d F Y') }}">
                          {{ \Carbon\Carbon::parse($artikel->published)->translatedFormat('d F Y') }}
                      </time>
                    </div>

                    <p class="text-base text-gray-600 line-clamp-3 mb-4 font-poppins">
                        {{ Str::limit($artikel->excerpt, 120, '...') }}
                    </p>

                    <a
                      href="{{ route('detailPost',$artikel->slug) }}"
                      class="inline-block text-blue-600 hover:text-blue-800 font-medium"
                    >
                      Baca Selengkapnya →
                    </a>
                  </div>
                </article>
                @endforeach
              @else
                <p class="text-gray-500">Tidak ada artikel terbaru saat ini.</p>
              @endif
            </div>
          </div>
        </div>
      </div>

      <!---Agenda--->
      <div data-aos="fade-up" data-aos-duration="800" class="container mx-auto py-6 sm:px-0 px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
          <!-- Berita Terbaru -->
          <div class="lg:col-span-2">
            <h3 class="text-xl font-semibold mb-4 relative pb-2">
              Agenda
              <div class="absolute left-0 right-0 bottom-0 flex">
                <hr class="w-24 h-[1px] bg-yellow-500 border-0" />
                <hr class="flex-1 h-[1px] bg-gray-300 border-0" />
              </div>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              @if ($latestAgenda->isNotEmpty())
                  @foreach ($latestAgenda as $agenda)
                    <div class="overflow-hidden">
                      <img
                        src="{{ asset('storage/' . $agenda->image) }}"
                        alt="{{ $agenda->title }}"
                        class="w-full h-64 object-cover"
                      />
                      <div class="mt-3">
                        <h2 class="text-2xl font-bold text-gray-800 mb-3 font-inter">
                             <a href="{{ route('detailPost', $agenda->slug) }}" class="hover:text-blue-500">{{ Str::limit($agenda->title, 50, '...') }}</a> 
                        </h2>
                        <p class="text-gray-600 mb-2">  By {{ $agenda->users->name ?? 'Admin' }} - 
                         {{ \Carbon\Carbon::parse($agenda->published)->translatedFormat('d F Y') }}</p>
                        <p class="text-gray-600 mb-4 font-poppins">
                            {{ Str::limit($agenda->excerpt, 120, '...') }}
                        </p>
                        <a
                          href="{{ route('detailPost',$agenda->slug) }}"
                          class="inline-block px-2 py-2.5 border border-gray-400 text-gray-800 hover:bg-blue-500 hover:text-white transition"
                        >
                          Baca Selengkapnya
                        </a>
                      </div>
                    </div>
                  @endforeach
              @else
                  <p class="text-gray-500">Tidak ada agenda terbaru saat ini.</p>
              @endif
             
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection