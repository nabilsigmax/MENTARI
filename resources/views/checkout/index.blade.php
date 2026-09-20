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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body 
    class="bg-mentari-gray font-sans text-stone-800"
    x-data="{ 
        showModal: false,
        harga: {{ $keripik->harga }}, 
        jumlah: {{ $qty }},
        get total() {
            return this.harga * this.jumlah;
        },
        formatRupiah(val) {
            return 'Rp' + new Intl.NumberFormat('id-ID').format(val);
        },
        submitForm(e) {
            // Tampilkan pop-up modal
            this.showModal = true;
            
            // Redirect otomatis ke landing page setelah 2.5 detik
            setTimeout(() => {
                e.target.submit();
            }, 2500);
        }
    }"
>
    <header class="bg-white border-b border-stone-200 h-20 flex items-center shadow-xs">
        <div class="max-w-4xl mx-auto px-4 w-full flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                <span class="text-xl font-black text-mentari-red hidden sm:block">Checkout</span>
            </a>
            <a href="{{ route('keripik.show', $keripik->id) }}" class="text-sm font-medium text-stone-500 hover:text-mentari-red">Batal</a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-8">
        <form 
            action="{{ route('checkout.store') }}" 
            method="POST" 
            enctype="multipart/form-data" 
            @submit.prevent="submitForm($event)"
            class="flex flex-col md:flex-row gap-6"
        >
            @csrf
            <input type="hidden" name="keripik_id" value="{{ $keripik->id }}">
            <input type="hidden" name="jumlah" :value="jumlah">
            
            <div class="w-full md:w-2/3 space-y-6">
                <!-- Data Pembeli -->
                <section class="bg-white p-6 rounded-sm shadow-sm">
                    <h2 class="text-lg font-bold text-stone-800 mb-4 flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-5 h-5 text-mentari-red"></i> Data Pengiriman
                    </h2>
                    
                    @if(session('error'))
                        <div class="mb-4 bg-red-50 text-red-600 p-3 rounded text-sm font-medium">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama_pembeli" required class="w-full rounded border-stone-300 border px-3 py-2 outline-none focus:border-mentari-red focus:ring-1 focus:ring-mentari-red">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1">No. WhatsApp</label>
                            <input type="text" name="no_hp" required class="w-full rounded border-stone-300 border px-3 py-2 outline-none focus:border-mentari-red focus:ring-1 focus:ring-mentari-red">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" required rows="3" class="w-full rounded border-stone-300 border px-3 py-2 outline-none focus:border-mentari-red focus:ring-1 focus:ring-mentari-red"></textarea>
                        </div>
                    </div>
                </section>

                <!-- Pembayaran -->
                <section class="bg-white p-6 rounded-sm shadow-sm">
                    <h2 class="text-lg font-bold text-stone-800 mb-4 flex items-center gap-2">
                        <i data-lucide="credit-card" class="w-5 h-5 text-mentari-red"></i> Pembayaran
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1">Metode Pembayaran</label>
                            <select name="metode_pembayaran" required class="w-full rounded border-stone-300 border px-3 py-2 outline-none focus:border-mentari-red focus:ring-1 focus:ring-mentari-red bg-white">
                                <option value="BCA">Transfer BCA - 1234567890 (a.n Keripik Mentari)</option>
                                <option value="Mandiri">Transfer Mandiri - 0987654321 (a.n Keripik Mentari)</option>
                                <option value="COD">COD (Bayar di Tempat)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-stone-700 mb-1">Bukti Pembayaran (Opsional)</label>
                            <input type="file" name="bukti_pembayaran" accept="image/*" class="w-full text-sm text-stone-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-mentari-red-light file:text-mentari-red hover:file:bg-red-100">
                            <p class="text-xs text-stone-500 mt-1">Bisa diupload nanti, atau jika memilih COD abaikan ini.</p>
                        </div>
                    </div>
                </section>
            </div>

            <div class="w-full md:w-1/3">
                <section class="bg-white p-6 rounded-sm shadow-sm sticky top-24">
                    <h2 class="text-lg font-bold text-stone-800 mb-4">Ringkasan Pesanan</h2>
                    
                    <div class="flex gap-3 pb-4 border-b border-stone-100">
                        <img src="{{ $keripik->gambar_url ?: 'https://via.placeholder.com/80' }}" class="w-16 h-16 object-cover border border-stone-200 rounded">
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-stone-800 line-clamp-2">{{ $keripik->nama }}</h3>
                            <p class="text-xs text-stone-500 mt-0.5">{{ $keripik->berat }}</p>
                            <p class="text-sm font-semibold text-mentari-red mt-1" x-text="formatRupiah(harga)"></p>
                        </div>
                    </div>

                    <!-- Tombol Pengatur Jumlah Pesanan -->
                    <div class="py-4 border-b border-stone-100 flex items-center justify-between">
                        <span class="text-sm font-medium text-stone-700">Jumlah</span>
                        <div class="flex items-center border border-stone-300 rounded overflow-hidden">
                            <button 
                                type="button" 
                                @click="if (jumlah > 1) jumlah--" 
                                :class="jumlah <= 1 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-stone-100'"
                                class="px-2.5 py-1 text-stone-600 transition"
                            >-</button>
                            
                            <input 
                                type="number" 
                                x-model.number="jumlah" 
                                @input="if (jumlah < 1 || !jumlah) jumlah = 1"
                                class="w-10 text-center text-sm font-bold text-stone-800 outline-none border-x border-stone-200 py-1 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            >
                            
                            <button 
                                type="button" 
                                @click="jumlah++" 
                                class="px-2.5 py-1 text-stone-600 hover:bg-stone-100 transition"
                            >+</button>
                        </div>
                    </div>

                    <div class="py-4 space-y-2 text-sm text-stone-600 border-b border-stone-100">
                        <div class="flex justify-between">
                            <span>Subtotal Produk</span>
                            <span class="font-medium text-stone-800" x-text="formatRupiah(total)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Ongkos Kirim</span>
                            <span class="text-green-600 font-medium">Gratis</span>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-between items-center mb-6">
                        <span class="font-bold text-stone-800">Total</span>
                        <span class="text-xl font-bold text-mentari-red" x-text="formatRupiah(total)"></span>
                    </div>

                    <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-700 transition-colors cursor-pointer">
    Buat Pesanan
</button>
                </section>
            </div>
        </form>
    </main>

    <!-- Pop-up Modal Pembayaran Sukses -->
    <div 
        x-show="showModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
        style="display: none;"
    >
        <div class="bg-white rounded-2xl p-6 sm:p-8 max-w-sm w-full text-center shadow-xl">
            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check-circle-2" class="w-10 h-10"></i>
            </div>
            <h3 class="text-xl font-extrabold text-stone-800 mb-2">Pembayaran Sukses!</h3>
            <p class="text-sm text-stone-600 mb-6">Terima kasih telah berbelanja di Keripik Mentari. Anda akan otomatis diarahkan kembali ke halaman utama...</p>
            <div class="w-full bg-stone-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-mentari-red h-full animate-pulse w-full"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    </script>
</body>
</html>