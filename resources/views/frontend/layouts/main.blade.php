<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title') - PMII Dipayuda Banjarnegara</title>

    <link rel="icon" href="{{ asset('assets/img/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/font/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    {{--
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}"> --}}
    @vite('resources/css/app.css')
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/aos/aos.css') }}">
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">
    <header>
        <!-- Top Bar -->
        @include('frontend.layouts.top-header')

        <!-- Main Header -->
        @include('frontend.layouts.main-header')

        <!-- Navigation -->
        @include('frontend.layouts.navbar')
    </header>

    @yield('content')

    @include('frontend.layouts.footer')

    <!-- Tambahan untuk responsivitas dan aksesibilitas -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black opacity-50 z-40 hidden"></div>

    <!-- Tombol scroll to top -->
    <button id="scrollToTop"
        class="fixed bottom-6 right-6 bg-blue-600 text-white w-12 h-12 rounded-full shadow-lg hover:bg-blue-700 transition duration-300 hidden">
        <i class="bi bi-arrow-up text-xl"></i>
    </button>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/aos/aos.js') }}"></script>
    <script>
        AOS.init({
          once: true,
      });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const tickerContent = document.querySelector(".news-ticker-content");
            const prevBtn = document.querySelector(".news-prev");
            const nextBtn = document.querySelector(".news-next");

            // Dapatkan semua elemen berita
            const newsItems = Array.from(tickerContent.querySelectorAll("a"));
            let currentIndex = 0;

            // Fungsi untuk menampilkan berita
            function showNews(index) {
                // Sembunyikan semua berita
                newsItems.forEach((item, i) => {
                    item.style.display = "none";
                });

                // Tampilkan berita yang sesuai
                if (newsItems[index]) {
                    newsItems[index].style.display = "block";
                }
            }

            // Tampilkan berita pertama
            showNews(currentIndex);

            // Fungsi untuk berita selanjutnya
            function nextNews() {
                currentIndex = (currentIndex + 1) % newsItems.length;
                showNews(currentIndex);
            }

            // Fungsi untuk berita sebelumnya
            function prevNews() {
                currentIndex = (currentIndex - 1 + newsItems.length) % newsItems.length;
                showNews(currentIndex);
            }

            // Ganti berita setiap 5 detik
            let newsInterval = setInterval(nextNews, 5000);

            // Tombol next
            nextBtn.addEventListener("click", () => {
                clearInterval(newsInterval);
                nextNews();
                newsInterval = setInterval(nextNews, 5000);
            });

            // Tombol previous
            prevBtn.addEventListener("click", () => {
                clearInterval(newsInterval);
                prevNews();
                newsInterval = setInterval(nextNews, 5000);
            });

            // Hentikan interval jika mouse masuk
            tickerContent.addEventListener("mouseenter", () => {
                clearInterval(newsInterval);
            });

            // Lanjutkan interval jika mouse keluar
            tickerContent.addEventListener("mouseleave", () => {
                newsInterval = setInterval(nextNews, 5000);
            });
        });
    </script>
</body>

</html>