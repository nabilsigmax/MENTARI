<x-layouts.admin title="Dashboard Pengelolaan Keripik">

    <!-- PAGE HEADER -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-stone-900 tracking-tight">Katalog Produk Keripik</h1>
            <p class="text-stone-500 text-sm mt-1">Kelola daftar varian keripik buah, tempe, dan paket oleh-oleh Mentari.</p>
        </div>
        
    </div>

    <!-- SUMMARY METRICS CARDS -->
    <div class="mb-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Total Produk -->
        <div class="bg-white p-5 rounded-3xl border border-stone-200/90 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-stone-500 uppercase tracking-wider">Total Produk</span>
                <p class="text-3xl font-black text-stone-900 mt-1">{{ $totalKeripik }} <span class="text-xs font-semibold text-stone-400">item</span></p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-stone-100 text-stone-700 flex items-center justify-center">
                <i data-lucide="package" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Produk Aktif -->
        <div class="bg-white p-5 rounded-3xl border border-stone-200/90 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-mentari-green-dark uppercase tracking-wider">Tampil di Katalog</span>
                <p class="text-3xl font-black text-mentari-green mt-1">{{ $keripikAktif }} <span class="text-xs font-semibold text-mentari-green/70">aktif</span></p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-green-50 text-mentari-green flex items-center justify-center">
                <i data-lucide="eye" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Total Stok -->
        <div class="bg-white p-5 rounded-3xl border border-stone-200/90 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Total Stok Tersedia</span>
                <p class="text-3xl font-black text-amber-600 mt-1">{{ number_format($totalStok, 0, ',', '.') }} <span class="text-xs font-semibold text-amber-600/70">pcs</span></p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <i data-lucide="boxes" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Kategori -->
        <div class="bg-white p-5 rounded-3xl border border-stone-200/90 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Total Kategori</span>
                <p class="text-3xl font-black text-blue-600 mt-1">{{ count($categories) }} <span class="text-xs font-semibold text-blue-600/70">jenis</span></p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i data-lucide="tags" class="w-6 h-6"></i>
            </div>
        </div>

    </div>

    <!-- MAIN PRODUCT TABLE CARD -->
    <section class="bg-white rounded-3xl border border-stone-200/90 shadow-xs overflow-hidden">
        
        <!-- Filter & Search Bar Header -->
        <div class="p-5 sm:p-6 border-b border-stone-200/80 bg-stone-50/50">
            <form method="GET" action="{{ route('admin.keripik.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
                
                <!-- Search Input -->
                <div class="sm:col-span-5 relative">
                    <i data-lucide="search" class="w-4 h-4 text-stone-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Cari nama keripik, rasa, kategori..." 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-stone-300 bg-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-mentari-red/30 focus:border-mentari-red"
                    >
                </div>

                <!-- Category Filter Dropdown -->
                <div class="sm:col-span-3">
                    <select 
                        name="kategori" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 bg-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-mentari-red/30 focus:border-mentari-red"
                    >
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" @selected(request('kategori') == $cat)>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="sm:col-span-2">
                    <select 
                        name="status" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 bg-white text-xs sm:text-sm font-medium focus:outline-none focus:ring-2 focus:ring-mentari-red/30 focus:border-mentari-red"
                    >
                        <option value="">Semua Status</option>
                        <option value="1" @selected(request('status') === '1')>Aktif Saja</option>
                        <option value="0" @selected(request('status') === '0')>Nonaktif</option>
                    </select>
                </div>

                <!-- Action Filter Buttons -->
                <div class="sm:col-span-2 flex items-center gap-2">
                    <button 
                        type="submit" 
                        class="w-full bg-stone-900 hover:bg-black text-white py-2.5 rounded-xl font-bold text-xs transition"
                    >
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'kategori', 'status']))
                        <a 
                            href="{{ route('admin.keripik.index') }}" 
                            class="px-3 py-2.5 bg-stone-200 hover:bg-stone-300 text-stone-700 rounded-xl text-xs font-bold transition text-center"
                            title="Reset Filter"
                        >
                            Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>

        <!-- Table Responsive Container -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-stone-100/70 text-[11px] font-black uppercase tracking-wider text-stone-500 border-b border-stone-200/80">
                    <tr>
                        <th class="py-3.5 px-5">Foto & Produk</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Harga Satuan</th>
                        <th class="py-3.5 px-4">Stok</th>
                        <th class="py-3.5 px-4">Status Tampil</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse ($keripiks as $keripik)
                        <tr class="hover:bg-stone-50/70 transition">
                            
                            <!-- Foto & Nama Produk -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-12 h-12 rounded-xl bg-stone-100 border border-stone-200/80 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if($keripik->gambar_url)
                                            <img src="{{ $keripik->gambar_url }}" alt="{{ $keripik->nama }}" class="w-full h-full object-cover">
                                        @else
                                            <i data-lucide="image" class="w-5 h-5 text-stone-400"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="font-extrabold text-stone-900 text-sm leading-snug">{{ $keripik->nama }}</h3>
                                        <span class="text-xs text-stone-500 font-semibold flex items-center gap-1 mt-0.5">
                                            <i data-lucide="scale" class="w-3 h-3 text-stone-400"></i>
                                            {{ $keripik->berat }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Kategori -->
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200/80">
                                    {{ $keripik->kategori }}
                                </span>
                            </td>

                            <!-- Harga -->
                            <td class="py-4 px-4 font-black text-stone-900 text-sm">
                                Rp {{ number_format($keripik->harga, 0, ',', '.') }}
                            </td>

                            <!-- Stok -->
                            <td class="py-4 px-4">
                                @if($keripik->stok > 20)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 text-mentari-green-dark border border-green-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-mentari-green"></span>
                                        {{ $keripik->stok }} pcs
                                    </span>
                                @elseif($keripik->stok > 0)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        {{ $keripik->stok }} pcs (Menipis)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Habis
                                    </span>
                                @endif
                            </td>

                            <!-- Status Aktif -->
                            <td class="py-4 px-4">
                                @if($keripik->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-mentari-green-dark">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-stone-100 text-stone-500">
                                        <i data-lucide="eye-off" class="w-3 h-3"></i>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <!-- Aksi Edit / Hapus -->
                            <td class="py-4 px-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a 
                                        href="{{ route('admin.keripik.edit', $keripik) }}" 
                                        class="inline-flex items-center gap-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 px-3 py-1.5 rounded-xl text-xs font-bold transition"
                                        title="Edit Keripik"
                                    >
                                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                        <span>Edit</span>
                                    </a>

                                    <form 
                                        method="POST" 
                                        action="{{ route('admin.keripik.destroy', $keripik) }}" 
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk {{ $keripik->nama }}?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit" 
                                            class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 px-3 py-1.5 rounded-xl text-xs font-bold transition"
                                            title="Hapus Keripik"
                                        >
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-stone-500">
                                <i data-lucide="package-search" class="w-12 h-12 text-stone-300 mx-auto mb-3"></i>
                                <h4 class="text-base font-bold text-stone-800">Tidak ada data keripik</h4>
                                <p class="text-xs text-stone-500 mt-1">Belum ada data yang cocok dengan filter pencarian.</p>
                                <a 
                                    href="{{ route('admin.keripik.create') }}" 
                                    class="mt-4 inline-flex items-center gap-2 bg-mentari-red hover:bg-mentari-red-dark text-white px-4 py-2 rounded-xl text-xs font-bold shadow-xs transition"
                                >
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                    <span>Tambah Produk Pertama</span>
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($keripiks->hasPages())
            <div class="p-5 border-t border-stone-200/80 bg-stone-50/40">
                {{ $keripiks->links() }}
            </div>
        @endif

    </section>

</x-layouts.admin>