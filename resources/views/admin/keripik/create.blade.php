<x-layouts.admin title="Tambah Keripik Baru">

    <!-- Breadcrumb & Header -->
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-xs font-semibold text-stone-500 mb-2">
            <a href="{{ route('admin.keripik.index') }}" class="hover:text-mentari-red">Katalog Keripik</a>
            
        </nav>
        <h1 class="text-2xl sm:text-3xl font-black text-stone-900 tracking-tight">Tambah Produk Keripik Baru</h1>
        <p class="text-stone-500 text-sm mt-1">Lengkapi formulir di bawah ini untuk menambahkan varian keripik baru ke katalog.</p>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-3xl border border-stone-200/90 shadow-xs p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.keripik.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.keripik.form')
        </form>
    </div>

</x-layouts.admin>