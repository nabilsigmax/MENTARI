<x-layouts.admin title="Kelola Pesanan">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-stone-800">Daftar Pesanan</h1>
            <p class="text-stone-500 text-sm mt-1">Kelola transaksi dan pesanan pelanggan.</p>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-xl border border-stone-200 mb-6 shadow-xs flex flex-col sm:flex-row gap-4 justify-between items-center">
        <form action="{{ route('admin.pesanan.index') }}" method="GET" class="flex flex-1 w-full gap-2">
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i data-lucide="search" class="h-4 w-4 text-stone-400"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="block w-full pl-10 pr-3 py-2 border border-stone-200 rounded-lg text-sm focus:outline-none focus:ring-1 focus:ring-mentari-red focus:border-mentari-red" placeholder="Cari nama atau ID...">
            </div>
            <select name="status" class="border border-stone-200 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-mentari-red">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
            </select>
            <button type="submit" class="bg-stone-900 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-black transition">Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.pesanan.index') }}" title="Reset Filter" class="px-4 py-2 bg-stone-200 hover:bg-stone-300 text-stone-700 rounded-lg text-sm font-bold transition">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white border border-stone-200 rounded-xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-stone-50 border-b border-stone-200 text-xs uppercase tracking-wider text-stone-500">
                        <th class="px-6 py-4 font-semibold">ID</th>
                        <th class="px-6 py-4 font-semibold">Pembeli</th>
                        <th class="px-6 py-4 font-semibold">Produk</th>
                        <th class="px-6 py-4 font-semibold">Total</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-sm">
                    @forelse($pesanans as $pesanan)
                        <tr class="hover:bg-stone-50 transition">
                            <td class="px-6 py-4 text-stone-900 font-medium whitespace-nowrap">#{{ str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-stone-900">{{ $pesanan->nama_pembeli }}</div>
                                <div class="text-stone-500 text-xs mt-0.5">{{ $pesanan->no_hp }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-stone-900">{{ $pesanan->keripik->nama }}</div>
                                <div class="text-stone-500 text-xs mt-0.5">{{ $pesanan->jumlah }} pcs</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-stone-900">
                                Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'dibayar' => 'bg-blue-100 text-blue-800',
                                        'diproses' => 'bg-indigo-100 text-indigo-800',
                                        'dikirim' => 'bg-purple-100 text-purple-800',
                                        'selesai' => 'bg-green-100 text-mentari-green-dark',
                                        'batal' => 'bg-red-100 text-red-800',
                                    ];
                                    $color = $colors[$pesanan->status] ?? 'bg-stone-100 text-stone-800';
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $color }}">
                                    {{ $pesanan->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.pesanan.show', $pesanan->id) }}" class="inline-flex items-center gap-1.5 bg-stone-100 hover:bg-stone-200 text-stone-700 border border-stone-200 px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-stone-500">
                                <i data-lucide="inbox" class="h-10 w-10 mx-auto text-stone-300 mb-3"></i>
                                <p>Belum ada data pesanan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pesanans->hasPages())
            <div class="px-6 py-4 border-t border-stone-200">
                {{ $pesanans->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>

