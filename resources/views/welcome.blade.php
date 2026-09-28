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

    @php
        $chatIsAuth = auth()->check();
        $chatAuthName = $chatIsAuth ? auth()->user()->name : '';
    @endphp
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('chatWidget', () => ({
                chatOpen: false,
                chatMessages: [],
                chatSessionId: null,
                newMessage: '',
                customerName: @json($chatAuthName),
                nameSubmitted: @json($chatIsAuth),
                loading: false,
                pollTimer: null,

                async initChat() {
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
                },
            }));
        });
    </script>

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
    
        buatPesanan() {
            if (this.cart.length === 0) return;
            const items = this.cart.map(item => ({ id: item.id, qty: item.qty }));
            window.location.href = '{{ route('checkout.cart') }}?items=' + encodeURIComponent(JSON.stringify(items));
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
    <div class="bg-mentari-green text-white text-xs sm:text-sm py-2 px-4 font-medium tracking-wide overflow-hidden">
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
                                    class="inline-flex items-center gap-2 bg-mentari-green hover:bg-mentari-green-dark text-white px-6 py-3 rounded-full font-bold text-xs sm:text-sm shadow-md transition">
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
                                    class="inline-flex items-center gap-2 bg-mentari-green hover:bg-mentari-green-dark text-white px-6 py-3 rounded-full font-bold text-xs sm:text-sm shadow-md transition">
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
                                class="inline-block text-xs sm:text-sm font-bold text-mentari-green-dark uppercase tracking-wider bg-green-200/80 px-3 py-1 rounded-full">
                                🍍 100% Buah Asli Vacuum Frying
                            </span>
                            <h2
                                class="text-3xl sm:text-5xl md:text-6xl font-black text-green-950 tracking-tight leading-none">
                                Keripik Mix <br>
                                <span class="text-mentari-green-dark">Buah Spesial</span>
                            </h2>
                            <p class="text-stone-600 text-xs sm:text-sm pt-1 max-w-md leading-relaxed">
                                Kombinasi aneka buah manis segar khas daerah dalam satu kemasan! Tanpa pemanis buatan,
                                diproses hygienis untuk menjaga nutrisi dan kerenyahan alami buah.
                            </p>
                            <div class="pt-2">
                                <a href="/produk/keripik-mix-buah"
                                    class="inline-flex items-center gap-2 bg-mentari-green hover:bg-mentari-green-dark text-white px-6 py-3 rounded-full font-bold text-xs sm:text-sm shadow-md transition">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    <span>Lihat Detail Mix Buah</span>
                                </a>
                            </div>
                        </div>
                        <div class="md:col-span-5 flex items-center justify-center">
                            <div
                                class="relative bg-white/95 p-4 sm:p-6 rounded-3xl shadow-xl border border-green-300 max-w-sm text-center">
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
        <h3 class="text-xl sm:text-2xl font-black text-stone-900 tracking-tight">Temukan Keripik Favoritmu</h3>
        <p class="text-xs sm:text-sm text-stone-500 mt-1">Pilih keripik favorit untuk menemani setiap momenmu.
        </p>

        <!-- Search Input di Bawah Header Katalogg -->
        <div class="mt-6 max-w-md mx-auto relative">
            <input type="text" x-model="searchQuery" placeholder="Cari produk keripik..."
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
                    Gurih
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
                                    <span class="text-mentari-green-dark font-bold">Ready Stock</span>
                                </div>
                                <h3 class="font-bold text-stone-900 text-base" x-text="product.name"></h3>
                                <p class="text-stone-600 text-xs mt-1 leading-relaxed" x-text="product.desc"></p>
                            </div>

                            <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                                <div class="font-extrabold text-mentari-red text-base"
                                    x-text="formatRupiah(product.price)"></div>

                                <div class="flex items-center gap-1.5">
                                    <button type="button" @click="addToCart(product)"
                                        class="flex items-center justify-center gap-1.5 h-9 px-3 rounded-lg border border-mentari-green text-mentari-green-dark text-xs font-bold hover:bg-green-50 transition-colors"
                                        title="Tambah ke Keranjang">
                                        <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                                        <span>Keranjang</span>
                                    </button>
                                    <a :href="'/keripik/' + product.id"
                                        class="flex items-center justify-center gap-1.5 h-9 px-3 rounded-lg bg-mentari-green hover:bg-mentari-green-dark text-white text-xs font-bold transition-colors"
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
                        class="w-10 h-10 rounded-xl bg-green-100 text-mentari-green-dark flex items-center justify-center mb-4 font-bold">
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
                            <i data-lucide="check" class="w-4 h-4 text-mentari-green"></i>
                            <span>Bahan 100% buah asli tanpa pemanis buatan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-mentari-green"></i>
                            <span>Pengiriman aman ke seluruh wilayah Kota Malang</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-mentari-green"></i>
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
                    <button @click="buatPesanan()"
                        class="w-full bg-mentari-green hover:bg-mentari-green-dark text-white py-3 rounded-xl font-bold text-xs transition">
                        Buat Pesanan
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- CLEAN FOOTER -->
    <!-- ENHANCED FOOTER -->
    <footer class="bg-stone-950 text-stone-300 pt-16 pb-8 border-t border-stone-800/80 font-sans">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">

        <!-- Main Footer Links Grid -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 py-12 border-b border-stone-800/80">

            <!-- Brand Info & Corporate License (5 Columns) -->
            <div class="md:col-span-5 space-y-5">
                <a href="#" class="inline-flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Keripik Mentari Logo"
                        class="h-12 w-auto object-contain bg-white rounded-xl p-1.5 shadow-sm transition group-hover:scale-105">
                    <div>
                        <span class="text-xl font-black tracking-tight text-white block leading-none">MENTARI</span>
                        <span class="text-[11px] text-stone-400 font-medium tracking-widest uppercase mt-1 block">Oleh-Oleh Khas Malang</span>
                    </div>
                </a>

                <p class="text-xs leading-relaxed text-stone-400 max-w-sm">
                    Produsen kuliner dan buah khas Malang terpercaya. Memadukan bahan alam pilihan dengan teknologi penggorengan hampa udara (*vacuum frying*) untuk cita rasa alami nan renyah.
                </p>

                <!-- Corporate Info Card (UD. Mentari Jaya Abadi) -->
                <div class="p-3.5 rounded-xl bg-stone-900/80 border border-stone-800 flex items-center justify-between gap-4 max-w-sm">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-stone-800 flex items-center justify-center shrink-0">
                            <i data-lucide="building-2" class="w-4 h-4 text-stone-300"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-stone-200 block">UD. MENTARI JAYA ABADI</span>
                        </div>
                    </div>
                    <span class="text-[10px] px-2 py-0.5 rounded-full bg-stone-800 text-stone-400 font-medium shrink-0">Malang, Jatim</span>
                </div>

                <!-- Social Media Links (SVG High-Quality) -->
                <div class="flex items-center gap-2 pt-1">
                    <!-- WhatsApp -->
                    <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                        class="w-9 h-9 rounded-lg bg-stone-900 hover:bg-mentari-red hover:text-white border border-stone-800 flex items-center justify-center text-stone-400 transition"
                        title="WhatsApp">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                    </a>

                    <!-- Instagram -->
                    <a href="https://instagram.com/keripikmentari" target="_blank" rel="noopener noreferrer"
                        class="w-9 h-9 rounded-lg bg-stone-900 hover:bg-mentari-red hover:text-white border border-stone-800 flex items-center justify-center text-stone-400 transition"
                        title="Instagram">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>

                    <!-- TikTok -->
                    <a href="https://tiktok.com/@keripikmentari" target="_blank" rel="noopener noreferrer"
                        class="w-9 h-9 rounded-lg bg-stone-900 hover:bg-mentari-red hover:text-white border border-stone-800 flex items-center justify-center text-stone-400 transition"
                        title="TikTok">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                        </svg>
                    </a>

                    <!-- X (Twitter) -->
                    <a href="https://x.com/keripikmentari" target="_blank" rel="noopener noreferrer"
                        class="w-9 h-9 rounded-lg bg-stone-900 hover:bg-mentari-red hover:text-white border border-stone-800 flex items-center justify-center text-stone-400 transition"
                        title="X (Twitter)">
                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Navigation Links (2 Columns) -->
            <div class="md:col-span-2 space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-stone-100">Jelajah</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="#beranda" class="text-stone-400 hover:text-white transition">Beranda</a></li>
                    <li><a href="#keunggulan" class="text-stone-400 hover:text-white transition">Keunggulan</a></li>
                    <li><a href="#produk" class="text-stone-400 hover:text-white transition">Katalog Produk</a></li>
                    <li><a href="#tentang" class="text-stone-400 hover:text-white transition">Tentang Kami</a></li>
                    <li><a href="#kontak" class="text-stone-400 hover:text-white transition">Lokasi Outlet</a></li>
                </ul>
            </div>

            <!-- Product Categories (2 Columns) -->
            <div class="md:col-span-2 space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-stone-100">Kategori</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="#produk" class="text-stone-400 hover:text-white transition">Keripik Buah Asli</a></li>
                    <li><a href="#produk" class="text-stone-400 hover:text-white transition">Keripik Tempe Khas</a></li>
                    <li><a href="#produk" class="text-stone-400 hover:text-white transition">Olahan Bakso Malang</a></li>
                    <li><a href="#produk" class="text-stone-400 hover:text-white transition">Hampers & Paket Hemat</a></li>
                </ul>
            </div>

            <!-- Contact & Operating Hours (3 Columns) -->
            <div class="md:col-span-3 space-y-4">
                <h4 class="text-xs font-bold uppercase tracking-wider text-stone-100">Pusat Informasi</h4>
                <ul class="space-y-3 text-xs">
                    <li class="flex items-start gap-2.5 text-stone-400">
                        <i data-lucide="map-pin" class="w-4 h-4 shrink-0 text-mentari-red mt-0.5"></i>
                        <span class="leading-relaxed">Jl. Raya Malang - Batu, Jawa Timur, Indonesia</span>
                    </li>
                    <li class="flex items-center gap-2.5">
                        <i data-lucide="phone" class="w-4 h-4 shrink-0 text-mentari-red"></i>
                        <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer"
                            class="text-stone-400 hover:text-white transition font-medium">+62 812-3456-7890</a>
                    </li>
                    <li class="flex items-center gap-2.5 text-stone-400">
                        <i data-lucide="mail" class="w-4 h-4 shrink-0 text-mentari-red"></i>
                        <span>mentarioleholeh@gmail.com</span>
                    </li>
                    <li class="flex items-center gap-2.5 text-stone-400">
                        <i data-lucide="clock" class="w-4 h-4 shrink-0 text-mentari-red"></i>
                        <span>Senin - Minggu: 08.00 - 20.00 WIB</span>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Corporate Bottom Bar -->
        <div class="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-stone-500">
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-x-6 gap-y-2">
                <p>&copy; {{ date('Y') }} <strong class="text-stone-300 font-semibold">UD. Mentari Jaya Abadi</strong>. All rights reserved.</p>
            </div>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-1.5 text-stone-400">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[11px] font-medium">Layanan Online Aktif</span>
                </div>

                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.keripik.index') }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-stone-900 border border-stone-800 px-3 py-1.5 font-semibold text-stone-300 transition hover:bg-stone-800 hover:text-white">
                            <i data-lucide="shield-check" class="h-3.5 w-3.5 text-mentari-red"></i>
                            <span>Admin Portal</span>
                        </a>
                    @endif
                @endauth
            </div>
        </div>

    </div>
</footer>
    <!-- FLOATING CHAT WIDGET -->
    <div x-data="chatWidget" class="fixed bottom-6 right-6 z-50">
        <!-- Chat Button -->
        <button x-show="!chatOpen" @click="toggleChat()"
            class="w-14 h-14 rounded-full bg-mentari-green hover:bg-mentari-green-dark text-white shadow-lg flex items-center justify-center transition-all hover:scale-110"
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
            <div class="bg-mentari-green text-white px-5 py-4 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                        <i data-lucide="headphones" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm">Chat Keripik Mentari</h4>
                        <p class="text-[11px] text-green-100">Kami siap membantu Anda</p>
                    </div>
                </div>
                <button @click="toggleChat()" class="p-1 hover:bg-white/20 rounded-lg transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Name Input (shown first time) -->
            <template x-if="!nameSubmitted">
                <div class="p-4 flex flex-col gap-2">
                    <input type="text" x-model="customerName" placeholder="Nama Anda"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-mentari-red" />
                    <button @click="submitName()" :disabled="!customerName.trim()"
                        class="bg-mentari-green hover:bg-mentari-green-dark text-white px-4 py-2 rounded">Mulai
                        Chat</button>
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
                                    'bg-mentari-green text-white rounded-2xl rounded-br-sm'"
                                    class="px-4 py-2.5 max-w-[80%] shadow-xs">
                                    <p class="text-xs leading-relaxed" x-text="msg.message"></p>
                                    <p class="text-[10px] mt-1"
                                        :class="msg.is_admin ? 'text-stone-400' : 'text-white-200'"
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
                                class="w-10 h-10 rounded-xl bg-mentari-red hover:bg-mentari-red-dark disabled:bg-stone-300 text-white flex items-center justify-center transition shrink-0">
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
