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

<body class="bg-stone-100 text-stone-800 font-sans antialiased min-h-screen">

    <div x-data="{ sidebarOpen: false }" class="min-h-screen">

        <!-- SIDEBAR OVERLAY (mobile) -->
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-stone-900/50 backdrop-blur-sm lg:hidden"></div>

        <!-- SIDEBAR -->
        <aside
            class="fixed top-0 left-0 z-50 h-full w-72 bg-white border-r border-stone-200 flex flex-col transition-transform duration-300 lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            <!-- Sidebar Header -->
            <div class="flex items-center justify-between px-6 h-20 border-b border-stone-200 shrink-0">
                <a href="{{ route('admin.keripik.index') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Keripik Mentari"
                        class="h-11 w-auto object-contain group-hover:scale-105 transition">
                    <div>
                        <span
                            class="text-lg font-black text-mentari-red tracking-tight block leading-none">MENTARI</span>
                        <span
                            class="text-[11px] font-bold text-stone-500 uppercase tracking-wider mt-0.5 block">Admin
                            Dashboard</span>
                    </div>
                </a>
                <button @click="sidebarOpen = false"
                    class="lg:hidden w-9 h-9 rounded-xl border border-stone-200 text-stone-500 hover:bg-stone-100 flex items-center justify-center">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Sidebar Nav -->
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
                <p class="px-3 pb-2 text-[10px] font-black uppercase tracking-widest text-stone-400">Menu</p>

                <a href="{{ route('admin.keripik.index') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold transition {{ request()->routeIs('admin.keripik.*') ? 'bg-green-50 text-mentari-green' : 'text-stone-600 hover:bg-stone-100' }}">
                    <i data-lucide="package" class="w-5 h-5 shrink-0"></i>
                    <span>Katalog Keripik</span>
                </a>

                <a href="{{ route('admin.kategori.index') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold transition {{ request()->routeIs('admin.kategori.*') ? 'bg-green-50 text-mentari-green' : 'text-stone-600 hover:bg-stone-100' }}">
                    <i data-lucide="tags" class="w-5 h-5 shrink-0"></i>
                    <span>Kategori</span>
                </a>

                <a href="{{ route('admin.pesanan.index') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold transition {{ request()->routeIs('admin.pesanan.*') ? 'bg-green-50 text-mentari-green' : 'text-stone-600 hover:bg-stone-100' }}">
                    <i data-lucide="shopping-cart" class="w-5 h-5 shrink-0"></i>
                    <span>Pesanan</span>
                </a>

                <a href="{{ route('admin.chat.index') }}"
                    class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-bold transition {{ request()->routeIs('admin.chat.*') ? 'bg-green-50 text-mentari-green' : 'text-stone-600 hover:bg-stone-100' }}">
                    <i data-lucide="message-circle" class="w-5 h-5 shrink-0"></i>
                    <span>Chat</span>
                </a>

                <div class="pt-4">
                    <a href="{{ route('admin.keripik.create') }}"
                        class="flex items-center justify-center gap-2 w-full px-3 py-3 rounded-xl bg-mentari-green hover:bg-mentari-green-dark text-white text-sm font-bold transition shadow-xs">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        <span>Tambah Keripik</span>
                    </a>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="px-4 py-5 border-t border-stone-200 space-y-2 shrink-0">
                <div class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-stone-50">
                    <div class="w-9 h-9 rounded-full bg-mentari-red text-white flex items-center justify-center font-black text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-black text-stone-700 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-stone-400 truncate">Admin</p>
                    </div>
                </div>

                <a href="{{ url('/') }}" target="_self"
                    class="flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-bold text-stone-600 hover:bg-stone-100 transition"
                    title="Buka Website Landing Page">
                    <i data-lucide="globe" class="w-4 h-4 text-mentari-green shrink-0"></i>
                    <span>Lihat Website</span>
                    <i data-lucide="external-link" class="w-3 h-3 text-stone-400 ml-auto"></i>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-3 rounded-xl text-xs font-bold text-stone-600 hover:bg-red-50 hover:text-red-600 transition">
                        <i data-lucide="log-out" class="w-4 h-4 text-mentari-green shrink-0"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- CONTENT WRAPPER -->
        <div class="lg:pl-72 flex flex-col min-h-screen">

            <!-- MOBILE TOPBAR -->
            <header class="lg:hidden bg-white border-b border-stone-200 sticky top-0 z-30 shadow-xs">
                <div class="flex items-center justify-between h-16 px-4">
                    <button @click="sidebarOpen = true"
                        class="w-10 h-10 rounded-xl border border-stone-200 text-stone-600 flex items-center justify-center">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <span class="text-sm font-black text-mentari-red tracking-tight">MENTARI</span>
                    <a href="{{ url('/') }}"
                        class="w-10 h-10 rounded-xl border border-stone-200 text-stone-600 flex items-center justify-center">
                        <i data-lucide="globe" class="w-4 h-4"></i>
                    </a>
                </div>
            </header>

            <!-- MAIN ADMIN CONTAINER -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8">

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

            <!-- ADMIN FOOTER -->
            <footer class="bg-white border-t border-stone-200 py-6 text-center text-xs text-stone-500">
                <div class="px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                    <p>&copy; {{ date('Y') }} <strong>Keripik Mentari Malang</strong>. Panel Pengelolaan Produk.</p>
                    <p class="text-stone-400">Oleh-Oleh Khas Malang & Kota Batu</p>
                </div>
            </footer>
        </div>
    </div>

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
