<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $keripik->nama }} - Keripik Mentari</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mentari: {
                            red: '#EE4D2D', // Disesuaikan dengan warna oranye/merah e-commerce
                            'red-light': '#FFEEEE',
                            'red-dark': '#D73211',
                            gray: '#F5F5F5' // Background halaman
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Sembunyikan panah input number */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>

<body class="bg-mentari-gray font-sans text-stone-800">
    <header class="sticky top-0 z-40 border-b border-stone-200 bg-white">
        <div class="bg-emerald-500 text-white text-xs sm:text-sm py-2 px-4 font-medium tracking-wide overflow-hidden">
            <marquee behavior="scroll" direction="left" scrollamount="10">
                🛡️ Garansi Kerenyahan 100% & Ter sertifikasi Halal MUI: Kami Menjamin Keripik Apel Khas Malang Tetap
                Renyah,
                Aman, dan Higienis Sampai ke Tangan Anda dengan Pengiriman Ekstra Aman ke Seluruh Kota di Indonesia!
            </marquee>
        </div>

        <!-- MAIN NAVBAR (VIBRANT & CLEAN) -->
        <!-- MAIN NAVBAR (RAPI, TETAP LENGKAP FITURNYA) -->
        <!-- MAIN NAVBAR -->
        <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-stone-200/80 shadow-xs">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between h-20 gap-4">

                    <!-- Logo Brand -->
                    <a href="#" class="flex items-center gap-3 shrink-0">
                        <img src="{{ asset('images/logo.png') }}" alt="Keripik Mentari Logo"
                            class="h-12 sm:h-14 w-auto object-contain">
                        <div class="hidden sm:block">
                            <span
                                class="text-xl font-black tracking-tight text-mentari-red block leading-tight">MENTARI</span>
                            <span class="text-xs text-stone-500 font-semibold tracking-wide">Oleh-Oleh Khas
                                Malang</span>
                        </div>
                    </a>



                    <!-- Actions -->
                    <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">

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
                                            class="block rounded-lg px-3 py-2 font-bold text-stone-700 hover:bg-stone-50">Login</a>
                                        <a href="{{ route('register') }}"
                                            class="block rounded-lg bg-mentari-red px-3 py-2 font-bold text-white text-center hover:bg-mentari-red-dark">Register</a>
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

                        <!-- Mobile menu toggle -->
                        <button @click="mobileMenu = !mobileMenu"
                            class="rounded-lg p-2 text-stone-600 transition hover:bg-stone-100 lg:hidden"
                            title="Buka menu">
                            <i data-lucide="menu" class="h-6 w-6"></i>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Mobile Nav Menu -->
            <div x-show="mobileMenu" @click.outside="mobileMenu = false"
                class="space-y-3 border-t border-stone-100 bg-white px-4 py-3 text-sm lg:hidden" x-cloak>
                <a @click="mobileMenu = false" href="#beranda"
                    class="block rounded-lg px-3 py-2 font-semibold text-stone-700 hover:bg-stone-50">Beranda</a>
                <a @click="mobileMenu = false" href="#keunggulan"
                    class="block rounded-lg px-3 py-2 font-semibold text-stone-700 hover:bg-stone-50">Keunggulan</a>
                <a @click="mobileMenu = false" href="#produk"
                    class="block rounded-lg px-3 py-2 font-semibold text-stone-700 hover:bg-stone-50">Produk</a>
                <a @click="mobileMenu = false" href="#tentang"
                    class="block rounded-lg px-3 py-2 font-semibold text-stone-700 hover:bg-stone-50">Tentang Kami</a>
                <a @click="mobileMenu = false" href="#kontak"
                    class="block rounded-lg px-3 py-2 font-semibold text-stone-700 hover:bg-stone-50">Lokasi &
                    Kontak</a>
                <a href="https://wa.me/6281234567890?text=Halo%20Keripik%20Mentari,%20saya%20tertarik%20untuk%20pesan%20oleh-oleh%20khas%20Malang."
                    target="_blank"
                    class="flex items-center justify-center gap-2 rounded-lg bg-mentari-red px-3 py-2.5 font-bold text-white">
                    <i data-lucide="message-circle" class="h-4 w-4"></i>
                    <span>Pesan di WA</span>
                </a>
            </div>
        </header>
        <main class="mx-auto max-w-6xl px-4 py-6 sm:px-6" x-data="{
            qty: 1,
            stock: {{ $keripik->stok }},
            price: {{ $keripik->harga }},
            productName: {{ Illuminate\Support\Js::from($keripik->nama) }},
            productWeight: {{ Illuminate\Support\Js::from($keripik->berat) }},
            formatRupiah(v) {
                return 'Rp' + Number(v).toLocaleString('id-ID')
            },
            orderViaWhatsApp() {
                const message = `Halo Keripik Mentari, saya ingin pesan ${this.productName} (${this.productWeight}) sebanyak ${this.qty} pcs. Mohon informasi ketersediaan stok dan ongkir.`;
                window.open('https://wa.me/6281234567890?text=' + encodeURIComponent(message), '_blank')
            }
        }">

            <!-- Breadcrumb -->
            <nav class="mb-5 flex items-center gap-2 text-sm text-blue-600">
                <a href="{{ route('home') }}" class="hover:underline">Beranda</a>
                <span class="text-stone-400 text-xs">/</span>
                <a href="#" class="hover:underline">{{ $keripik->kategori }}</a>
                <span class="text-stone-400 text-xs">/</span>
                <span class="text-stone-800 line-clamp-1">{{ $keripik->nama }}</span>
            </nav>

            <!-- Product Main Section -->
            <section class="rounded-sm bg-white shadow-sm flex flex-col md:flex-row p-4 md:p-6 gap-8">

                <!-- Left: Image Gallery -->
                <div class="w-full md:w-5/12 shrink-0">
                    <div class="aspect-square overflow-hidden border border-stone-100 bg-stone-50">
                        @if ($keripik->gambar_url)
                            <img src="{{ $keripik->gambar_url }}" alt="{{ $keripik->nama }}"
                                class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center text-stone-400">
                                <i data-lucide="image" class="h-16 w-16 opacity-50"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails (Mocked) -->
                    <div class="mt-4 flex gap-2 overflow-x-auto">
                        @if ($keripik->gambar_url)
                            <div class="w-20 h-20 shrink-0 border-2 border-mentari-red cursor-pointer">
                                <img src="{{ $keripik->gambar_url }}" class="w-full h-full object-cover">
                            </div>
                        @endif
                        <!-- Add more thumbnails here if available -->
                    </div>

                    <!-- Share & Favorite -->

                </div>

                <!-- Right: Product Info -->
                <div class="w-full flex-1 flex flex-col">
                    <!-- Title & Badges -->
                    <h1 class="text-xl font-medium leading-relaxed text-stone-900">
                        <span class="align-middle">{{ $keripik->nama }}</span>
                    </h1>



                    <!-- Price Box -->
                    <div
                        class="mt-6 bg-stone-50 p-4 sm:p-5 rounded-xl border border-stone-100 shadow-sm flex flex-col gap-1">
                        <!-- Original price & discount badge -->


                        <!-- Main Price -->
                        <div class="flex items-baseline gap-1">
                            <span class="text-3xl sm:text-4xl font-extrabold tracking-tight text-mentari-red"
                                x-text="formatRupiah(price)"></span>
                            <span class="text-sm font-medium text-stone-500 ml-1">/ kemasan</span>
                        </div>
                    </div>

                    <!-- Details List -->
                    <div class="mt-6 flex flex-col gap-5 text-sm text-stone-600">

                        <!-- Pengiriman -->
                        <div class="flex items-start">
                            <div class="w-28 shrink-0">Pengiriman</div>
                            <div class="flex-1 flex flex-col gap-2">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="truck" class="w-5 h-5 text-emerald-600"></i>
                                    <span class="text-stone-800">Garansi tiba 15 - 16 Sep ></span>
                                </div>

                            </div>
                        </div>

                        <!-- Jaminan -->
                        <div class="flex items-start">
                            <div class="w-28 shrink-0">Jaminan</div>
                            <div class="flex-1 flex flex-wrap items-center gap-2 text-stone-800">
                                <i data-lucide="shield-check" class="w-4 h-4 text-mentari-red"></i>
                                <span>15 Hari Pengembalian &bull; 100% Original &bull; COD-Cek Dulu</span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-stone-400 cursor-pointer"></i>
                            </div>
                        </div>

                        <!-- Ukuran (From original data) -->
                        <div class="flex items-center">
                            <div class="w-28 shrink-0">Ukuran</div>
                            <div class="text-stone-800">{{ $keripik->berat }}</div>
                        </div>

                        <!-- Kuantitas -->
                        <div class="flex items-center mt-2">
                            <div class="w-28 shrink-0">Kuantitas</div>
                            <div class="flex items-center gap-4">
                                <div class="flex h-8 items-center border border-stone-300 rounded-sm">
                                    <button @click="if(qty>1)qty--"
                                        class="flex h-full w-8 items-center justify-center border-r border-stone-300 text-stone-500 hover:bg-stone-50">
                                        <i data-lucide="minus" class="w-3 h-3"></i>
                                    </button>
                                    <input type="number" x-model="qty" min="1" :max="stock"
                                        class="h-full w-12 text-center text-sm font-medium outline-none" readonly>
                                    <button @click="if(qty<stock)qty++" :disabled="stock === 0"
                                        class="flex h-full w-8 items-center justify-center border-l border-stone-300 text-stone-500 hover:bg-stone-50 disabled:bg-stone-100 disabled:text-stone-300">
                                        <i data-lucide="plus" class="w-3 h-3"></i>
                                    </button>
                                </div>
                                <div class="text-sm" :class="stock > 0 ? 'text-stone-500' : 'text-red-500'"
                                    x-text="stock>0?'Tersisa '+stock+' buah':'Stok habis'"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <form action="{{ route('checkout.index') }}" method="GET"
                        class="mt-8 flex flex-col sm:flex-row gap-4 w-full">
                        <!-- Hidden Inputs -->
                        <input type="hidden" name="keripik_id" value="{{ $keripik->id }}">
                        <input type="hidden" name="qty" x-bind:value="qty">

                        <div class="flex flex-1 gap-2 sm:gap-4">


                            <!-- Keranjang -->
                            <button type="button" @click="addToCart()" :disabled="stock === 0"
                                class="flex items-center justify-center gap-2 h-12 px-4 sm:px-6 rounded-lg border border-emerald-600 text-emerald-700 font-medium hover:bg-emerald-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed w-1/2 sm:w-auto flex-1">
                                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                                <span>Keranjang</span>
                            </button>
                        </div>

                        <!-- Beli Sekarang -->
                        <button type="submit" :disabled="stock === 0"
                            class="flex items-center justify-center h-12 px-10 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-emerald-600 disabled:bg-stone-300 disabled:cursor-not-allowed sm:w-auto w-full">
                            Beli Sekarang
                        </button>
                    </form>
                    </form>

                </div>
            </section>

            <!-- Product Description Section -->
            <section class="mt-4 rounded-sm bg-white p-6 shadow-sm">

                <!-- Bisa ditambah spesifikasi tambahan di sini jika ada -->

                <h2 class="mt-4 bg-stone-50 px-4 py-3 text-lg font-medium text-stone-900">Deskripsi Produk</h2>
                <p class="mt-4 whitespace-pre-line px-4 text-sm leading-relaxed text-stone-700">
                    {{ $keripik->deskripsi ?: 'Belum ada deskripsi untuk produk ini.' }}</p>
            </section>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
        </script>
</body>

</html>
