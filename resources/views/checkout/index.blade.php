<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - Keripik Mentari</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mentari: {
                            red: '#EE4D2D',
                            'red-light': '#FFEEEE',
                            'red-dark': '#D73211',
                            green: '#16A34A',
                            'green-dark': '#15803D',
                            gray: '#F8F9FA'
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
        [x-cloak] { display: none !important; }
        /* Sembunyikan panah input number */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
</head>
<body 
    class="bg-mentari-gray font-sans text-stone-800 antialiased selection:bg-mentari-green/20 selection:text-mentari-green"
    x-data="{
        showModal: false,
        metode: 'BCA',
        isMulti: {{ count($items) > 1 ? 'true' : 'false' }},
        multiTotal: {{ $total }},
        harga: {{ $keripik->harga }},
        jumlah: {{ $qty }},
        get total() {
            return this.isMulti ? this.multiTotal : this.harga * this.jumlah;
        },
        formatRupiah(val) {
            return 'Rp' + new Intl.NumberFormat('id-ID').format(val);
        },
        submitForm(e) {
            this.showModal = true;
            setTimeout(() => {
                e.target.submit();
            }, 2500);
        }
    }"
>
    <!-- Header Minimalis & Aman -->
    <header class="bg-white border-b border-stone-200 h-20 flex items-center sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 w-full flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 sm:h-12 w-auto transition-transform group-hover:scale-105">
                <div class="hidden sm:block border-l-2 border-stone-200 pl-3">
                    <span class="text-lg font-extrabold tracking-tight text-stone-900">Checkout</span>
                </div>
            </a>
            <div class="flex items-center gap-4">
                <div class="hidden sm:flex items-center gap-1.5 text-mentari-green text-sm font-bold bg-green-50 px-3 py-1.5 rounded-full">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    Pembayaran Aman
                </div>
                <a href="{{ route('keripik.show', $keripik->id) }}" class="text-sm font-bold text-stone-500 hover:text-mentari-red transition-colors flex items-center gap-1">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Batal
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
        <form 
            action="{{ route('checkout.store') }}" 
            method="POST" 
            @submit.prevent="submitForm($event)"
            class="flex flex-col lg:flex-row gap-8"
        >
            @csrf
            @if (count($items) > 1)
                <input type="hidden" name="items" value="{{ json_encode(collect($items)->map(fn ($item) => ['keripik_id' => $item['keripik']->id, 'jumlah' => $item['jumlah']])->values()) }}">
            @else
                <input type="hidden" name="keripik_id" value="{{ $keripik->id }}">
                <input type="hidden" name="jumlah" :value="jumlah">
            @endif
            
            <!-- KOLOM KIRI (Form) -->
            <div class="w-full lg:w-2/3 space-y-6 sm:space-y-8">
                
                <!-- Data Pengiriman -->
                <section class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-stone-200/60">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-mentari-green/10 flex items-center justify-center text-mentari-green shrink-0">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-stone-900">Alamat Pengiriman</h2>
                            <p class="text-sm text-stone-500 mt-0.5">Pastikan alamat dan nomor HP Anda benar.</p>
                        </div>
                    </div>
                    
                    @if(session('error'))
                        <div class="mb-6 flex items-start gap-3 bg-green-50 text-red-700 p-4 rounded-xl text-sm font-medium border border-green-100">
                            <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2">Nama Penerima</label>
                            <input type="text" name="nama_pembeli" value="{{ old('nama_pembeli', auth()->user()->name ?? '') }}" required 
                                class="w-full rounded-xl border-0 bg-stone-50 px-4 py-3.5 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-200 transition focus:bg-white focus:ring-2 focus:ring-inset focus:ring-mentari-green outline-none"
                                placeholder="Masukkan nama lengkap">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2">No. WhatsApp</label>
                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                    <span class="text-stone-500 font-medium">+62</span>
                                </div>
                                <input type="text" name="no_hp" value="{{ old('no_hp', auth()->user()->no_hp ?? '') }}" required 
                                    class="w-full rounded-xl border-0 bg-stone-50 pl-12 pr-4 py-3.5 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-200 transition focus:bg-white focus:ring-2 focus:ring-inset focus:ring-mentari-green outline-none"
                                    placeholder="81234567890">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                            <textarea name="alamat" required rows="3" 
                                class="w-full rounded-xl border-0 bg-stone-50 px-4 py-3.5 text-stone-900 shadow-sm ring-1 ring-inset ring-stone-200 transition focus:bg-white focus:ring-2 focus:ring-inset focus:ring-mentari-green outline-none resize-none"
                                placeholder="Nama jalan, gedung, no. rumah, RT/RW, kelurahan, kecamatan, kota">{{ old('alamat', auth()->user()->alamat ?? '') }}</textarea>
                        </div>
                    </div>
                </section>

                <!-- Pembayaran -->
                <section class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-stone-200/60">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                            <i data-lucide="wallet" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-stone-900">Metode Pembayaran</h2>
                            <p class="text-sm text-stone-500 mt-0.5">Pilih cara pembayaran yang Anda inginkan.</p>
                        </div>
                    </div>

                    <!-- Custom Radio Cards (Diperbaiki dengan Alpine.js) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Option 1: BCA -->
                        <label class="relative flex cursor-pointer rounded-xl border bg-white p-4 shadow-sm transition"
                               :class="metode === 'BCA' ? 'border-mentari-green' : 'border-stone-200 hover:border-blue-200'">
                            <input type="radio" name="metode_pembayaran" value="BCA" x-model="metode" class="sr-only">
                            
                            <!-- Border Luar Aktif -->
                            <div class="pointer-events-none absolute -inset-px rounded-xl border-2 transition"
                                 :class="metode === 'BCA' ? 'border-mentari-green' : 'border-transparent'"></div>
                            
                            <div class="flex items-center gap-4 w-full">
                                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center text-blue-700 shrink-0">
                                    <i data-lucide="building-2" class="w-5 h-5"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-stone-900">Transfer BCA</p>
                                    <p class="text-xs text-stone-500 mt-0.5">1234567890 (Keripik Mentari)</p>
                                </div>
                                <!-- Lingkaran Aktif -->
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition"
                                     :class="metode === 'BCA' ? 'border-mentari-green' : 'border-stone-300'">
                                    <div class="w-2.5 h-2.5 rounded-full bg-mentari-green transition-transform duration-200"
                                         :class="metode === 'BCA' ? 'scale-100' : 'scale-0'"></div>
                                </div>
                            </div>
                        </label>

                        <!-- Option 2: Mandiri -->
                        <label class="relative flex cursor-pointer rounded-xl border bg-white p-4 shadow-sm transition"
                               :class="metode === 'Mandiri' ? 'border-mentari-green' : 'border-stone-200 hover:border-yellow-200'">
                            <input type="radio" name="metode_pembayaran" value="Mandiri" x-model="metode" class="sr-only">
                            
                            <div class="pointer-events-none absolute -inset-px rounded-xl border-2 transition"
                                 :class="metode === 'Mandiri' ? 'border-mentari-green' : 'border-transparent'"></div>
                            
                            <div class="flex items-center gap-4 w-full">
                                <div class="w-10 h-10 bg-yellow-50 rounded-lg flex items-center justify-center text-yellow-600 shrink-0">
                                    <i data-lucide="building-2" class="w-5 h-5"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-stone-900">Transfer Mandiri</p>
                                    <p class="text-xs text-stone-500 mt-0.5">0987654321 (Keripik Mentari)</p>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition"
                                     :class="metode === 'Mandiri' ? 'border-mentari-green' : 'border-stone-300'">
                                    <div class="w-2.5 h-2.5 rounded-full bg-mentari-green transition-transform duration-200"
                                         :class="metode === 'Mandiri' ? 'scale-100' : 'scale-0'"></div>
                                </div>
                            </div>
                        </label>

                        <!-- Option 3: COD -->
                        <label class="relative flex cursor-pointer rounded-xl border bg-white p-4 shadow-sm transition md:col-span-2"
                               :class="metode === 'COD' ? 'border-mentari-green' : 'border-stone-200 hover:border-green-200'">
                            <input type="radio" name="metode_pembayaran" value="COD" x-model="metode" class="sr-only">
                            
                            <div class="pointer-events-none absolute -inset-px rounded-xl border-2 transition"
                                 :class="metode === 'COD' ? 'border-mentari-green' : 'border-transparent'"></div>
                            
                            <div class="flex items-center gap-4 w-full">
                                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 shrink-0">
                                    <i data-lucide="truck" class="w-5 h-5"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-stone-900">Cash on Delivery (COD)</p>
                                    <p class="text-xs text-stone-500 mt-0.5">Bayar tunai ke kurir saat pesanan tiba di tujuan.</p>
                                </div>
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center shrink-0 transition"
                                     :class="metode === 'COD' ? 'border-mentari-green' : 'border-stone-300'">
                                    <div class="w-2.5 h-2.5 rounded-full bg-mentari-green transition-transform duration-200"
                                         :class="metode === 'COD' ? 'scale-100' : 'scale-0'"></div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Alert Info -->
                    <div class="rounded-xl border border-stone-200 bg-stone-50 p-4">
                        <div class="flex items-start gap-3">
                            <i data-lucide="info" class="mt-0.5 h-5 w-5 shrink-0 text-stone-400"></i>
                            <div>
                                <p class="text-sm font-bold text-stone-700">Verifikasi Otomatis</p>
                                <p class="mt-1 text-sm leading-relaxed text-stone-500">
                                    Pesanan akan langsung diproses. Mohon pastikan nominal transfer sesuai dengan total tagihan untuk mempercepat verifikasi.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- KOLOM KANAN (Ringkasan) -->
            <div class="w-full lg:w-1/3">
                <section class="bg-white p-6 sm:p-7 rounded-2xl shadow-sm border border-stone-200/60 sticky top-28">
                    <h2 class="text-xl font-extrabold text-stone-900 mb-6">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-4 mb-6 max-h-[300px] overflow-y-auto pr-2">
                        @foreach ($items as $item)
                            <div class="flex gap-4 group">
                                <div class="w-16 h-16 shrink-0 rounded-xl bg-stone-50 border border-stone-100 overflow-hidden">
                                    <img src="{{ $item['keripik']->gambar_url ?: 'https://via.placeholder.com/80' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                                <div class="flex-1 pt-1">
                                    <h3 class="text-sm font-bold text-stone-900 line-clamp-2 leading-tight">{{ $item['keripik']->nama }}</h3>
                                    <p class="text-xs text-stone-500 mt-1">{{ $item['keripik']->berat }}</p>
                                    <div class="flex justify-between items-center mt-2">
                                        <p class="text-sm font-extrabold text-stone-700">Rp{{ number_format($item['keripik']->harga, 0, ',', '.') }}</p>
                                        <p class="text-xs font-medium bg-stone-100 text-stone-600 px-2 py-0.5 rounded-md">x{{ $item['jumlah'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pemisah -->
                    <div class="border-t border-dashed border-stone-300 my-6"></div>

                    <!-- Tombol Pengatur Jumlah Pesanan (Hanya jika Beli Langsung 1 Item) -->
                    @if (count($items) === 1)
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-sm font-bold text-stone-700">Kuantitas</span>
                            <div class="flex items-center bg-stone-50 border border-stone-200 rounded-lg p-1">
                                <button type="button" @click="if (jumlah > 1) jumlah--"
                                    :class="jumlah <= 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-white hover:shadow-sm'"
                                    class="w-7 h-7 flex items-center justify-center rounded-md text-stone-600 transition">
                                    <i data-lucide="minus" class="w-3 h-3"></i>
                                </button>
                                <input type="number" x-model.number="jumlah" @input="if (jumlah < 1 || !jumlah) jumlah = 1"
                                    class="w-10 text-center text-sm font-extrabold text-stone-800 bg-transparent outline-none py-1">
                                <button type="button" @click="jumlah++"
                                    class="w-7 h-7 flex items-center justify-center rounded-md text-stone-600 hover:bg-white hover:shadow-sm transition">
                                    <i data-lucide="plus" class="w-3 h-3"></i>
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="bg-stone-50 border border-stone-200 rounded-xl p-3 text-xs text-stone-500 flex gap-2 mb-6">
                            <i data-lucide="shopping-cart" class="w-4 h-4 shrink-0"></i>
                            <span>Terdapat {{ count($items) }} produk di keranjang. Kembali ke keranjang untuk mengubah kuantitas.</span>
                        </div>
                    @endif

                    <div class="space-y-3 text-sm text-stone-500 mb-6">
                        <div class="flex justify-between items-center">
                            <span>Subtotal</span>
                            <span class="font-bold text-stone-800" x-text="formatRupiah(total)"></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span>Biaya Pengiriman</span>
                            <span class="font-bold text-mentari-green px-2 py-0.5 bg-green-50 rounded-md">Gratis</span>
                        </div>
                    </div>

                    <!-- Pemisah Total -->
                    <div class="border-t border-stone-200 my-4"></div>

                    <div class="flex justify-between items-end mb-8">
                        <div>
                            <p class="text-sm font-medium text-stone-500 mb-1">Total Belanja</p>
                            <p class="text-2xl font-black text-mentari-red" x-text="formatRupiah(total)"></p>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-mentari-green text-white text-base font-bold py-4 rounded-xl hover:bg-mentari-green-dark transition-all hover:-translate-y-0.5 hover:shadow-lg hover:shadow-mentari-green/30 flex items-center justify-center gap-2">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                        Bayar Sekarang
                    </button>
                </section>
            </div>
        </form>
    </main>

    <!-- Pop-up Modal Pembayaran Sukses (Dengan animasi Bar) -->
    <div 
        x-show="showModal" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 backdrop-blur-none"
        x-transition:enter-end="opacity-100 backdrop-blur-sm"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 backdrop-blur-sm"
        x-transition:leave-end="opacity-0 backdrop-blur-none"
        class="fixed inset-0 z-50 flex items-center justify-center bg-stone-900/60 p-4"
    >
        <div 
            x-show="showModal"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            class="bg-white rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl relative overflow-hidden"
        >
            <div class="w-20 h-20 bg-green-100 text-mentari-green rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
                <i data-lucide="check" class="w-10 h-10 animate-[bounce_1s_ease-in-out_infinite]"></i>
            </div>
            <h3 class="text-2xl font-black text-stone-900 mb-2">Pesanan Dibuat!</h3>
            <p class="text-sm text-stone-500 mb-8 leading-relaxed">Terima kasih, mohon tunggu sebentar. Kami sedang mengalihkan Anda ke halaman pesanan...</p>
            
            <!-- Animated Loading Bar -->
            <div class="w-full bg-stone-100 h-2 rounded-full overflow-hidden">
                <div class="bg-mentari-green h-full transition-all duration-[2500ms] ease-linear origin-left" 
                     :class="showModal ? 'w-full' : 'w-0'"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    </script>
</body>
</html>