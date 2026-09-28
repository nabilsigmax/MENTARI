<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Saya - Keripik Mentari</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mentari: {
                            red: '#EE4D2D',
                            'red-dark': '#D73211',
                            green: '#16A34A',
                            'green-dark': '#15803D',
                            gray: '#F5F5F5'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-mentari-gray font-sans text-stone-800" x-data="{ activeTab: 'semua' }">
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-stone-200/80 shadow-xs">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-20 gap-4">

            <!-- Logo Brand + Slogan (Klik Logo kembali ke Beranda) -->
            <a href="/" class="flex items-center gap-3 shrink-0 group" title="Kembali ke Beranda">
                <img src="{{ asset('images/logo.png') }}" alt="Keripik Mentari Logo"
                    class="h-12 sm:h-14 w-auto object-contain transition group-hover:scale-105">
                
                <div class="hidden sm:block border-l border-stone-200 pl-3">
                    <span class="text-xs text-stone-500 font-bold tracking-wide block leading-tight">Oleh-Oleh Khas Malang</span>
                </div>
            </a>

            <!-- Actions (Transaksional & Akun) -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">

                <!-- Button Cek Pesanan -->
                <a href="{{ route('orders.index') }}"
                    class="flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-bold text-stone-700 bg-stone-100 hover:bg-stone-200 hover:text-mentari-red transition"
                    title="Cek Status Pesanan">
                    <i data-lucide="package-search" class="h-4 w-4 text-mentari-red"></i>
                    <span class="hidden xs:inline">Cek Pesanan</span>
                </a>

                <!-- Cart -->
                <button @click="cartOpen = true"
                    class="relative rounded-full p-2.5 text-stone-700 transition hover:bg-stone-100"
                    title="Keranjang">
                    <i data-lucide="shopping-bag" class="h-5 w-5"></i>
                    <span x-show="cartCount > 0" x-text="cartCount"
                        class="absolute right-1 top-1 flex h-4 w-4 items-center justify-center rounded-full bg-mentari-red text-[10px] font-bold text-white"></span>
                </button>

                <!-- Account Dropdown -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open"
                        class="flex items-center gap-1.5 rounded-full p-2 sm:pl-2.5 sm:pr-3 text-stone-700 transition hover:bg-stone-100"
                        title="Akun">
                        <i data-lucide="user-circle" class="h-5 w-5"></i>
                        @auth
                            <span
                                class="hidden lg:block text-xs font-bold max-w-[100px] truncate">{{ auth()->user()->name }}</span>
                        @endauth
                        <i data-lucide="chevron-down" class="h-3.5 w-3.5 hidden sm:block"></i>
                    </button>

                    <div x-show="open" x-cloak x-transition
                        class="absolute right-0 mt-2 w-56 rounded-xl border border-stone-200 bg-white shadow-lg overflow-hidden text-xs">
                        @guest
                            <div class="p-2 space-y-1">
                                <a href="{{ route('login') }}"
                                    class="block rounded-lg px-3 py-2 font-bold text-stone-700 hover:bg-stone-50">Masuk</a>
                                <a href="{{ route('register') }}"
                                    class="block rounded-lg bg-mentari-green px-3 py-2 font-bold text-white text-center hover:bg-mentari-green-dark">Daftar</a>
                            </div>
                        @endguest

                        @auth
                            <div class="px-4 py-3 border-b border-stone-100">
                                <p class="font-bold text-stone-800">{{ auth()->user()->name }}</p>
                                <p class="text-stone-500 text-[11px]">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="p-2 space-y-1">
                                @if (auth()->user()->isAdmin())
                                    <a href="{{ route('admin.keripik.index') }}"
                                        class="flex items-center gap-2 rounded-lg px-3 py-2 font-bold text-stone-700 hover:bg-stone-50">
                                        <i data-lucide="shield-check" class="h-3.5 w-3.5 text-mentari-red"></i>
                                        <span>Dashboard Admin</span>
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left flex items-center gap-2 rounded-lg px-3 py-2 font-bold text-stone-700 hover:bg-red-50 hover:text-mentari-red">
                                        <i data-lucide="log-out" class="h-3.5 w-3.5"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        @endauth
                    </div>
                </div>

            </div>

        </div>
    </div>
</header>
    <!-- Header -->
    <header class="sticky top-0 z-40 border-b border-stone-200 bg-white shadow-sm">
        <div class="mx-auto flex h-16 max-w-4xl items-center justify-between px-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <i data-lucide="chevron-left" class="h-6 w-6 text-mentari-red"></i>
                <span class="text-lg font-bold text-stone-900">Pesanan Saya</span>
            </a>
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-stone-600">{{ auth()->user()->name ?? 'Guest' }}</span>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="mx-auto max-w-4xl px-4">
            <div class="flex overflow-x-auto scrollbar-hide text-sm font-semibold text-stone-500">
                <button @click="activeTab = 'semua'"
                    :class="activeTab === 'semua' ? 'border-mentari-red text-mentari-red border-b-2' :
                        'border-transparent hover:text-mentari-red'"
                    class="whitespace-nowrap px-4 py-3 transition-colors">Semua</button>
                <button @click="activeTab = 'pending'"
                    :class="activeTab === 'pending' ? 'border-mentari-red text-mentari-red border-b-2' :
                        'border-transparent hover:text-mentari-red'"
                    class="whitespace-nowrap px-4 py-3 transition-colors">Belum Bayar</button>
                <button @click="activeTab = 'diproses'"
                    :class="activeTab === 'diproses' ? 'border-mentari-red text-mentari-red border-b-2' :
                        'border-transparent hover:text-mentari-red'"
                    class="whitespace-nowrap px-4 py-3 transition-colors">Dikemas</button>
                <button @click="activeTab = 'dikirim'"
                    :class="activeTab === 'dikirim' ? 'border-mentari-red text-mentari-red border-b-2' :
                        'border-transparent hover:text-mentari-red'"
                    class="whitespace-nowrap px-4 py-3 transition-colors">Dikirim</button>
                <button @click="activeTab = 'selesai'"
                    :class="activeTab === 'selesai' ? 'border-mentari-red text-mentari-red border-b-2' :
                        'border-transparent hover:text-mentari-red'"
                    class="whitespace-nowrap px-4 py-3 transition-colors">Selesai</button>
                <button @click="activeTab = 'batal'"
                    :class="activeTab === 'batal' ? 'border-mentari-red text-mentari-red border-b-2' :
                        'border-transparent hover:text-mentari-red'"
                    class="whitespace-nowrap px-4 py-3 transition-colors">Dibatalkan</button>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-4xl p-4 py-6">

        <div class="space-y-4">
            {{-- Loop untuk setiap pesanan yang ditarik dari database --}}
            @forelse ($pesanans ?? [] as $pesanan)
                <div x-show="activeTab === 'semua' || activeTab === '{{ strtolower($pesanan->status) }}'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0" class="rounded-sm bg-white p-4 shadow-sm"
                    style="display: none;">
                    
                    <!-- Header Card: Nama Toko & Status -->
                    <div class="flex items-center justify-between border-b border-stone-100 pb-3">
                        <div class="flex items-center gap-2">
                            <i data-lucide="store" class="h-4 w-4 text-stone-600"></i>
                            <span class="text-sm font-bold text-stone-800">Keripik Mentari</span>
                        </div>
                        <span class="text-sm font-bold uppercase text-mentari-red">
                            @if ($pesanan->status === 'pending')
                                Belum Bayar
                            @elseif($pesanan->status === 'dibayar')
                                Menunggu Konfirmasi
                            @elseif($pesanan->status === 'diproses')
                                Dikemas
                            @elseif($pesanan->status === 'dikirim')
                                Dikirim
                            @elseif($pesanan->status === 'selesai')
                                Selesai
                            @elseif($pesanan->status === 'batal')
                                Dibatalkan
                            @else
                                {{ $pesanan->status }}
                            @endif
                        </span>
                    </div>

                    <!-- Body Card: Info Produk -->
                    <div class="flex items-start gap-4 py-4 cursor-pointer" onclick="window.location.href='#'">
                        <img src="{{ $pesanan->keripik->gambar_url ?? 'https://via.placeholder.com/80' }}"
                            alt="{{ $pesanan->keripik->nama ?? 'Produk' }}"
                            class="h-20 w-20 rounded border border-stone-200 object-cover">
                        <div class="flex-1">
                            <h3 class="text-base font-semibold text-stone-800 line-clamp-2">
                                {{ $pesanan->keripik->nama ?? 'Nama Produk' }}</h3>
                            <p class="mt-1 text-sm text-stone-500">Variasi: Original,
                                {{ $pesanan->keripik->berat ?? '250g' }}</p>
                            <p class="mt-1 text-sm font-medium text-stone-700">x{{ $pesanan->jumlah }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-stone-800">
                                Rp{{ number_format($pesanan->keripik->harga ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Footer Card: Total, Pengiriman, & Aksi -->
                    <div class="border-t border-stone-100 pt-5 mt-2">
                        
                        <!-- Detail Pengiriman (Tampilan Rapih) -->
                        @if ($pesanan->nomor_resi || $pesanan->tanggal_dikirim || $pesanan->estimasi_tiba || $pesanan->tanggal_diterima)
                            <div class="mb-5 overflow-hidden rounded-xl border border-stone-200 bg-stone-50/50">
                                <!-- Header Pengiriman -->
                                <div class="border-b border-stone-200 bg-stone-100/50 px-4 py-3 flex items-center gap-2">
                                    <i data-lucide="truck" class="w-4 h-4 text-stone-500"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider text-stone-700">Detail Pengiriman</span>
                                </div>
                    
                                <!-- Isi Pengiriman -->
                                <div class="flex flex-col gap-3.5 p-4 text-sm">
                                    @if ($pesanan->nomor_resi)
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-2 text-stone-500">
                                                <i data-lucide="receipt-text" class="w-4 h-4"></i> No. Resi
                                            </span>
                                            <div class="flex items-center gap-2">
                                                <span class="font-bold text-stone-800">{{ $pesanan->nomor_resi }}</span>
                                                <button type="button" onclick="salinResi(this, '{{ $pesanan->nomor_resi }}')"
                                                    class="flex items-center gap-1.5 justify-center rounded border border-stone-200 bg-white px-2 py-1 text-xs font-semibold text-stone-500 transition hover:border-mentari-red hover:text-mentari-red" title="Salin Resi">
                                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                                    <span>Salin</span>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                    
                                    @if ($pesanan->tanggal_dikirim)
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-2 text-stone-500">
                                                <i data-lucide="calendar-arrow-up" class="w-4 h-4"></i> Dikirim
                                            </span>
                                            <span class="font-semibold text-stone-800">{{ $pesanan->tanggal_dikirim->translatedFormat('d M Y') }}</span>
                                        </div>
                                    @endif
                    
                                    @if ($pesanan->estimasi_tiba)
                                        <div class="flex items-center justify-between">
                                            <span class="flex items-center gap-2 text-stone-500">
                                                <i data-lucide="calendar-clock" class="w-4 h-4"></i> Estimasi Tiba
                                            </span>
                                            <span class="font-semibold text-stone-800">{{ $pesanan->estimasi_tiba->translatedFormat('d M Y') }}</span>
                                        </div>
                                    @endif
                    
                                    @if ($pesanan->tanggal_diterima)
                                        <div class="mt-1 flex items-center justify-between border-t border-stone-200 pt-3">
                                            <span class="flex items-center gap-2 text-stone-500">
                                                <i data-lucide="package-check" class="w-4 h-4 text-mentari-green"></i> Diterima
                                            </span>
                                            <span class="font-bold text-mentari-green">{{ $pesanan->tanggal_diterima->translatedFormat('d M Y') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Ringkasan Harga & Tombol -->
                        <div class="mb-4 flex items-center justify-end gap-2 text-sm text-right">
                            <span class="text-stone-600">Total Pesanan:</span>
                            <span class="text-lg font-black text-mentari-red">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex justify-end gap-2">
                            <!-- Tombol Dinamis berdasarkan status -->
                            @if ($pesanan->status === 'pending')
                                <a href="#"
                                    class="rounded bg-mentari-green px-6 py-2 text-sm font-bold text-white transition hover:bg-mentari-green-dark">
                                    Bayar Sekarang
                                </a>
                                <button
                                    class="rounded border border-stone-300 bg-white px-4 py-2 text-sm font-semibold text-stone-600 transition hover:bg-stone-50">Batalkan</button>
                            @elseif($pesanan->status === 'dikirim')
                                <button
                                    class="rounded bg-mentari-green px-6 py-2 text-sm font-bold text-white transition hover:bg-mentari-green-dark">
                                    Pesanan Diterima
                                </button>
                                <button
                                    class="rounded border border-stone-300 bg-white px-4 py-2 text-sm font-semibold text-stone-600 transition hover:bg-stone-50">Lacak</button>
                            @elseif($pesanan->status === 'selesai' || $pesanan->status === 'batal')
                                <a href="#"
                                    class="rounded bg-mentari-green px-6 py-2 text-sm font-bold text-white transition hover:bg-mentari-green-dark">
                                    Beli Lagi
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Tampilan jika tidak ada pesanan sama sekali -->
                <div class="flex flex-col items-center justify-center rounded-sm bg-white py-16 shadow-sm">
                    <div class="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-stone-100 text-stone-400">
                        <i data-lucide="file-x-2" class="h-10 w-10"></i>
                    </div>
                    <h3 class="text-lg font-bold text-stone-800">Belum ada pesanan</h3>
                    <p class="mt-1 text-sm text-stone-500">Cari produk favoritmu dan mulai belanja!</p>
                    <a href="{{ route('home') }}"
                        class="mt-6 rounded bg-mentari-green px-6 py-2.5 text-sm font-bold text-white hover:bg-mentari-green-dark">
                        Belanja Sekarang
                    </a>
                </div>
            @endforelse
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());

        function salinResi(button, resi) {
            const label = button.querySelector('span');
            const teksAwal = label ? label.textContent : 'Salin';
            const kembalikan = () => {
                if (label) label.textContent = teksAwal;
            };
            const tandaiTersalin = () => {
                if (label) label.textContent = 'Tersalin!';
                setTimeout(kembalikan, 1500);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(resi).then(tandaiTersalin).catch(kembalikan);
            } else {
                const input = document.createElement('input');
                input.value = resi;
                document.body.appendChild(input);
                input.select();
                try {
                    document.execCommand('copy');
                    tandaiTersalin();
                } catch (e) {
                    kembalikan();
                }
                document.body.removeChild(input);
            }
        }
    </script>
</body>

</html>