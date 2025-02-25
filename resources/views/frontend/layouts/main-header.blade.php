 <div class="container py-6 px-1 mx-auto md:max-w-screen-md lg:max-w-screen-xl">
    <div class="flex flex-col sm:flex-row justify-between items-center">
        <a href="{{ route('home') }}" class="flex items-center mb-4 sm:mb-0">
        <img
            src="{{ asset('assets/img/logo.png') }}"
            alt="Logo PMII"
            class="h-16 sm:h-20 w-auto"
        />
        <span class="text-xl sm:text-2xl font-bold text-blue-700 ml-2"
            >PMII Dipayuda</span
        >
        </a>
        <div class="relative w-full sm:w-auto">
        <input
            type="text"
            placeholder="Cari berita..."
            class="w-full sm:w-auto pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
        <svg
            class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
            ></path>
        </svg>
        </div>
    </div>
</div>