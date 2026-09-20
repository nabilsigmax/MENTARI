@props(['keripik' => null])
@php($editing = $keripik !== null)

<div 
    class="space-y-8" 
    x-data="{
        previewImage: '{{ old('gambar_url', $keripik?->gambar_url) }}',
        uploadMode: 'file',
        hargaValue: '{{ old('harga', $keripik?->harga) }}',
        
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewImage = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        formatRupiahPreview(val) {
            if (!val) return 'Rp 0';
            return 'Rp ' + Number(val).toLocaleString('id-ID');
        }
    }"
>

    <!-- Error Validation Alert -->
    @if ($errors->any())
        <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-800 shadow-xs">
            <div class="flex items-center gap-2 font-bold mb-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-red-600"></i>
                <span>Terdapat kesalahan pengisian data:</span>
            </div>
            <ul class="list-disc pl-5 space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- LEFT COLUMN: Gambar Produk & Status -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Gambar Upload & URL Card -->
            <div class="bg-stone-50/80 p-5 rounded-3xl border border-stone-200/90 space-y-4">
                
                <div>
                    <label class="block text-sm font-extrabold text-stone-900">
                        Foto / Gambar Produk <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-stone-500 mt-0.5">Bisa upload file foto langsung atau masukkan tautan URL.</p>
                </div>

                <!-- Tab Pilihan: Upload File vs URL -->
                <div class="flex items-center p-1 bg-stone-200/80 rounded-xl text-xs font-bold">
                    <button 
                        type="button" 
                        @click="uploadMode = 'file'" 
                        :class="uploadMode === 'file' ? 'bg-white text-stone-900 shadow-xs' : 'text-stone-600'" 
                        class="flex-1 py-2 rounded-lg transition text-center"
                    >
                        📁 Upload File
                    </button>
                    <button 
                        type="button" 
                        @click="uploadMode = 'url'" 
                        :class="uploadMode === 'url' ? 'bg-white text-stone-900 shadow-xs' : 'text-stone-600'" 
                        class="flex-1 py-2 rounded-lg transition text-center"
                    >
                        🔗 Input URL
                    </button>
                </div>

                <!-- Mode 1: File Input -->
                <div x-show="uploadMode === 'file'" class="space-y-2">
                    <div class="relative border-2 border-dashed border-stone-300 hover:border-mentari-red rounded-2xl p-4 text-center bg-white transition cursor-pointer group">
                        <input 
                            type="file" 
                            name="gambar_file" 
                            id="gambar_file" 
                            accept="image/*" 
                            @change="handleFileSelect($event)" 
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                        >
                        <div class="space-y-1.5 py-3">
                            <i data-lucide="upload-cloud" class="w-8 h-8 text-stone-400 group-hover:text-mentari-red mx-auto transition"></i>
                            <div class="text-xs font-bold text-stone-800">
                                <span class="text-mentari-red">Klik untuk upload file</span> atau drag & drop
                            </div>
                            <p class="text-[11px] text-stone-400">PNG, JPG, WEBP (Maks. 5 MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Mode 2: URL Input -->
                <div x-show="uploadMode === 'url'" class="space-y-1.5" x-cloak>
                    <input 
                        type="url" 
                        name="gambar_url" 
                        x-model="previewImage"
                        :disabled="uploadMode !== 'url'"
                        placeholder="https://contoh.com/foto-keripik.jpg" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 bg-white text-xs font-medium focus:outline-none focus:ring-2 focus:ring-mentari-red/30 focus:border-mentari-red"
                    >
                    <p class="text-[11px] text-stone-400">Masukkan link URL gambar dari internet.</p>
                </div>

                <!-- Live Preview Card -->
                <div class="pt-2">
                    <span class="text-xs font-bold text-stone-600 uppercase tracking-wider block mb-2">Pratinjau Foto:</span>
                    
                    <div class="relative w-full aspect-[4/3] rounded-2xl bg-white border border-stone-200 overflow-hidden flex items-center justify-center">
                        <template x-if="previewImage">
                            <img :src="previewImage" alt="Preview Gambar" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!previewImage">
                            <div class="text-center text-stone-400 p-4">
                                <i data-lucide="image" class="w-10 h-10 mx-auto mb-1 opacity-40"></i>
                                <span class="text-xs">Belum ada gambar dipilih</span>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <!-- Status Publikasi Toggle Card -->
            <div class="bg-stone-50/80 p-5 rounded-3xl border border-stone-200/90 flex items-center justify-between">
                <div>
                    <label for="is_active" class="font-extrabold text-stone-900 text-sm cursor-pointer">
                        Tampilkan di Landing Page
                    </label>
                    <p class="text-xs text-stone-500 mt-0.5">Produk akan langsung muncul di katalog pembeli.</p>
                </div>
                <div>
                    <input type="hidden" name="is_active" value="0">
                    <input 
                        type="checkbox" 
                        name="is_active" 
                        id="is_active" 
                        value="1" 
                        @checked(old('is_active', $keripik?->is_active ?? true)) 
                        class="w-5 h-5 rounded-lg border-stone-300 text-mentari-red focus:ring-mentari-red cursor-pointer"
                    >
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Detail Produk -->
        <div class="lg:col-span-7 space-y-5">
            
            <!-- Nama Keripik -->
            <div>
                <label for="nama" class="block text-sm font-extrabold text-stone-900 mb-1">
                    Nama Keripik <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    name="nama" 
                    id="nama" 
                    value="{{ old('nama', $keripik?->nama) }}" 
                    placeholder="Contoh: Keripik Apel Manalagi Super" 
                    required 
                    class="w-full px-4 py-3 rounded-2xl border border-stone-300 bg-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-mentari-red/30 focus:border-mentari-red"
                >
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                
                <!-- Kategori -->
                <div>
                    <label for="kategori" class="block text-sm font-extrabold text-stone-900 mb-1">
                        Kategori Produk <span class="text-red-500">*</span>
                    </label>
                    @php($selectedCategory = old('kategori', $keripik?->kategori))
                    <select
                        name="kategori"
                        id="kategori"
                        required
                        class="w-full px-4 py-3 rounded-2xl border border-stone-300 bg-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-mentari-red/30 focus:border-mentari-red"
                    >
                        <option value="" disabled @selected(blank($selectedCategory))>Pilih kategori</option>
                        @if ($selectedCategory && ! App\KategoriKeripik::tryFrom($selectedCategory))
                            <option value="{{ $selectedCategory }}" selected>Kategori lama tidak valid � pilih kategori baru</option>
                        @endif
                        @foreach ($categories as $category)
                            <option value="{{ $category->value }}" @selected($selectedCategory === $category->value)>{{ $category->label() }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-stone-500">Kategori menentukan tab produk di landing page.</p></div>

                <!-- Berat Bersih -->
                <div>
                    <label for="berat" class="block text-sm font-extrabold text-stone-900 mb-1">
                        Berat / Kemasan <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="berat" 
                        id="berat" 
                        value="{{ old('berat', $keripik?->berat) }}" 
                        placeholder="Contoh: 100 gram / 250 gram" 
                        required 
                        class="w-full px-4 py-3 rounded-2xl border border-stone-300 bg-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-mentari-red/30 focus:border-mentari-red"
                    >
                </div>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                
                <!-- Harga -->
                <div>
                    <label for="harga" class="block text-sm font-extrabold text-stone-900 mb-1">
                        Harga Satuan (Rp) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-bold text-stone-400">Rp</span>
                        <input 
                            type="number" 
                            name="harga" 
                            id="harga" 
                            min="0" 
                            x-model="hargaValue" 
                            required 
                            placeholder="25000" 
                            class="w-full pl-11 pr-4 py-3 rounded-2xl border border-stone-300 bg-white text-sm font-bold focus:outline-none focus:ring-2 focus:ring-mentari-red/30 focus:border-mentari-red"
                        >
                    </div>
                    <span class="text-[11px] font-bold text-mentari-red mt-1 block" x-text="formatRupiahPreview(hargaValue)"></span>
                </div>

                <!-- Stok -->
                <div>
                    <label for="stok" class="block text-sm font-extrabold text-stone-900 mb-1">
                        Jumlah Stok Tersedia (Pcs) <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="number" 
                        name="stok" 
                        id="stok" 
                        min="0" 
                        value="{{ old('stok', $keripik?->stok ?? 50) }}" 
                        required 
                        placeholder="50" 
                        class="w-full px-4 py-3 rounded-2xl border border-stone-300 bg-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-mentari-red/30 focus:border-mentari-red"
                    >
                </div>

            </div>

            <!-- Deskripsi Produk -->
            <div>
                <label for="deskripsi" class="block text-sm font-extrabold text-stone-900 mb-1">
                    Deskripsi & Keunggulan Produk
                </label>
                <textarea 
                    name="deskripsi" 
                    id="deskripsi" 
                    rows="4" 
                    placeholder="Jelaskan keunggulan keripik, proses penggorengan vacuum, rasa manis alami, dll..." 
                    class="w-full px-4 py-3 rounded-2xl border border-stone-300 bg-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-mentari-red/30 focus:border-mentari-red leading-relaxed"
                >{{ old('deskripsi', $keripik?->deskripsi) }}</textarea>
            </div>

            <!-- Form Actions -->
            <div class="pt-4 border-t border-stone-200 flex items-center gap-3">
                <button 
                    type="submit" 
                    class="inline-flex items-center gap-2 bg-mentari-red hover:bg-mentari-red-dark text-white px-7 py-3.5 rounded-2xl font-bold text-sm shadow-md transition hover:-translate-y-0.5"
                >
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>{{ $editing ? 'Simpan Perubahan' : 'Simpan Produk Keripik' }}</span>
                </button>

                <a 
                    href="{{ route('admin.keripik.index') }}" 
                    class="px-6 py-3.5 rounded-2xl bg-stone-200 hover:bg-stone-300 text-stone-700 font-bold text-sm transition"
                >
                    Batal
                </a>
            </div>

        </div>

    </div>

</div>