<x-layouts.admin title="Chat Pelanggan">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-stone-800">Chat Pelanggan</h1>
            <p class="text-stone-500 text-sm mt-1">Kelola pesan dari pelanggan yang menghubungi via chat.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($sessions->isEmpty())
        <div class="bg-white border border-stone-200 rounded-2xl p-12 text-center shadow-xs">
            <i data-lucide="message-circle" class="w-16 h-16 text-stone-300 mx-auto mb-4"></i>
            <h3 class="text-lg font-bold text-stone-700">Belum Ada Chat</h3>
            <p class="text-stone-500 text-sm mt-2">Pesan dari pelanggan akan muncul di sini ketika mereka mulai
                menghubungi Anda melalui chat widget.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-4">
            @foreach ($sessions as $session)
                <div
                    class="flex items-center gap-2 bg-white border border-stone-200 rounded-xl hover:border-mentari-red/40 hover:shadow-sm transition group">

                    <a href="{{ route('admin.chat.show', $session) }}"
                        class="flex flex-1 min-w-0 items-center justify-between gap-4 p-5">
                        <div class="flex items-center gap-4 min-w-0">
                            <div
                                class="w-10 h-10 rounded-full bg-mentari-red/10 text-mentari-red flex items-center justify-center font-bold text-sm shrink-0">
                                {{ strtoupper(substr($session->customer_name ?? 'P', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-stone-800 group-hover:text-mentari-red transition truncate">
                                    {{ $session->customer_name ?? 'Pengunjung' }}
                                </h4>
                                <p class="text-xs text-stone-500 mt-0.5 truncate">
                                    @if ($session->messages->first())
                                        {{ Str::limit($session->messages->first()->message, 60) }}
                                    @else
                                        <span class="italic">Belum ada pesan</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span
                                class="inline-flex items-center gap-1 text-xs font-bold text-stone-500 bg-stone-100 px-2.5 py-1 rounded-full">
                                <i data-lucide="message-square" class="w-3 h-3"></i>
                                {{ $session->messages_count }} pesan
                            </span>
                            <p class="text-[11px] text-stone-400 mt-1">{{ $session->updated_at->diffForHumans() }}</p>
                        </div>
                    </a>

                    <form method="POST" action="{{ route('admin.chat.destroy', $session) }}" class="pr-4 shrink-0"
                        onsubmit="return confirm(@js('Hapus chat dengan ' . ($session->customer_name ?? 'Pengunjung') . ' beserta semua pesannya? Tindakan ini tidak bisa dibatalkan.'))">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Hapus chat" aria-label="Hapus chat"
                            class="w-9 h-9 rounded-lg flex items-center justify-center text-stone-400 hover:text-red-600 hover:bg-red-50 transition focus-visible:outline focus-visible:outline-2 focus-visible:outline-red-500">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $sessions->links() }}
        </div>
    @endif
</x-layouts.admin>