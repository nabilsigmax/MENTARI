<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#DC2626">

    <title>{{ $title ?? 'Admin Dashboard' }} - Keripik Mentari</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mentari: {
                            red: '#DC2626',
                            'green-dark': '#991B1B',
                            gold: '#F59E0B',
                            green: '#16A34A',
                            'green-dark': '#15803D',
                            warm: '#FAFAF9'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-stone-100 text-stone-800 font-sans antialiased min-h-screen flex flex-col justify-between">

    <div>
        <!-- TOP ADMIN NAVBAR -->
        <header class="bg-white border-b border-stone-200 sticky top-0 z-30 shadow-xs">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">

                    <!-- Logo & Title -->
                    <div class="flex items-center gap-5">
                        <a href="{{ route('admin.keripik.index') }}" class="flex items-center gap-3 group">
                            <img src="{{ asset('images/logo.png') }}" alt="Keripik Mentari"
                                class="h-12 w-auto object-contain group-hover:scale-105 transition">
                            <div>
                                <span
                                    class="text-lg font-black text-mentari-red tracking-tight block leading-none">MENTARI</span>
                                <span
                                    class="text-[11px] font-bold text-stone-500 uppercase tracking-wider mt-0.5 block">Admin
                                    Dashboard</span>
                            </div>
                        </a>

                        <div class="hidden md:block h-6 w-px bg-stone-200"></div>

                        <!-- Main Nav Links -->
                        <nav class="hidden md:flex items-center gap-2">
                            <a href="{{ route('admin.keripik.index') }}"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ request()->routeIs('admin.keripik.*') ? 'bg-green-50 text-mentari-green font-black' : 'text-stone-600 hover:bg-stone-100' }}">
                                <i data-lucide="package" class="w-4 h-4"></i>
                                <span>Katalog Keripik</span>
                            </a>
                            <a href="{{ route('admin.pesanan.index') }}"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ request()->routeIs('admin.pesanan.*') ? 'bg-green-50 text-mentari-green font-black' : 'text-stone-600 hover:bg-stone-100' }}">
                                <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                <span>Pesanan</span>
                            </a>
                            <a href="{{ route('admin.chat.index') }}"
                                class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ request()->routeIs('admin.chat.*') ? 'bg-green-50 text-mentari-green font-black' : 'text-stone-600 hover:bg-stone-100' }}">
                                <i data-lucide="message-circle" class="w-4 h-4"></i>
                                <span>Chat</span>
                            </a>
                            <a href="{{ route('admin.keripik.create') }}"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-mentari-green hover:bg-mentari-green-dark text-white text-xs font-bold transition shadow-xs">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                <span>Tambah Keripik</span>
                            </a>
                        </nav>
                    </div>

                    <!-- Right Side Actions -->
                    <div class="flex items-center gap-3">
                        <span
                            class="hidden lg:inline text-xs font-bold text-stone-600">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 text-stone-700 bg-white hover:bg-stone-50 text-xs font-bold transition shadow-2xs">
                                <i data-lucide="log-out" class="w-4 h-4 text-mentari-green"></i><span
                                    class="hidden sm:inline">Keluar</span>
                            </button>
                        </form>
                        <a href="{{ url('/') }}" target="_self"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-stone-200 text-stone-700 bg-white hover:bg-stone-50 text-xs font-bold transition shadow-2xs"
                            title="Buka Website Landing Page (Tab Baru)">
                            <i data-lucide="globe" class="w-4 h-4 text-mentari-green"></i>
                            <span class="hidden sm:inline">Lihat Website</span>
                            <i data-lucide="external-link" class="w-3 h-3 text-stone-400"></i>
                        </a>


                    </div>

                </div>
            </div>
        </header>

        <!-- MAIN ADMIN CONTAINER -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Global Flash Message -->
            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm font-bold text-mentari-green-dark flex items-center justify-between shadow-xs"
                    x-data="{ show: true }" x-show="show">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-xl bg-green-100 text-mentari-green-dark flex items-center justify-center shrink-0 font-bold">
                            <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                        </div>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false"
                        class="text-mentari-green hover:text-mentari-green-dark text-lg font-bold p-1">&times;</button>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-bold text-red-800 flex items-center justify-between shadow-xs"
                    x-data="{ show: true }" x-show="show">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-xl bg-red-100 text-red-700 flex items-center justify-center shrink-0 font-bold">
                            <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        </div>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="show = false"
                        class="text-red-500 hover:text-red-800 text-lg font-bold p-1">&times;</button>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    <!-- ADMIN FOOTER -->
    <footer class="bg-white border-t border-stone-200 py-6 text-center text-xs text-stone-500">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p>&copy; {{ date('Y') }} <strong>Keripik Mentari Malang</strong>. Panel Pengelolaan Produk.</p>
            <p class="text-stone-400">Oleh-Oleh Khas Malang & Kota Batu</p>
        </div>
    </footer>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
        document.addEventListener('alpine:initialized', () => {
            lucide.createIcons();
        });
    </script>
</body>

</html>
