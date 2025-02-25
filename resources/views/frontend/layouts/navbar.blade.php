<nav class="bg-blue-700 container mx-auto md:max-w-screen-md lg:max-w-screen-xl sm:px-0 px-4 overflow-x-auto">
    <ul class="flex flex-nowrap">
       <li class="group">
            <a
                href="{{ route('home') }}"
                class="block px-4 py-3 text-white relative whitespace-nowrap {{ Request::routeIs('home') ? 'bg-blue-800' : 'hover:bg-blue-700' }}"
            >
                Home
                <span class="absolute bottom-0 left-0 w-full h-1 bg-yellow-400 transform {{ Request::routeIs('home') ? 'scale-x-100' : 'scale-x-0' }} group-hover:scale-x-100 transition-transform duration-300 origin-left">
                </span>
            </a>
        </li>
        <li class="group">
        <a
            href="/category/berita"
            class="block px-4 py-3 text-white hover:bg-blue-700 relative whitespace-nowrap"
            >Berita
            <span
            class="absolute bottom-0 left-0 w-full h-1 bg-yellow-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
            ></span>
        </a>
        </li>
        <li class="group">
        <a
            href="/category/artikel"
            class="block px-4 py-3 text-white hover:bg-blue-700 relative whitespace-nowrap"
            >Artikel
            <span
            class="absolute bottom-0 left-0 w-full h-1 bg-yellow-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
            ></span>
        </a>
        </li>
        <li class="group">
        <a
            href="/category/opini"
            class="block px-4 py-3 text-white hover:bg-blue-700 relative whitespace-nowrap"
            >Opini
            <span
            class="absolute bottom-0 left-0 w-full h-1 bg-yellow-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
            ></span>
        </a>
        </li>
        <li class="group">
        <a
            href="/category/agenda"
            class="block px-4 py-3 text-white hover:bg-blue-700 relative whitespace-nowrap"
            >Agenda
            <span
            class="absolute bottom-0 left-0 w-full h-1 bg-yellow-400 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"
            ></span>
        </a>
        </li>
    </ul>
</nav>