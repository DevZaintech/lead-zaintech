<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Gate</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/alpinejs" defer></script>
    @yield('css')
</head>
<body class="bg-gray-100 h-screen flex flex-col"
      x-data="{ sidebarOpen: false, profileOpen: false }">

        @if(session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg flex items-center space-x-2 z-50"
            x-transition
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="ml-2 focus:outline-none">
                ✖
            </button>
        </div>
        @endif

        @if($errors->any())
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 4000)"
            class="fixed top-5 right-5 bg-red-500 text-white px-4 py-2 rounded shadow-lg flex items-center space-x-2 z-50"
            x-transition
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ implode(', ', $errors->all()) }}</span>
            <button @click="show = false" class="ml-2 focus:outline-none">
                ✖
            </button>
        </div>
        @endif

    <!-- Navbar -->
    <header class="w-full bg-white shadow px-4 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <!-- Tombol hamburger hanya di mobile -->
            <button class="md:hidden text-gray-600" @click="sidebarOpen = !sidebarOpen">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <!-- Logo utama di navbar -->
            <img src="https://zaintech.co.id/public/icon/logo-h-deks.webp" alt="Logo" class="w-40 h-auto">
        </div>

        <!-- Profil kanan -->
        <div class="relative" x-data="{ profileOpen: false }">
            <button @click="profileOpen = !profileOpen"
                class="flex items-center space-x-2 focus:outline-none">
                <!-- Avatar -->
                <div class="w-10 h-10 rounded-full bg-[#3fa9f3] flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr(Auth::user()->NAMA, 0, 1)) }}
                </div>
                <span class="hidden sm:inline text-gray-700 font-medium">{{ Auth::user()->NAMA }}</span>
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <!-- Dropdown -->
            <div x-show="profileOpen" @click.away="profileOpen = false"
                 class="absolute right-0 mt-2 w-40 bg-white rounded-md shadow-lg py-2 z-50"
                 x-transition>
                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar ala Filament -->
        <aside
            class="fixed inset-y-0 left-0 transform bg-white w-64 shadow-lg flex flex-col z-30 transition-transform duration-200 ease-in-out
                   md:translate-x-0 md:static md:inset-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            <!-- Menu Navigasi -->
            <nav class="flex-1 px-4 py-6 space-y-2">

                {{-- Menu selalu tampil untuk semua --}}
                <a href="{{ Auth::user()->ROLE == 'admin' ? route('dashboard.admin') :
                                    (Auth::user()->ROLE == 'gate' ? route('dashboard.gate') :
                                    (Auth::user()->ROLE == 'sales' ? route('dashboard.sales') :
                                    route('home'))) }}"
                    class="flex items-center px-4 py-2 rounded-lg transition
                    {{ request()->routeIs('dashboard.*') 
                        ? 'bg-[#3fa9f3]/10 text-[#3fa9f3] border-l-4 border-[#3fa9f3]' 
                        : 'text-gray-700 hover:bg-[#3fa9f3]/10 hover:text-[#3fa9f3]' }}">
                    {{-- Home Icon --}}
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l9-9 9 9M4 10v10h6V14h4v6h6V10"/>
                    </svg>
                    Dashboard
                </a>

                {{-- Menu khusus Gate --}}
                @if(Auth::user()->ROLE == 'gate')
                    <a href="{{ route('inputlead.gate') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('inputlead.gate') 
                            ? 'bg-[#3fa9f3]/10 text-[#3fa9f3] border-l-4 border-[#3fa9f3]' 
                            : 'text-gray-700 hover:bg-[#3fa9f3]/10 hover:text-[#3fa9f3]' }}">
                        {{-- Clipboard Add Icon --}}
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5h6m-6 0a2 2 0 01-2 2H5a2 2 0 00-2 2v10a2 
                                2 0 002 2h14a2 2 0 002-2V9a2 2 0 
                                00-2-2h-2a2 2 0 01-2-2m-6 0a2 2 0 
                                012-2h2a2 2 0 012 2m-6 8h6m-3-3v6"/>
                        </svg>
                        Input Lead
                    </a>

                    <a href="{{ route('datalead.gate') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('datalead.gate') 
                            ? 'bg-[#3fa9f3]/10 text-[#3fa9f3] border-l-4 border-[#3fa9f3]' 
                            : 'text-gray-700 hover:bg-[#3fa9f3]/10 hover:text-[#3fa9f3]' }}">
                        {{-- Table Icon --}}
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        Data Lead
                    </a>
                @endif

                {{-- Menu khusus Sales --}}
                @if(Auth::user()->ROLE == 'sales')
                    <a href="{{ route('inputlead.sales') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('inputlead.gate') 
                            ? 'bg-[#3fa9f3]/10 text-[#3fa9f3] border-l-4 border-[#3fa9f3]' 
                            : 'text-gray-700 hover:bg-[#3fa9f3]/10 hover:text-[#3fa9f3]' }}">
                        {{-- Clipboard Add Icon --}}
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 5h6m-6 0a2 2 0 01-2 2H5a2 2 0 00-2 2v10a2 
                                2 0 002 2h14a2 2 0 002-2V9a2 2 0 
                                00-2-2h-2a2 2 0 01-2-2m-6 0a2 2 0 
                                012-2h2a2 2 0 012 2m-6 8h6m-3-3v6"/>
                        </svg>
                        Input Lead
                    </a>

                    <a href="{{ route('datalead.sales') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('datalead.gate') 
                            ? 'bg-[#3fa9f3]/10 text-[#3fa9f3] border-l-4 border-[#3fa9f3]' 
                            : 'text-gray-700 hover:bg-[#3fa9f3]/10 hover:text-[#3fa9f3]' }}">
                        {{-- Table Icon --}}
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        Data Lead
                    </a>

                    <!-- <a href="{{ route('opportunity.sales') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('opportunity.sales') 
                            ? 'bg-[#3fa9f3]/10 text-[#3fa9f3] border-l-4 border-[#3fa9f3]' 
                            : 'text-gray-700 hover:bg-[#3fa9f3]/10 hover:text-[#3fa9f3]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M9 17v-2h6v2a2 2 0 0 1-2 2H11a2 2 0 0 1-2-2zM12 12V5m-7 0h14"/>
                        </svg>
                        Data Opportunity
                    </a>

                    <a href="{{ route('quotation.sales') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('quotation.sales') 
                            ? 'bg-[#3fa9f3]/10 text-[#3fa9f3] border-l-4 border-[#3fa9f3]' 
                            : 'text-gray-700 hover:bg-[#3fa9f3]/10 hover:text-[#3fa9f3]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M9 17v-2h6v2a2 2 0 0 1-2 2H11a2 2 0 0 1-2-2zM12 12V5m-7 0h14"/>
                        </svg>
                        Data Quotation
                    </a> -->
                @endif

                {{-- Menu hanya Admin --}}
                @if(Auth::user()->ROLE == 'admin')
                    <a href="{{ route('datalead.admin') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('datalead.admin') 
                            ? 'bg-[#3fa9f3]/10 text-[#3fa9f3] border-l-4 border-[#3fa9f3]' 
                            : 'text-gray-700 hover:bg-[#3fa9f3]/10 hover:text-[#3fa9f3]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4"/>
                        </svg>
                        Lead
                    </a>

                    <a href="{{ route('dataopp.admin') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('dataopp.admin') 
                            ? 'bg-[#3fa9f3]/10 text-[#3fa9f3] border-l-4 border-[#3fa9f3]' 
                            : 'text-gray-700 hover:bg-[#3fa9f3]/10 hover:text-[#3fa9f3]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4"/>
                        </svg>
                        Opportunity
                    </a>

                    <a href="{{ route('dataquo.admin') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('dataquo.admin') 
                            ? 'bg-[#3fa9f3]/10 text-[#3fa9f3] border-l-4 border-[#3fa9f3]' 
                            : 'text-gray-700 hover:bg-[#3fa9f3]/10 hover:text-[#3fa9f3]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4"/>
                        </svg>
                        Quotation
                    </a>

                    <a href="{{ route('kategori.index') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('kategori.index') 
                            ? 'bg-[#3fa9f3]/10 text-[#3fa9f3] border-l-4 border-[#3fa9f3]' 
                            : 'text-gray-700 hover:bg-[#3fa9f3]/10 hover:text-[#3fa9f3]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M12 4v16m8-8H4"/>
                        </svg>
                        Kategori
                    </a>

                    <a href="{{ route('subkategori.index') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('subkategori.index') 
                            ? 'bg-[#3fa9f3]/10 text-[#3fa9f3] border-l-4 border-[#3fa9f3]' 
                            : 'text-gray-700 hover:bg-[#3fa9f3]/10 hover:text-[#3fa9f3]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M4 6h16M4 12h8m-8 6h16"/>
                        </svg>
                        Sub Kategori
                    </a>

                    <a href="{{ route('produk.index') }}"
                        class="flex items-center px-4 py-2 rounded-lg transition
                        {{ request()->routeIs('produk.index') 
                            ? 'bg-[#3fa9f3]/10 text-[#3fa9f3] border-l-4 border-[#3fa9f3]' 
                            : 'text-gray-700 hover:bg-[#3fa9f3]/10 hover:text-[#3fa9f3]' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M9 17v-2h6v2a2 2 0 0 1-2 2H11a2 2 0 0 1-2-2zM12 12V5m-7 0h14"/>
                        </svg>
                        Produk
                    </a>
                @endif

            </nav>


            <!-- Footer Sidebar -->
            <div class="p-4 border-t">
                <p class="text-xs text-gray-400">© {{ date('Y') }} Zaintech</p>
            </div>
        </aside>

        <!-- Overlay untuk mobile -->
        <div class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden"
             x-show="sidebarOpen" @click="sidebarOpen=false"></div>

        <!-- Content -->
        <main class="flex-1 p-6 overflow-y-auto md:ml-0 bg-grey-100">
            <div class="w-full bg-white-100">
                @yield('content')
            </div>
        </main>
    </div>
    @yield('scripts')
</body>
</html>
