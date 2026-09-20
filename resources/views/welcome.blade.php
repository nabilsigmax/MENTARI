<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#DC2626">

    <title>Keripik Mentari - Oleh-Oleh Khas Malang</title>
    <meta name="description"
        content="Keripik Mentari: Keripik Buah & Tempe Asli Khas Malang. Renyah alami, tanpa pengawet, diproses dengan teknologi vacuum frying modern.">

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
                            'red-dark': '#991B1B',
                            gold: '#F59E0B',
                            green: '#10B981',
                            'green-dark': '#059669',
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

<body class="bg-stone-50 text-stone-800 font-sans antialiased selection:bg-mentari-red selection:text-white"
    x-data="{
        mobileMenu: false,
        cartOpen: false,
        selectedCategory: 'all',
        searchQuery: '',
        cart: [],
    
        // Auto Slider State
        currentSlide: 0,
        slideCount: 3,
        autoTimer: null,
    
        initSlider() {
            this.startTimer();
        },
        startTimer() {
            this.clearTimer();
            this.autoTimer = setInterval(() => {
                this.nextSlide();
            }, 4500);
        },
        clearTimer() {
            if (this.autoTimer) clearInterval(this.autoTimer);
        },
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.slideCount;
        },
        prevSlide() {
            this.currentSlide = (this.currentSlide - 1 + this.slideCount) % this.slideCount;
        },
        goToSlide(index) {
            this.currentSlide = index;
            this.startTimer();
        },
    
        products: {{ Illuminate\Support\Js::from(
            isset($keripiks)
                ? $keripiks->map(
                    fn($k) => [
                        'id' => $k->id,
                        'name' => $k->nama,
                        'category' => match ($k->kategori) {
                            'Keripik Buah' => 'buah',
                            'Keripik Tempe & Gurih' => 'gurih',
                            'Paket Bundling & Hampers' => 'bundling',
                        },
                        'weight' => $k->berat,
                        'price' => $k->harga,
                        'desc' => $k->deskripsi ?? '',
                        'image' =>
                            $k->gambar_url ?:
                            'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=600&q=80',
                    ],
                )
                : [
                    [
                        'id' => 1,
                        'name' => 'Keripik Apel Manalagi Super',
                        'category' => 'buah',
                        'weight' => '100 gram',
                        'price' => 25000,
                        'desc' => 'Apel Manalagi pilihan Kota Batu. Renyah, manis asam segar alami tanpa pemanis buatan.',
                        'image' => 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=600&q=80',
                    ],
                    [
                        'id' => 2,
                        'name' => 'Keripik Nangka Madu',
                        'category' => 'buah',
                        'weight' => '100 gram',
                        'price' => 28000,
                        'desc' => 'Aroma harum dan manis legit alami nangka madu tropis dengan tekstur garing renyah.',
                        'image' =>
                            'https://images.unsplash.com/photo-1587132137056-bfbf0166836e?auto=format&fit=crop&w=600&q=80',
                    ],
                    [
                        'id' => 3,
                        'name' => 'Keripik Tempe Daun Jeruk',
                        'category' => 'gurih',
                        'weight' => '150 gram',
                        'price' => 18000,
                        'desc' => 'Tempe kedelai khas Malang dengan irisan renyah dan bumbu rempah daun jeruk harum.',
                        'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=600&q=80',
                    ],
                    [
                        'id' => 4,
                        'name' => 'Keripik Salak Pondoh',
                        'category' => 'buah',
                        'weight' => '100 gram',
                        'price' => 24000,
                        'desc' => 'Kombinasi rasa manis segar buah salak pondoh dalam gigitan renyah berongga.',
                        'image' =>
                            'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?auto=format&fit=crop&w=600&q=80',
                    ],
                    [
                        'id' => 5,
                        'name' => 'Keripik Tempe Balado',
                        'category' => 'gurih',
                        'weight' => '150 gram',
                        'price' => 19000,
                        'desc' => 'Bumbu balado pedas manis gurih yang meresap sempurna pada keripik tempe renyah.',
                        'image' =>
                            'https://images.unsplash.com/photo-1626777552726-4a6b54c97e46?auto=format&fit=crop&w=600&q=80',
                    ],
                    [
                        'id' => 6,
                        'name' => 'Paket Oleh-Oleh Arema (3 Pcs)',
                        'category' => 'bundling',
                        'weight' => '350 gram',
                        'price' => 65000,
                        'desc' => '1x Keripik Apel + 1x Keripik Nangka + 1x Keripik Tempe Daun Jeruk + Tas Jinjing.',
                        'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=600&q=80',
                    ],
                ],
        ) }},
    
        addToCart(product) {
            let item = this.cart.find(i => i.id === product.id);
            if (item) {
                item.qty++;
            } else {
                this.cart.push({ ...product, qty: 1 });
            }
        },
    
        updateQty(index, delta) {
            this.cart[index].qty += delta;
            if (this.cart[index].qty <= 0) {
                this.cart.splice(index, 1);
            }
        },
    
        get cartTotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
        },
    
        get cartCount() {
            return this.cart.reduce((sum, item) => sum + item.qty, 0);
        },
    
        formatRupiah(num) {
            return 'Rp ' + Number(num).toLocaleString('id-ID');
        },
    
        checkoutWhatsApp() {
            if (this.cart.length === 0) return;
            let text = '*HALO KERIPIK MENTARI, SAYA INGIN ORDER:*\n\n';
            this.cart.forEach((item, i) => {
                text += `${i + 1}. ${item.name} (${item.weight}) x ${item.qty} = ${this.formatRupiah(item.price * item.qty)}\n`;
            });
            text += `\n*Total: ${this.formatRupiah(this.cartTotal)}*\n\nMohon informasi ketersediaan stok & ongkir. Terima kasih!`;
            window.open('https://wa.me/6281234567890?text=' + encodeURIComponent(text), '_blank');
        },
    
        directWA(product) {
            let text = `*HALO KERIPIK MENTARI, SAYA INGIN PESAN:*\n\nProduk: *${product.name}* (${product.weight})\nHarga: ${this.formatRupiah(product.price)}\nJumlah: 1 pcs\n\nMohon bantu proses pesanan saya. Terima kasih!`;
            window.open('https://wa.me/6281234567890?text=' + encodeURIComponent(text), '_blank');
        },
    
        get filteredProducts() {
            return this.products.filter(p => {
                const matchCategory = this.selectedCategory === 'all' || p.category === this.selectedCategory;
                const query = this.searchQuery.toLowerCase().trim();
                const matchSearch = !query || p.name.toLowerCase().includes(query) || p.desc.toLowerCase().includes(query);
                return matchCategory && matchSearch;
            });
        }
    }" x-init="initSlider()">

    <!-- TOP ANNOUNCEMENT BAR -->
    <div class="bg-emerald-500 text-white text-xs sm:text-sm py-2 px-4 font-medium tracking-wide overflow-hidden">
        <marquee behavior="scroll" direction="left" scrollamount="10">
            🛡️ Garansi Kerenyahan 100% & Ter sertifikasi Halal MUI: Kami Menjamin Keripik Apel Khas Malang Tetap
            Renyah,
            Aman, dan Higienis Sampai ke Tangan Anda dengan Pengiriman Ekstra Aman ke Seluruh Kota di Indonesia!
        </marquee>
    </div>

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
                        <span class="text-xs text-stone-500 font-semibold tracking-wide">Oleh-Oleh Khas Malang</span>
                    </div>
                </a>

                <!-- Nav Links -->
                <nav class="hidden lg:flex items-center gap-6 text-sm font-semibold text-stone-600">
                    <a href="#beranda" class="hover:text-mentari-red transition">Beranda</a>
                    <a href="#keunggulan" class="hover:text-mentari-red transition">Keunggulan</a>
                    <a href="#produk" class="hover:text-mentari-red transition">Produk</a>
                    <a href="#tentang" class="hover:text-mentari-red transition">Tentang Kami</a>
                    <a href="#kontak" class="hover:text-mentari-red transition">Kontak</a>
                </nav>

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
                        class="rounded-lg p-2 text-stone-600 transition hover:bg-stone-100 lg:hidden" title="Buka menu">
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
                class="block rounded-lg px-3 py-2 font-semibold text-stone-700 hover:bg-stone-50">Lokasi & Kontak</a>
            <a href="https://wa.me/6281234567890?text=Halo%20Keripik%20Mentari,%20saya%20tertarik%20untuk%20pesan%20oleh-oleh%20khas%20Malang."
                target="_blank"
                class="flex items-center justify-center gap-2 rounded-lg bg-mentari-red px-3 py-2.5 font-bold text-white">
                <i data-lucide="message-circle" class="h-4 w-4"></i>
                <span>Pesan di WA</span>
            </a>
        </div>
    </header>

    <!-- AUTO-SLIDING HERO BANNER CAROUSEL -->
    <section id="beranda" class="relative max-w-6xl mx-auto px-4 sm:px-6 py-6 md:py-8" x-data="{
        currentSlide: 0,
        slideCount: 3,
        timer: null,
    
        startTimer() {
            this.timer = setInterval(() => {
                this.nextSlide();
            }, 5000);
        },
        clearTimer() {
            clearInterval(this.timer);
        },
        nextSlide() {
            this.currentSlide = (this.currentSlide === this.slideCount - 1) ? 0 : this.currentSlide + 1;
        },
        prevSlide() {
            this.currentSlide = (this.currentSlide === 0) ? this.slideCount - 1 : this.currentSlide - 1;
        },
        goToSlide(index) {
            this.currentSlide = index;
        }
    }"
        x-init="startTimer()" @mouseenter="clearTimer()" @mouseleave="startTimer()">
        <div
            class="relative overflow-hidden rounded-3xl shadow-sm border border-stone-200/80 min-h-[550px] sm:min-h-[420px] md:min-h-[460px]">
            <div class="flex w-full h-full transition-transform duration-500 ease-in-out absolute inset-0"
                :style="`transform: translateX(-${currentSlide * 100}%);`">
                <!-- SLIDE 1: KERIPIK TERLARIS -->
                <div
                    class="w-full h-full flex-shrink-0 p-6 sm:p-10 md:p-14 bg-gradient-to-r from-[#fff8e1] via-[#ffecb3] to-[#ffe082] flex items-center justify-between">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center w-full">
                        <div class="md:col-span-7 space-y-3 sm:space-y-4 text-left">
                            <span
                                class="inline-block text-xs sm:text-sm font-bold text-amber-900 uppercase tracking-wider bg-amber-200/80 px-3 py-1 rounded-full">
                                ⭐ Paling Dicari Pelanggan
                            </span>
                            <h2
                                class="text-3xl sm:text-5xl md:text-6xl font-black text-stone-900 tracking-tight leading-none">
                                Koleksi Keripik <br>
                                <span class="text-mentari-red">Terlaris khas Malang</span>
                            </h2>
                            <p class="text-stone-700 text-sm sm:text-base font-medium max-w-lg leading-relaxed">
                                Nikmati deretan varian oleh-oleh favorit pilihan jutaan wisatawan. Dibuat dari bahan
                                premium berkualitas tinggi dengan kerenyahan tiada tanding.
                            </p>
                            <div class="pt-2">
                                <a href="/produk/terlaris"
                                    class="inline-flex items-center gap-2 bg-stone-900 hover:bg-black text-white px-6 py-3 rounded-full font-bold text-xs sm:text-sm shadow-md transition">
                                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                    <span>Lihat Detail Keripik Terlaris</span>
                                </a>
                            </div>
                        </div>
                        <div class="md:col-span-5 flex items-center justify-center">
                            <div
                                class="relative bg-white/95 p-4 sm:p-6 rounded-3xl shadow-xl border border-amber-300 max-w-sm text-center">
                                <img src="{{ asset('images/logo.png') }}" alt="Keripik Mentari"
                                    class="h-16 w-auto object-contain mx-auto mb-2">
                                <img src="/images/keripik-nangka.jpeg" alt="Keripik Terlaris"
                                    class="w-full h-36 sm:h-44 object-cover rounded-2xl shadow-inner">
                                <div class="mt-3 flex items-center justify-around text-xs font-bold text-stone-700">
                                    <span>Pilihan Utama</span><span>•</span><span>Renyah
                                        Gurih</span><span>•</span><span>Halal MUI</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SLIDE 2: KERIPIK BAKSO ORIGINAL & PEDAS -->
                <div
                    class="w-full h-full flex-shrink-0 p-6 sm:p-10 md:p-14 bg-gradient-to-r from-[#ffebee] via-[#ffcdd2] to-[#ef9a9a] flex items-center justify-between">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center w-full">
                        <div class="md:col-span-7 space-y-3 sm:space-y-4 text-left">
                            <span
                                class="inline-block text-xs sm:text-sm font-bold text-red-900 uppercase tracking-wider bg-red-200/80 px-3 py-1 rounded-full">
                                🔥 Sensasi Gurih & Pedas Mantap
                            </span>
                            <h2
                                class="text-3xl sm:text-5xl md:text-6xl font-black text-stone-900 tracking-tight leading-none">
                                Keripik Bakso <br>
                                <span class="text-mentari-red">Original & Pedas</span>
                            </h2>
                            <p class="text-stone-700 text-sm sm:text-base font-medium max-w-lg leading-relaxed">
                                Sensasi makan bakso khas Malang dalam wujud keripik renyah! Tersedia varian Original
                                gurih bumbu rempah dan Pedas cabai asli yang membakar lidah.
                            </p>
                            <div class="pt-2">
                                <a href="/produk/keripik-bakso"
                                    class="inline-flex items-center gap-2 bg-mentari-red hover:bg-red-700 text-white px-6 py-3 rounded-full font-bold text-xs sm:text-sm shadow-md transition">
                                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                    <span>Lihat Detail Keripik Bakso</span>
                                </a>
                            </div>
                        </div>
                        <div class="md:col-span-5 flex items-center justify-center relative">
                            <div class="flex items-center justify-center gap-3 sm:gap-4">
                                <!-- Variant Original -->
                                <a href="/produk/keripik-bakso-original" class="block group">
                                    <div
                                        class="relative bg-white/90 p-3 rounded-2xl shadow-md border border-red-200 text-center w-36 sm:w-44 transition group-hover:scale-105">
                                        <span
                                            class="absolute -top-2.5 -right-2 bg-amber-500 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow">Original</span>
                                        <img src="/images/keripik-bakso-original.jpeg" alt="Keripik Bakso Original"
                                            class="w-full h-24 sm:h-32 object-cover rounded-xl mb-2">
                                        <div
                                            class="font-extrabold text-xs text-stone-900 group-hover:text-mentari-red transition">
                                            Bakso Original</div>
                                        <div class="text-[10px] text-stone-500">Gurih Rempah</div>
                                    </div>
                                </a>
                                <!-- Variant Pedas -->
                                <a href="/produk/keripik-bakso-pedas" class="block group">
                                    <div
                                        class="relative bg-white/90 p-3 rounded-2xl shadow-md border-2 border-mentari-red text-center w-36 sm:w-44 transition group-hover:scale-105">
                                        <span
                                            class="absolute -top-2.5 -right-2 bg-mentari-red text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow">Pedas
                                            🔥</span>
                                        <img src="/images/keripik-bakso-pedas.jpeg" alt="Keripik Bakso Pedas"
                                            class="w-full h-24 sm:h-32 object-cover rounded-xl mb-2">
                                        <div
                                            class="font-extrabold text-xs text-stone-900 group-hover:text-mentari-red transition">
                                            Bakso Pedas</div>
                                        <div class="text-[10px] text-stone-500">Cabai Asli</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SLIDE 3: KERIPIK MIX BUAH -->
                <div
                    class="w-full h-full flex-shrink-0 p-6 sm:p-10 md:p-14 bg-gradient-to-r from-[#e8f5e9] via-[#f1f8e9] to-[#dcedc8] flex items-center justify-between">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center w-full">
                        <div class="md:col-span-7 space-y-3 sm:space-y-4 text-left">
                            <span
                                class="inline-block text-xs sm:text-sm font-bold text-emerald-800 uppercase tracking-wider bg-emerald-200/80 px-3 py-1 rounded-full">
                                🍍 100% Buah Asli Vacuum Frying
                            </span>
                            <h2
                                class="text-3xl sm:text-5xl md:text-6xl font-black text-emerald-950 tracking-tight leading-none">
                                Keripik Mix <br>
                                <span class="text-emerald-700">Buah Spesial</span>
                            </h2>
                            <p class="text-stone-600 text-xs sm:text-sm pt-1 max-w-md leading-relaxed">
                                Kombinasi aneka buah manis segar khas daerah dalam satu kemasan! Tanpa pemanis buatan,
                                diproses hygienis untuk menjaga nutrisi dan kerenyahan alami buah.
                            </p>
                            <div class="pt-2">
                                <a href="/produk/keripik-mix-buah"
                                    class="inline-flex items-center gap-2 bg-emerald-700 hover:bg-emerald-800 text-white px-6 py-3 rounded-full font-bold text-xs sm:text-sm shadow-md transition">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    <span>Lihat Detail Mix Buah</span>
                                </a>
                            </div>
                        </div>
                        <div class="md:col-span-5 flex items-center justify-center">
                            <div
                                class="relative bg-white/95 p-4 sm:p-6 rounded-3xl shadow-xl border border-emerald-300 max-w-sm text-center">
                                <img src="{{ asset('images/logo.png') }}" alt="Keripik Mentari"
                                    class="h-16 w-auto object-contain mx-auto mb-2">
                                <img src="/images/keripik-mix.jpeg" alt="Keripik Mix Buah"
                                    class="w-full h-36 sm:h-44 object-cover rounded-2xl shadow-inner">
                                <div class="mt-3 flex items-center justify-around text-xs font-bold text-stone-700">
                                    <span>Aneka Buah</span><span>•</span><span>0%
                                        Pemanis</span><span>•</span><span>Sehat & Alami</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NAVIGATION BUTTONS -->
            <button type="button" @click="prevSlide()"
                class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-white/80 hover:bg-white text-stone-800 shadow-md flex items-center justify-center transition hover:scale-105 z-20">
                <i data-lucide="chevron-left" class="w-5 h-5 sm:w-6 sm:h-6"></i>
            </button>
            <button type="button" @click="nextSlide()"
                class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-white/80 hover:bg-white text-stone-800 shadow-md flex items-center justify-center transition hover:scale-105 z-20">
                <i data-lucide="chevron-right" class="w-5 h-5 sm:w-6 sm:h-6"></i>
            </button>

            <!-- INDICATORS -->
            <div
                class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 bg-white/80 backdrop-blur px-3 py-1.5 rounded-full shadow-sm z-20">
                <template x-for="i in slideCount" :key="i">
                    <button type="button" @click="goToSlide(i - 1)"
                        class="h-2 rounded-full transition-all duration-300"
                        :class="currentSlide === (i - 1) ? 'w-6 bg-mentari-red' : 'w-2 bg-stone-300 hover:bg-stone-400'"></button>
                </template>
            </div>
        </div>
    </section>

    <!-- SECTION TITLE & SEARCH BAR -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-6 text-center">
        <h3 class="text-xl sm:text-2xl font-black text-stone-900 tracking-tight">Varian Best Seller Khas Malang</h3>
        <p class="text-xs sm:text-sm text-stone-500 mt-1">Pilihan favorit wisatawan dan pembeli setia Keripik Mentari
        </p>

        <!-- Search Input di Bawah Header Katalogg -->
        <div class="mt-6 max-w-md mx-auto relative">
            <input type="text" x-model="searchQuery" placeholder="Cari keripik favoritmu..."
                class="w-full pl-10 pr-10 py-2.5 text-xs sm:text-sm rounded-full border border-stone-300 bg-white shadow-xs focus:outline-none focus:border-mentari-red focus:ring-1 focus:ring-mentari-red transition">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-stone-400">
                <i data-lucide="search" class="w-4 h-4"></i>
            </div>
            <button x-show="searchQuery.length > 0" @click="searchQuery = ''"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone-400 hover:text-stone-600"
                title="Hapus pencarian">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- PRODUCT CATALOG -->
    <section id="produk" class="py-8 bg-stone-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            <!-- Category Filter Buttons -->
            <div
                class="flex items-center justify-center gap-1.5 bg-white p-1 rounded-xl text-xs font-bold border border-stone-200/80 shadow-xs mb-6 w-fit mx-auto">
                <button @click="selectedCategory = 'all'"
                    :class="selectedCategory === 'all' ? 'bg-mentari-green text-white' :
                        'text-stone-600 hover:text-stone-900'"
                    class="px-3.5 py-1.5 rounded-lg transition">
                    Semua
                </button>
                <button @click="selectedCategory = 'buah'"
                    :class="selectedCategory === 'buah' ? 'bg-mentari-green text-white' :
                        'text-stone-600 hover:text-stone-900'"
                    class="px-3.5 py-1.5 rounded-lg transition">
                    Keripik Buah
                </button>
                <button @click="selectedCategory = 'gurih'"
                    :class="selectedCategory === 'gurih' ? 'bg-mentari-green text-white' :
                        'text-stone-600 hover:text-stone-900'"
                    class="px-3.5 py-1.5 rounded-lg transition">
                    Tempe & Gurih
                </button>

            </div>

            <!-- Empty State -->
            <div x-show="filteredProducts.length === 0"
                class="text-center py-12 bg-white rounded-2xl border border-stone-200/80 shadow-xs my-4">
                <i data-lucide="search-x" class="w-12 h-12 text-stone-300 mx-auto mb-3"></i>
                <h4 class="font-bold text-stone-700 text-sm">Produk Tidak Ditemukan</h4>
                <p class="text-xs text-stone-500 mt-1">Coba gunakan kata kunci lain di pencarian atau pilih kategori
                    yang berbeda.</p>
                <button @click="searchQuery = ''; selectedCategory = 'all'"
                    class="mt-4 text-xs text-mentari-red font-bold hover:underline">
                    Tampilkan Semua Produk
                </button>
            </div>

            <!-- Products Grid -->
            <div x-show="filteredProducts.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div
                        class="bg-white rounded-2xl border border-stone-200/90 overflow-hidden flex flex-col justify-between hover:border-stone-400 transition shadow-xs">

                        <div class="aspect-[16/10] overflow-hidden bg-stone-100">
                            <img :src="product.image" :alt="product.name" class="w-full h-full object-cover">
                        </div>

                        <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between text-xs text-stone-500 mb-1">
                                    <span x-text="'Netto: ' + product.weight"></span>
                                    <span class="text-emerald-700 font-bold">Ready Stock</span>
                                </div>
                                <h3 class="font-bold text-stone-900 text-base" x-text="product.name"></h3>
                                <p class="text-stone-600 text-xs mt-1 leading-relaxed" x-text="product.desc"></p>
                            </div>

                            <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                                <div class="font-extrabold text-mentari-red text-base"
                                    x-text="formatRupiah(product.price)"></div>

                                <div class="flex items-center gap-1.5">
                                    <button type="button" @click="addToCart(product)"
                                        class="flex items-center justify-center gap-1.5 h-9 px-3 rounded-lg border border-emerald-600 text-emerald-700 text-xs font-bold hover:bg-emerald-50 transition-colors"
                                        title="Tambah ke Keranjang">
                                        <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                                        <span>Keranjang</span>
                                    </button>
                                    <a :href="'/keripik/' + product.id"
                                        class="flex items-center justify-center gap-1.5 h-9 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors"
                                        title="Lihat detail produk">
                                        <span>Detail</span>
                                        <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </template>
            </div>

        </div>
    </section>

    <!-- KEUNGGULAN -->
    <section id="keunggulan" class="py-16 bg-white border-t border-stone-200/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-stone-900">Kenapa Memilih Keripik Mentari?</h2>
                <p class="text-stone-600 text-sm mt-2">Kualitas rasa dan kerenyahan yang selalu terjaga untuk Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-stone-50 p-6 rounded-2xl border border-stone-200/80 shadow-xs">
                    <div
                        class="w-10 h-10 rounded-xl bg-red-100 text-mentari-red flex items-center justify-center mb-4 font-bold">
                        <i data-lucide="leaf" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-stone-900 text-base mb-1">Buah Segar Pilihan</h3>
                    <p class="text-stone-600 text-xs sm:text-sm leading-relaxed">
                        Keripik renyah dari 100% buah segar pilihan, diolah tanpa pengawet untuk camilan sehat, lezat,
                        dan praktis setiap saat.
                    </p>
                </div>

                <div class="bg-stone-50 p-6 rounded-2xl border border-stone-200/80 shadow-xs">
                    <div
                        class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center mb-4 font-bold">
                        <i data-lucide="zap" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-stone-900 text-base mb-1">Proses Vacuum Frying</h3>
                    <p class="text-stone-600 text-xs sm:text-sm leading-relaxed">
                        Suhu rendah menjaga nutrisi, rasa manis asli, serta warna buah tetap cerah tanpa minyak
                        berlebih.
                    </p>
                </div>

                <div class="bg-stone-50 p-6 rounded-2xl border border-stone-200/80 shadow-xs">
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center mb-4 font-bold">
                        <i data-lucide="shield-check" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-stone-900 text-base mb-1">Kemasan Aman & Tahan Lama</h3>
                    <p class="text-stone-600 text-xs sm:text-sm leading-relaxed">
                        Aluminium foil kedap udara ber-zipper tebal, menjaga kerenyahan keripik hingga berbulan-bulan.
                    </p>
                </div>
            </div>

        </div>
    </section>

    <!-- ABOUT & OUTLET -->
    <section id="tentang" class="py-16 bg-stone-50 border-t border-stone-200/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">

                <div class="space-y-4">
                    <span class="text-xs font-bold text-mentari-red uppercase tracking-wider">Tentang Kami</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-stone-900">Dedikasi Keripik Mentari</h2>
                    <p class="text-stone-600 text-sm leading-relaxed">
                        Kami adalah produsen oleh-oleh khas Malang yang berfokus pada cita rasa autentik dan keaslian
                        bahan. Dengan bermitra bersama petani lokal Kota Batu & Malang, kami memastikan hasil panen
                        terbaik diolah secara higienis hingga sampai ke tangan Anda.
                    </p>
                    <div class="space-y-2 text-xs text-stone-700 font-medium">
                        <div class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                            <span>Bahan 100% buah asli tanpa pemanis buatan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                            <span>Pengiriman aman ke seluruh wilayah Kota Malang</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-emerald-600"></i>
                            <span>Melayani pemesanan eceran, hampers, & partai besar</span>
                        </div>
                    </div>
                </div>

                <!-- Outlet Info Box -->
                <div id="kontak" class="space-y-4">
                    <h3 class="font-bold text-stone-900 text-lg">Outlet & Pemesanan</h3>
                    <p class="text-sm leading-relaxed text-stone-600">Keripik Mentari tersedia di berbagai toko
                        oleh-oleh pilihan di Malang dan Batu.</p>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                        <a href="https://www.google.com/maps/search/?api=1&query=Buah+Tangan+Jl.+Ir.+Soekarno+No.+374+Kota+Batu"
                            target="_blank" rel="noopener noreferrer"
                            class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs transition hover:border-red-200 hover:shadow-sm">
                            <div class="flex items-start gap-3"><i data-lucide="map-pin"
                                    class="h-5 w-5 shrink-0 text-mentari-red"></i>
                                <div>
                                    <h4 class="text-sm font-bold text-stone-900">Buah Tangan</h4>
                                    <p class="mt-1 text-xs leading-relaxed text-stone-600">Jl. Ir. Soekarno No. 374,
                                        Mojorejo, Kota Batu</p>
                                </div>
                            </div>
                        </a>
                        <a href="https://www.google.com/maps/search/?api=1&query=Goedang+Oleh+Oleh+Jl.+Simpang+Tenaga+Selatan+II+No.+12+Malang"
                            target="_blank" rel="noopener noreferrer"
                            class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs transition hover:border-red-200 hover:shadow-sm">
                            <div class="flex items-start gap-3"><i data-lucide="map-pin"
                                    class="h-5 w-5 shrink-0 text-mentari-red"></i>
                                <div>
                                    <h4 class="text-sm font-bold text-stone-900">Goedang Oleh-Oleh</h4>
                                    <p class="mt-1 text-xs leading-relaxed text-stone-600">Jl. Simpang Tenaga Selatan
                                        II No. 12, Blimbing, Kota Malang</p>
                                </div>
                            </div>
                        </a>
                        <a href="https://www.google.com/maps/search/?api=1&query=Manalagi+Strudel+Jl.+Borobudur+Agung+No.+22C+Malang"
                            target="_blank" rel="noopener noreferrer"
                            class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs transition hover:border-red-200 hover:shadow-sm">
                            <div class="flex items-start gap-3"><i data-lucide="map-pin"
                                    class="h-5 w-5 shrink-0 text-mentari-red"></i>
                                <div>
                                    <h4 class="text-sm font-bold text-stone-900">Manalagi Strudel</h4>
                                    <p class="mt-1 text-xs leading-relaxed text-stone-600">Jl. Borobudur Agung No. 22C,
                                        Mojolangu, Kota Malang</p>
                                </div>
                            </div>
                        </a>
                        <a href="https://www.google.com/maps/search/?api=1&query=Lapis+Tugu+Karangploso"
                            target="_blank" rel="noopener noreferrer"
                            class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs transition hover:border-red-200 hover:shadow-sm">
                            <div class="flex items-start gap-3"><i data-lucide="map-pin"
                                    class="h-5 w-5 shrink-0 text-mentari-red"></i>
                                <div>
                                    <h4 class="text-sm font-bold text-stone-900">Lapis Tugu Karangploso</h4>
                                    <p class="mt-1 text-xs leading-relaxed text-stone-600">Jl. Raya Takeran No. 3A,
                                        Ngijo, Karangploso</p>
                                </div>
                            </div>
                        </a>
                        <a href="https://www.google.com/maps/search/?api=1&query=Lapis+Tugu+Pujon" target="_blank"
                            rel="noopener noreferrer"
                            class="rounded-2xl border border-stone-200 bg-white p-4 shadow-xs transition hover:border-red-200 hover:shadow-sm sm:col-span-2">
                            <div class="flex items-start gap-3"><i data-lucide="map-pin"
                                    class="h-5 w-5 shrink-0 text-mentari-red"></i>
                                <div>
                                    <h4 class="text-sm font-bold text-stone-900">Lapis Tugu Pujon</h4>
                                    <p class="mt-1 text-xs leading-relaxed text-stone-600">Jl. Abdul Manan Wijaya, Ruko
                                        A, Pandesari, Pujon, Kabupaten Malang</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SIMPLE CART SLIDE-OVER -->
    <div x-show="cartOpen" class="fixed inset-0 z-50 overflow-hidden" x-cloak>
        <div @click="cartOpen = false" class="fixed inset-0 bg-black/40 transition-opacity"></div>
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div class="w-screen max-w-sm bg-white shadow-xl flex flex-col justify-between">

                <div class="p-5 border-b border-stone-200 flex items-center justify-between">
                    <h3 class="font-bold text-stone-900 text-base">Keranjang Pesanan</h3>
                    <button @click="cartOpen = false" class="text-stone-400 hover:text-stone-700 p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="p-5 flex-1 overflow-y-auto space-y-3">
                    <template x-if="cart.length === 0">
                        <div class="text-center py-12 text-stone-400 text-xs">
                            <i data-lucide="shopping-bag" class="w-10 h-10 mx-auto mb-2 opacity-30"></i>
                            <p class="font-medium text-stone-600">Keranjang masih kosong</p>
                        </div>
                    </template>

                    <template x-for="(item, index) in cart" :key="item.id">
                        <div
                            class="flex items-center justify-between p-3 bg-stone-50 rounded-xl border border-stone-200">
                            <div>
                                <h4 class="font-bold text-xs text-stone-900" x-text="item.name"></h4>
                                <div class="text-[11px] text-stone-500"
                                    x-text="formatRupiah(item.price) + ' x ' + item.qty"></div>
                            </div>
                            <div
                                class="flex items-center gap-2 bg-white px-2 py-0.5 rounded-lg border border-stone-200 text-xs">
                                <button @click="updateQty(index, -1)" class="text-stone-600 font-bold px-1">-</button>
                                <span class="font-bold" x-text="item.qty"></span>
                                <button @click="updateQty(index, 1)" class="text-stone-600 font-bold px-1">+</button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="p-5 bg-stone-50 border-t border-stone-200 space-y-3" x-show="cart.length > 0">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-stone-600">Total:</span>
                        <span class="font-bold text-base text-mentari-red" x-text="formatRupiah(cartTotal)"></span>
                    </div>
                    <button @click="checkoutWhatsApp()"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-bold text-xs transition">
                        Kirim Pesanan ke WhatsApp
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- CLEAN FOOTER -->
    <!-- ENHANCED FOOTER -->
    <footer class="bg-stone-900 text-stone-300 pt-14 pb-6 mt-4">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 pb-10 border-b border-stone-700/60">

                <!-- Brand -->
                <div class="space-y-4 lg:col-span-1">
                    <a href="#" class="flex items-center gap-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Keripik Mentari Logo"
                            class="h-11 w-auto object-contain bg-white rounded-lg p-1">
                        <div>
                            <span
                                class="text-lg font-black tracking-tight text-white block leading-tight">MENTARI</span>
                            <span class="text-[11px] text-stone-400 font-semibold tracking-wide">Oleh-Oleh Khas
                                Malang</span>
                        </div>
                    </a>
                    <p class="text-xs leading-relaxed text-stone-400">
                        Produsen keripik buah & tempe asli khas Malang. Renyah alami, tanpa pengawet, diproses dengan
                        teknologi vacuum frying modern.
                    </p>
                    <div class="flex items-center gap-2 pt-1">
                        <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                            class="w-9 h-9 rounded-full bg-stone-800 hover:bg-mentari-red flex items-center justify-center transition"
                            title="WhatsApp">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                        </a>
                        <a href="#"
                            class="w-9 h-9 rounded-full bg-stone-800 hover:bg-mentari-red flex items-center justify-center transition"
                            title="Instagram">
                            <i data-lucide="instagram" class="w-4 h-4"></i>
                        </a>
                        <a href="#"
                            class="w-9 h-9 rounded-full bg-stone-800 hover:bg-mentari-red flex items-center justify-center transition"
                            title="Facebook">
                            <i data-lucide="facebook" class="w-4 h-4"></i>
                        </a>
                        <a href="#"
                            class="w-9 h-9 rounded-full bg-stone-800 hover:bg-mentari-red flex items-center justify-center transition"
                            title="TikTok">
                            <i data-lucide="music-2" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Navigasi -->
                <div class="space-y-3">
                    <h4 class="text-white font-bold text-sm">Navigasi</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#beranda" class="hover:text-mentari-red transition">Beranda</a></li>
                        <li><a href="#keunggulan" class="hover:text-mentari-red transition">Keunggulan</a></li>
                        <li><a href="#produk" class="hover:text-mentari-red transition">Produk</a></li>
                        <li><a href="#tentang" class="hover:text-mentari-red transition">Tentang Kami</a></li>
                        <li><a href="#kontak" class="hover:text-mentari-red transition">Outlet & Kontak</a></li>
                    </ul>
                </div>

                <!-- Kategori Produk -->
                <div class="space-y-3">
                    <h4 class="text-white font-bold text-sm">Kategori Produk</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#produk" class="hover:text-mentari-red transition">Keripik Buah</a></li>
                        <li><a href="#produk" class="hover:text-mentari-red transition">Tempe & Gurih</a></li>
                        <li><a href="#produk" class="hover:text-mentari-red transition">Paket Bundling & Hampers</a>
                        </li>
                        <li><a href="/produk/keripik-bakso" class="hover:text-mentari-red transition">Keripik
                                Bakso</a></li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div class="space-y-3">
                    <h4 class="text-white font-bold text-sm">Hubungi Kami</h4>
                    <ul class="space-y-3 text-xs">
                        <li class="flex items-start gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 shrink-0 text-mentari-red mt-0.5"></i>
                            <span>Malang & Batu, Jawa Timur, Indonesia</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="phone" class="w-4 h-4 shrink-0 text-mentari-red mt-0.5"></i>
                            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                                class="hover:text-mentari-red transition">+62 812-3456-7890</a>
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="mail" class="w-4 h-4 shrink-0 text-mentari-red mt-0.5"></i>
                            <span>halo@keripikmentari.id</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i data-lucide="clock" class="w-4 h-4 shrink-0 text-mentari-red mt-0.5"></i>
                            <span>Setiap Hari, 08.00 - 20.00 WIB</span>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Bottom Bar -->
            <div
                class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-[11px] text-stone-500 text-center sm:text-left">
                <p>&copy; {{ date('Y') }} <strong class="text-stone-300">Keripik Mentari Malang</strong>. Seluruh
                    Hak Cipta Dilindungi.</p>
                <div class="flex items-center gap-4">
                    <span class="flex items-center gap-1.5">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-500"></i>
                        Halal MUI Certified
                    </span>
                    @auth
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.keripik.index') }}"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-stone-800 px-3 py-1.5 font-bold text-stone-200 transition hover:bg-stone-700">
                                <i data-lucide="shield-check" class="h-3.5 w-3.5 text-mentari-red"></i>
                                <span>Dashboard Admin</span>
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

        </div>
    </footer>

    <!-- FLOATING CHAT WIDGET -->
    <div x-data="{
        chatOpen: false,
        chatMessages: [],
        chatSessionId: null,
        newMessage: '',
        customerName: '',
        nameSubmitted: false,
        loading: false,
        pollTimer: null,
    
        async initChat() {
            // Try to load existing session messages
            try {
                const res = await fetch('{{ route('chat.messages') }}');
                const data = await res.json();
                this.chatSessionId = data.session_id;
                this.chatMessages = data.messages;
                if (this.chatMessages.length > 0) {
                    this.nameSubmitted = true;
                }
                this.$nextTick(() => this.scrollToBottom());
            } catch (e) {
                console.error('Chat init error:', e);
            }
        },
    
        startPolling() {
            this.pollTimer = setInterval(async () => {
                if (!this.chatOpen) return;
                try {
                    const res = await fetch('{{ route('chat.messages') }}');
                    const data = await res.json();
                    if (data.messages.length !== this.chatMessages.length) {
                        this.chatMessages = data.messages;
                        this.$nextTick(() => this.scrollToBottom());
                    }
                } catch (e) {}
            }, 5000);
        },
    
        stopPolling() {
            if (this.pollTimer) {
                clearInterval(this.pollTimer);
                this.pollTimer = null;
            }
        },
    
        async sendMessage() {
            if (!this.newMessage.trim()) return;
            this.loading = true;
            try {
                const res = await fetch('{{ route('chat.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        message: this.newMessage,
                        customer_name: this.customerName || 'Pengunjung',
                    }),
                });
                const data = await res.json();
                if (data.success) {
                    this.chatMessages.push(data.message);
                    this.newMessage = '';
                    this.$nextTick(() => this.scrollToBottom());
                }
            } catch (e) {
                console.error('Send error:', e);
            } finally {
                this.loading = false;
            }
        },
    
        submitName() {
            if (this.customerName.trim()) {
                this.nameSubmitted = true;
            }
        },
    
        scrollToBottom() {
            const el = this.$refs.chatBody;
            if (el) el.scrollTop = el.scrollHeight;
        },
    
        toggleChat() {
            this.chatOpen = !this.chatOpen;
            if (this.chatOpen) {
                this.initChat();
                this.startPolling();
                this.$nextTick(() => this.scrollToBottom());
            } else {
                this.stopPolling();
            }
        }
    }" class="fixed bottom-6 right-6 z-50">
        <!-- Chat Button -->
        <button x-show="!chatOpen" @click="toggleChat()"
            class="w-14 h-14 rounded-full bg-mentari-red hover:bg-red-700 text-white shadow-lg flex items-center justify-center transition-all hover:scale-110"
            title="Chat dengan kami">
            <i data-lucide="message-circle" class="w-6 h-6"></i>
        </button>

        <!-- Chat Box -->
        <div x-show="chatOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95" x-cloak
            class="w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-stone-200 overflow-hidden flex flex-col"
            style="height: 480px;">
            <!-- Header -->
            <div class="bg-mentari-red text-white px-5 py-4 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                        <i data-lucide="headphones" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">Chat Keripik Mentari</h4>
                        <p class="text-[11px] text-red-100">Kami siap membantu Anda</p>
                    </div>
                </div>
                <button @click="toggleChat()" class="p-1 hover:bg-white/20 rounded-lg transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Name Input (shown first time) -->
            <template x-if="!nameSubmitted">
                <div class="flex-1 flex items-center justify-center p-6">
                    <div class="text-center space-y-4 w-full">
                        <div
                            class="w-16 h-16 rounded-full bg-red-50 text-mentari-red flex items-center justify-center mx-auto">
                            <i data-lucide="user" class="w-8 h-8"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-stone-800">Selamat datang!</h4>
                            <p class="text-xs text-stone-500 mt-1">Masukkan nama Anda untuk mulai chat.</p>
                        </div>
                        <div>
                            <input type="text" x-model="customerName" @keydown.enter="submitName()"
                                placeholder="Nama Anda..."
                                class="w-full px-4 py-2.5 rounded-xl border border-stone-200 text-sm focus:outline-none focus:ring-2 focus:ring-mentari-red/30 focus:border-mentari-red">
                        </div>
                        <button @click="submitName()" :disabled="!customerName.trim()"
                            class="w-full bg-mentari-red hover:bg-red-700 disabled:bg-stone-300 text-white px-4 py-2.5 rounded-xl text-sm font-bold transition">
                            Mulai Chat
                        </button>
                    </div>
                </div>
            </template>

            <!-- Chat Messages -->
            <template x-if="nameSubmitted">
                <div class="flex-1 flex flex-col overflow-hidden">
                    <div x-ref="chatBody" class="flex-1 overflow-y-auto p-4 space-y-3">
                        <!-- Welcome message -->
                        <div class="flex justify-start" x-show="chatMessages.length === 0">
                            <div
                                class="bg-stone-100 text-stone-700 rounded-2xl rounded-bl-sm px-4 py-2.5 max-w-[80%] shadow-xs">
                                <p class="text-xs leading-relaxed">Halo <span x-text="customerName"></span>! 👋 Ada
                                    yang bisa kami bantu? Silakan kirim pesan Anda.</p>
                                <p class="text-[10px] text-stone-400 mt-1">Admin · Sekarang</p>
                            </div>
                        </div>

                        <template x-for="msg in chatMessages" :key="msg.id">
                            <div :class="msg.is_admin ? 'flex justify-start' : 'flex justify-end'">
                                <div :class="msg.is_admin ?
                                    'bg-stone-100 text-stone-800 rounded-2xl rounded-bl-sm' :
                                    'bg-mentari-red text-white rounded-2xl rounded-br-sm'"
                                    class="px-4 py-2.5 max-w-[80%] shadow-xs">
                                    <p class="text-xs leading-relaxed" x-text="msg.message"></p>
                                    <p class="text-[10px] mt-1"
                                        :class="msg.is_admin ? 'text-stone-400' : 'text-red-200'"
                                        x-text="(msg.is_admin ? 'Admin' : 'Anda') + ' · ' + msg.created_at"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Send Message -->
                    <div class="px-4 py-3 bg-stone-50 border-t border-stone-200 shrink-0">
                        <div class="flex items-center gap-2">
                            <input type="text" x-model="newMessage" @keydown.enter="sendMessage()"
                                placeholder="Ketik pesan..." :disabled="loading"
                                class="flex-1 px-4 py-2.5 rounded-xl border border-stone-200 text-xs focus:outline-none focus:ring-2 focus:ring-mentari-red/30 focus:border-mentari-red transition">
                            <button @click="sendMessage()" :disabled="loading || !newMessage.trim()"
                                class="w-10 h-10 rounded-xl bg-mentari-red hover:bg-red-700 disabled:bg-stone-300 text-white flex items-center justify-center transition shrink-0">
                                <i data-lucide="send" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </template>
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
