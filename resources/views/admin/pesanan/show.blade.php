<x-layouts.admin title="Detail Pesanan #{{ str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) }}">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.pesanan.index') }}"
            class="p-2 rounded-full hover:bg-stone-100 text-stone-500 transition">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-stone-800">Detail Pesanan
                #{{ str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) }}</h1>
            <p class="text-stone-500 text-sm mt-1">{{ $pesanan->created_at->format('d M Y H:i') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <!-- Informasi Produk -->
            <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-xs">
                <h2 class="text-lg font-bold text-stone-800 mb-4 border-b border-stone-100 pb-3">Produk yang Dipesan
                </h2>
                <div class="flex items-center gap-4">
                    <img src="{{ $pesanan->keripik->gambar_url ?: 'https://via.placeholder.com/80' }}"
                        class="w-20 h-20 object-cover rounded border border-stone-200">
                    <div>
                        <h3 class="font-bold text-stone-900">{{ $pesanan->keripik->nama }}</h3>
                        <p class="text-sm text-stone-500 mt-1">
                            Rp{{ number_format($pesanan->keripik->harga, 0, ',', '.') }} x {{ $pesanan->jumlah }}</p>
                        <p class="text-lg font-bold text-mentari-red mt-2">Total:
                            Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Pembeli -->
            <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-xs">
                <h2 class="text-lg font-bold text-stone-800 mb-4 border-b border-stone-100 pb-3">Data Pelanggan &
                    Pengiriman</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-stone-500 mb-1">Nama</span>
                        <span class="font-semibold text-stone-900">{{ $pesanan->nama_pembeli }}</span>
                    </div>
                    <div>
                        <span class="block text-stone-500 mb-1">No. WhatsApp</span>
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', $pesanan->no_hp) }}" target="_blank"
                            class="font-semibold text-blue-600 hover:underline flex items-center gap-1">
                            {{ $pesanan->no_hp }}
                            <i data-lucide="external-link" class="w-3 h-3"></i>
                        </a>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="block text-stone-500 mb-1">Alamat Lengkap</span>
                        <span class="font-semibold text-stone-900">{{ $pesanan->alamat }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Update Status -->
            <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-xs">
                <h2 class="text-lg font-bold text-stone-800 mb-4 border-b border-stone-100 pb-3">Status Pesanan</h2>
                <form action="{{ route('admin.pesanan.update', $pesanan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-stone-700 mb-2">Ubah Status</label>
                        <select name="status"
                            class="w-full border border-stone-200 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-mentari-green">
                            <option value="pending" {{ $pesanan->status == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="dibayar" {{ $pesanan->status == 'dibayar' ? 'selected' : '' }}>Dibayar
                            </option>
                            <option value="diproses" {{ $pesanan->status == 'diproses' ? 'selected' : '' }}>Diproses
                            </option>
                            <option value="dikirim" {{ $pesanan->status == 'dikirim' ? 'selected' : '' }}>Dikirim
                            </option>
                            <option value="selesai" {{ $pesanan->status == 'selesai' ? 'selected' : '' }}>Selesai
                            </option>
                            <option value="batal" {{ $pesanan->status == 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-stone-700 mb-2">Nomor Resi</label>
                        <input type="text" name="nomor_resi" value="{{ old('nomor_resi', $pesanan->nomor_resi) }}"
                            placeholder="Contoh: JNE1234567890"
                            class="w-full border border-stone-200 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-mentari-green">
                        @error('nomor_resi')
                            <span class="text-xs text-green-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-stone-700 mb-2">Tanggal Dikirim</label>
                        <input type="date" name="tanggal_dikirim"
                            value="{{ $pesanan->tanggal_dikirim?->format('Y-m-d') }}"
                            class="w-full border border-stone-200 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-mentari-green">
                        @error('tanggal_dikirim')
                            <span class="text-xs text-green-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-stone-700 mb-2">Tanggal Diterima</label>
                        <input type="date" name="tanggal_diterima"
                            value="{{ $pesanan->tanggal_diterima?->format('Y-m-d') }}"
                            class="w-full border border-stone-200 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-mentari-green">
                        @error('tanggal_diterima')
                            <span class="text-xs text-green-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-stone-700 mb-2">Estimasi Tiba</label>
                        <input type="date" name="estimasi_tiba"
                            value="{{ $pesanan->estimasi_tiba?->format('Y-m-d') }}"
                            class="w-full border border-stone-200 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-mentari-green">
                        @error('estimasi_tiba')
                            <span class="text-xs text-green-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-mentari-green text-white font-bold py-2 rounded-lg hover:bg-mentari-green-dark transition">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- Pembayaran -->
            <div class="bg-white border border-stone-200 rounded-xl p-6 shadow-xs">
                <h2 class="text-lg font-bold text-stone-800 mb-4 border-b border-stone-100 pb-3">Pembayaran</h2>
                <div class="text-sm mb-4">
                    <span class="block text-stone-500 mb-1">Metode</span>
                    <span class="font-semibold text-stone-900">{{ $pesanan->metode_pembayaran }}</span>
                </div>
               
            </div>
        </div>
    </div>
</x-layouts.admin>
