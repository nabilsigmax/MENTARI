<x-layouts.admin title="Edit Keripik: {{ $keripik->nama }}">

    <!-- Breadcrumb & Header -->
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-xs font-semibold text-stone-500 mb-2">
            <a href="{{ route('admin.keripik.index') }}" class="hover:text-mentari-red">Katalog Keripik</a>
            <span>/</span>
            <span class="text-stone-800">Edit Produk</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-black text-stone-900 tracking-tight">Edit: {{ $keripik->nama }}</h1>
        <p class="text-stone-500 text-sm mt-1">Perbarui harga, stok, foto produk, atau deskripsi varian keripik.</p>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-3xl border border-stone-200/90 shadow-xs p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.keripik.update', $keripik) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.keripik.form', ['keripik' => $keripik])
        </form>
    </div>

</x-layouts.admin>