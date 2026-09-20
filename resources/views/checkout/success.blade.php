<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Berhasil - Keripik Mentari</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
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
</head>
<body class="bg-mentari-gray font-sans text-stone-800 flex items-center justify-center min-h-screen">

    <div class="max-w-md w-full bg-white p-8 rounded-sm shadow-sm text-center">
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="check-circle" class="w-8 h-8"></i>
        </div>
        
        <h1 class="text-2xl font-bold text-stone-900 mb-2">Pesanan Berhasil!</h1>
        <p class="text-stone-500 mb-6 text-sm">Terima kasih <strong>{{ $pesanan->nama_pembeli }}</strong>, pesanan Anda telah kami terima.</p>
        
        <div class="bg-stone-50 p-4 rounded text-left text-sm space-y-2 mb-6">
            <div class="flex justify-between">
                <span class="text-stone-500">ID Pesanan</span>
                <span class="font-bold text-stone-900">#ORD-{{ str_pad($pesanan->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-stone-500">Total Pembayaran</span>
                <span class="font-bold text-mentari-red">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-stone-500">Metode</span>
                <span class="font-bold text-stone-900">{{ $pesanan->metode_pembayaran }}</span>
            </div>
        </div>

        @if($pesanan->metode_pembayaran !== 'COD' && $pesanan->status === 'pending')
            <div class="text-sm bg-yellow-50 text-yellow-800 p-3 rounded mb-6 text-left">
                Harap segera lakukan pembayaran dan konfirmasi melalui WhatsApp kami jika belum melampirkan bukti.
            </div>
        @endif

        <div class="space-y-3">
            @php
                $waText = urlencode("Halo Keripik Mentari, saya sudah order dengan ID #ORD-".str_pad($pesanan->id, 4, '0', STR_PAD_LEFT).".");
            @endphp
            <a href="https://wa.me/6281234567890?text={{ $waText }}" target="_blank" class="block w-full bg-green-500 text-white font-bold py-3 px-4 rounded-sm hover:bg-green-600 transition-colors flex items-center justify-center gap-2">
                <i data-lucide="message-circle" class="w-5 h-5"></i>
                Konfirmasi via WhatsApp
            </a>
            
            <a href="{{ route('home') }}" class="block w-full border border-stone-300 text-stone-700 font-bold py-3 px-4 rounded-sm hover:bg-stone-50 transition-colors">
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    </script>
</body>
</html>

