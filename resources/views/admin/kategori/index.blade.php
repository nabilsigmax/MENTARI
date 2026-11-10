<x-layouts.admin title="Kategori Produk">

    <!-- PAGE HEADER -->
    <div class="mb-8">
        <nav class="flex items-center gap-2 text-xs font-semibold text-stone-500 mb-2">
            <a href="{{ route('admin.keripik.index') }}" class="hover:text-mentari-green">Katalog Keripik</a>
            <span>/</span>
            <span class="text-stone-800">Kategori</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-black text-stone-900 tracking-tight">Kategori Produk</h1>
        <p class="text-stone-500 text-sm mt-1">Tambah, ubah, atau hapus kategori yang dipakai untuk mengelompokkan produk keripik.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-800 shadow-xs">
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- FORM TAMBAH KATEGORI -->
        <section class="bg-white rounded-3xl border border-stone-200/90 shadow-xs p-6 h-fit">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-2xl bg-green-50 text-mentari-green flex items-center justify-center">
                    <i data-lucide="tag" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="font-extrabold text-stone-900 text-sm">Tambah Kategori Baru</h2>
                    <p class="text-xs text-stone-500 mt-0.5">Kategori langsung bisa dipilih di form produk.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.kategori.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="nama" class="block text-sm font-extrabold text-stone-900 mb-1">
                        Nama Kategori <span class="text-green-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        value="{{ old('nama') }}"
                        placeholder="Contoh: Keripik Sayur"
                        required
                        class="w-full px-4 py-3 rounded-2xl border border-stone-300 bg-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-mentari-green/30 focus:border-mentari-green"
                    >
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 w-full bg-mentari-green hover:bg-mentari-green-dark text-white px-5 py-3 rounded-2xl font-bold text-sm shadow-md transition"
                >
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Simpan Kategori</span>
                </button>
            </form>
        </section>

        <!-- DAFTAR KATEGORI -->
        <section class="lg:col-span-2 bg-white rounded-3xl border border-stone-200/90 shadow-xs overflow-hidden">
            <div class="p-5 sm:p-6 border-b border-stone-200/80 bg-stone-50/50 flex items-center justify-between gap-3">
                <div>
                    <h2 class="font-extrabold text-stone-900 text-sm">Daftar Kategori</h2>
                    <p class="text-xs text-stone-500 mt-0.5">{{ $kategoris->count() }} kategori terdaftar.</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                    <i data-lucide="tags" class="w-3.5 h-3.5"></i>
                    {{ $kategoris->count() }} jenis
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-stone-100/70 text-[11px] font-black uppercase tracking-wider text-stone-500 border-b border-stone-200/80">
                        <tr>
                            <th class="py-3.5 px-5">Nama Kategori</th>
                            <th class="py-3.5 px-4">Jumlah Produk</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100">
                        @forelse ($kategoris as $kategori)
                            <tr class="hover:bg-stone-50/70 transition">
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 border border-amber-200/80 flex items-center justify-center shrink-0">
                                            <i data-lucide="tag" class="w-4 h-4"></i>
                                        </div>
                                        <span class="font-extrabold text-stone-900">{{ $kategori->nama }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-stone-100 text-stone-700">
                                        {{ $kategori->keripiks_count }} produk
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('admin.kategori.edit', $kategori) }}"
                                            class="inline-flex items-center gap-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 px-3 py-1.5 rounded-xl text-xs font-bold transition"
                                            title="Ubah Kategori"
                                        >
                                            <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                            <span>Edit</span>
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.kategori.destroy', $kategori) }}"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $kategori->nama }}?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-700 border border-red-200 px-3 py-1.5 rounded-xl text-xs font-bold transition"
                                                title="Hapus Kategori"
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
                                <td colspan="3" class="py-16 text-center text-stone-500">
                                    <i data-lucide="tags" class="w-12 h-12 text-stone-300 mx-auto mb-3"></i>
                                    <h4 class="text-base font-bold text-stone-800">Belum ada kategori</h4>
                                    <p class="text-xs text-stone-500 mt-1">Tambahkan kategori pertama melalui formulir di samping.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

</x-layouts.admin>
