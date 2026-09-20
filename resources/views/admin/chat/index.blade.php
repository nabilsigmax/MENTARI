<x-layouts.admin title="Chat Pelanggan">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-stone-800">Chat Pelanggan</h1>
            <p class="text-stone-500 text-sm mt-1">Kelola pesan dari pelanggan yang menghubungi via chat.</p>
        </div>
    </div>

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
                <a href="{{ route('admin.chat.show', $session) }}"
                    class="block bg-white border border-stone-200 rounded-xl p-5 hover:border-mentari-red/40 hover:shadow-sm transition group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 rounded-full bg-mentari-red/10 text-mentari-red flex items-center justify-center font-bold text-sm shrink-0">
                                {{ strtoupper(substr($session->customer_name ?? 'P', 0, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-stone-800 group-hover:text-mentari-red transition">
                                    {{ $session->customer_name ?? 'Pengunjung' }}
                                </h4>
                                <p class="text-xs text-stone-500 mt-0.5">
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
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $sessions->links() }}
        </div>
    @endif
</x-layouts.admin>
