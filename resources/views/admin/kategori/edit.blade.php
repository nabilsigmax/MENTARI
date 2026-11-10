<x-layouts.admin title="Edit Kategori: {{ $kategori->nama }}">

    <!-- PAGE HEADER -->
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-xs font-semibold text-stone-500 mb-2">
            <a href="{{ route('admin.kategori.index') }}" class="hover:text-mentari-green">Kategori</a>
            <span>/</span>
            <span class="text-stone-800">Edit Kategori</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-black text-stone-900 tracking-tight">Edit: {{ $kategori->nama }}</h1>
        <p class="text-stone-500 text-sm mt-1">Mengubah nama kategori juga akan memperbarui kategori semua produk yang memakainya.</p>
    </div>

    <div class="max-w-xl bg-white rounded-3xl border border-stone-200/90 shadow-xs p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.kategori.update', $kategori) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="nama" class="block text-sm font-extrabold text-stone-900 mb-1">
                    Nama Kategori <span class="text-green-500">*</span>
                </label>
                <input
                    type="text"
                    name="nama"
                    id="nama"
                    value="{{ old('nama', $kategori->nama) }}"
                    required
                    class="w-full px-4 py-3 rounded-2xl border border-stone-300 bg-white text-sm font-medium focus:outline-none focus:ring-2 focus:ring-mentari-green/30 focus:border-mentari-green"
                >
                @error('nama')
                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-4 border-t border-stone-200 flex items-center gap-3">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 bg-mentari-green hover:bg-mentari-green-dark text-white px-7 py-3.5 rounded-2xl font-bold text-sm shadow-md transition hover:-translate-y-0.5"
                >
                    <i data-lucide="save" class="w-4 h-4"></i>
                    <span>Simpan Perubahan</span>
                </button>

                <a
                    href="{{ route('admin.kategori.index') }}"
                    class="px-6 py-3.5 rounded-2xl bg-stone-200 hover:bg-stone-300 text-stone-700 font-bold text-sm transition"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>

</x-layouts.admin>
