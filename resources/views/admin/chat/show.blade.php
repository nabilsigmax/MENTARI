<x-layouts.admin title="Chat - {{ $chatSession->customer_name ?? 'Pengunjung' }}">
    <div class="mb-6">
        <a href="{{ route('admin.chat.index') }}"
            class="inline-flex items-center gap-2 text-sm font-bold text-stone-500 hover:text-mentari-red transition">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Daftar Chat
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {{-- Sidebar: Chat Sessions List --}}
        <div class="hidden lg:block">
            <div class="bg-white border border-stone-200 rounded-xl overflow-hidden shadow-xs">
                <div class="px-4 py-3 bg-stone-50 border-b border-stone-200">
                    <h3 class="text-xs font-bold text-stone-600 uppercase tracking-wider">Daftar Chat</h3>
                </div>
                <div class="divide-y divide-stone-100 max-h-[600px] overflow-y-auto">
                    @foreach ($sessions as $session)
                        <a href="{{ route('admin.chat.show', $session) }}"
                            class="block px-4 py-3 hover:bg-stone-50 transition {{ $session->id === $chatSession->id ? 'bg-red-50 border-l-2 border-mentari-red' : '' }}">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-mentari-red/10 text-mentari-red flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ strtoupper(substr($session->customer_name ?? 'P', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-stone-800 truncate">
                                        {{ $session->customer_name ?? 'Pengunjung' }}</p>
                                    <p class="text-[11px] text-stone-400">{{ $session->messages_count }} pesan</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Main Chat Area --}}
        <div class="lg:col-span-3">
            <div class="bg-white border border-stone-200 rounded-xl shadow-xs overflow-hidden flex flex-col"
                style="height: 70vh; min-height: 500px;">

                {{-- Chat Header --}}
                <div class="px-6 py-4 bg-white border-b border-stone-200 flex items-center gap-4 shrink-0">
                    <div
                        class="w-10 h-10 rounded-full bg-mentari-red/10 text-mentari-red flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr($chatSession->customer_name ?? 'P', 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="font-bold text-stone-800">{{ $chatSession->customer_name ?? 'Pengunjung' }}</h2>
                        <p class="text-xs text-stone-500">Chat dimulai
                            {{ $chatSession->created_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Messages --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-4" id="chat-messages">
                    @forelse($chatSession->messages as $msg)
                        <div class="flex {{ $msg->is_admin ? 'justify-end' : 'justify-start' }}">
                            <div
                                class="max-w-[75%] {{ $msg->is_admin ? 'bg-mentari-red text-white' : 'bg-stone-100 text-stone-800' }} rounded-2xl px-4 py-3 shadow-xs">
                                <p class="text-sm leading-relaxed">{{ $msg->message }}</p>
                                <p class="text-[10px] mt-1.5 {{ $msg->is_admin ? 'text-red-200' : 'text-stone-400' }}">
                                    {{ $msg->is_admin ? 'Admin' : $chatSession->customer_name ?? 'Pengunjung' }}
                                    · {{ $msg->created_at->format('H:i') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i data-lucide="message-circle" class="w-12 h-12 text-stone-300 mx-auto mb-3"></i>
                            <p class="text-sm text-stone-500">Belum ada pesan dalam sesi chat ini.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Reply Form --}}
                <div class="px-6 py-4 bg-stone-50 border-t border-stone-200 shrink-0">
                    <form action="{{ route('admin.chat.reply', $chatSession) }}" method="POST"
                        class="flex items-end gap-3">
                        @csrf
                        <div class="flex-1">
                            <textarea name="message" rows="2" placeholder="Ketik balasan untuk pelanggan..."
                                class="w-full resize-none rounded-xl border border-stone-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-mentari-red/30 focus:border-mentari-red transition"
                                required></textarea>
                            @error('message')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-mentari-red hover:bg-mentari-red-dark text-white px-5 py-3 rounded-xl text-sm font-bold transition shadow-xs">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Kirim
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-scroll to bottom of messages
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('chat-messages');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    </script>
</x-layouts.admin>
