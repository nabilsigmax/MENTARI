<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pesanan #{{ str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) }} - Keripik Mentari</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        mentari: {
                            red: '#EE4D2D',
                            'red-dark': '#D73211',
                            green: '#16A34A',
                            'green-dark': '#15803D',
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
</head>

<body class="min-h-screen bg-mentari-gray font-sans text-stone-800">
    <header class="sticky top-0 z-40 border-b border-stone-200 bg-white shadow-sm">
        <div class="mx-auto flex h-16 max-w-4xl items-center justify-between px-4">
            <a href="{{ route('orders.index') }}" class="flex items-center gap-3">
                <i data-lucide="chevron-left" class="h-6 w-6 text-mentari-red"></i>
                <span class="text-lg font-bold text-stone-900">Detail Pesanan</span>
            </a>
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-stone-600">{{ auth()->user()->name ?? 'Guest' }}</span>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-4xl p-4 py-6 space-y-4">
        <div class="rounded-sm bg-white p-4 shadow-sm">
            <div class="flex items-center justify-between border-b border-stone-100 pb-3">
                <div>
                    <p class="text-xs text-stone-500">Nomor Pesanan</p>
                    <p class="text-lg font-black text-stone-900">#{{ str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) }}</p>
                </div>
                <span class="text-sm font-bold uppercase text-mentari-red">
                    @if ($pesanan->status === 'pending')
                        Belum Bayar
                    @elseif($pesanan->status === 'dibayar')
                        Menunggu Konfirmasi
                    @elseif($pesanan->status === 'diproses')
                        Diproses
                    @elseif($pesanan->status === 'dikirim')
                        Sedang Dikirim
                    @elseif($pesanan->status === 'selesai')
                        Telah Diterima
                    @elseif($pesanan->status === 'batal')
                        Dibatalkan
                    @else
                        {{ $pesanan->status }}
                    @endif
                </span>
            </div>

            <div class="flex items-start gap-4 py-4">
                <img src="{{ $pesanan->keripik->gambar_url ?? 'https://via.placeholder.com/80' }}"
                    alt="{{ $pesanan->keripik->nama ?? 'Produk' }}"
                    class="h-20 w-20 rounded border border-stone-200 object-cover">
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-stone-800">{{ $pesanan->keripik->nama ?? 'Nama Produk' }}</h3>
                    <p class="mt-1 text-sm text-stone-500">Variasi: Original, {{ $pesanan->keripik->berat ?? '250g' }}</p>
                    <p class="mt-1 text-sm font-medium text-stone-700">x{{ $pesanan->jumlah }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-stone-800">
                        Rp{{ number_format($pesanan->keripik->harga ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="border-t border-stone-100 pt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-stone-500">Tanggal Pesanan</span>
                    <span class="font-semibold text-stone-800">{{ $pesanan->created_at->translatedFormat('d M Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-500">Total Pesanan</span>
                    <span class="text-lg font-black text-mentari-red">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-stone-500">Metode Pembayaran</span>
                    <span class="font-semibold text-stone-800">{{ $pesanan->metode_pembayaran }}</span>
                </div>
            </div>
        </div>

        <div class="rounded-sm bg-white p-4 shadow-sm">
            <h2 class="text-sm font-bold uppercase tracking-wider text-stone-700 mb-3">Data Pelanggan & Pengiriman</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between gap-4">
                    <span class="text-stone-500">Nama</span>
                    <span class="font-semibold text-stone-800 text-right">{{ $pesanan->nama_pembeli }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-stone-500">No. WhatsApp</span>
                    <span class="font-semibold text-stone-800 text-right">{{ $pesanan->no_hp }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-stone-500">Alamat</span>
                    <span class="font-semibold text-stone-800 text-right">{{ $pesanan->alamat }}</span>
                </div>
            </div>
        </div>

        @if ($pesanan->nomor_resi || $pesanan->tanggal_dikirim || $pesanan->estimasi_tiba || $pesanan->tanggal_diterima)
            <div class="rounded-sm bg-white p-4 shadow-sm">
                <h2 class="text-sm font-bold uppercase tracking-wider text-stone-700 mb-3">Detail Pengiriman</h2>
                <div class="space-y-2 text-sm">
                    @if ($pesanan->nomor_resi)
                        <div class="flex justify-between gap-4">
                            <span class="text-stone-500">No. Resi</span>
                            <span class="font-bold text-stone-800">{{ $pesanan->nomor_resi }}</span>
                        </div>
                    @endif
                    @if ($pesanan->tanggal_dikirim)
                        <div class="flex justify-between gap-4">
                            <span class="text-stone-500">Dikirim</span>
                            <span class="font-semibold text-stone-800">{{ $pesanan->tanggal_dikirim->translatedFormat('d M Y') }}</span>
                        </div>
                    @endif
                    @if ($pesanan->estimasi_tiba)
                        <div class="flex justify-between gap-4">
                            <span class="text-stone-500">Estimasi Tiba</span>
                            <span class="font-semibold text-stone-800">{{ $pesanan->estimasi_tiba->translatedFormat('d M Y') }}</span>
                        </div>
                    @endif
                    @if ($pesanan->tanggal_diterima)
                        <div class="flex justify-between gap-4">
                            <span class="text-stone-500">Diterima</span>
                            <span class="font-bold text-mentari-green">{{ $pesanan->tanggal_diterima->translatedFormat('d M Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    </script>
</body>

</html>
