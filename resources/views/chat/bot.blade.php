@extends('layouts.chat')

@section('content')
<div class="flex flex-col h-screen">

    {{-- HEADER --}}
    <header class="glass px-4 py-3 flex items-center justify-between flex-shrink-0 border-b border-white/5">
        <div class="flex items-center gap-3">
            <a href="{{ route('chat.index') }}" class="text-gray-400 hover:text-cy-400 transition" title="Volver al chat">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cy-400 to-cy-500 grid place-items-center">
                    <svg class="w-[18px] h-[18px] text-d-900" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <span class="font-mono text-[15px] font-bold"><span class="grad-text">Crypto</span><span class="text-gray-300">Chat</span></span>
            </div>
            <span class="hidden sm:flex items-center gap-1 text-[11px] text-gray-500 font-mono bg-d-700 px-2 py-0.5 rounded-full">
                <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                AI Bot
            </span>
        </div>
        <div class="flex items-center gap-3">
            <span class="hidden sm:block text-sm text-gray-400">{{ $currentUser->name }}</span>
            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-fuchsia-500 grid place-items-center text-white text-xs font-bold">
                {{ strtoupper(substr($currentUser->name, 0, 2)) }}
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-rose-400 transition" title="Salir">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">

        {{-- SIDEBAR --}}
        <aside class="w-72 bg-d-800 border-r border-white/5 flex flex-col flex-shrink-0 hidden lg:flex">
            <div class="p-3 border-b border-white/5">
                <h3 class="text-xs text-gray-500 font-mono uppercase tracking-wider px-2">Navegación</h3>
            </div>
            <div class="flex-1 overflow-y-auto p-2">
                {{-- Volver al chat --}}
                <a href="{{ route('chat.index') }}" class="c-item flex items-center gap-3 px-3 py-3 rounded-xl mb-1 transition-all cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-400 grid place-items-center text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-200">Chat de Usuarios</p>
                        <p class="text-[11px] text-gray-500 font-mono">Mensajes cifrados</p>
                    </div>
                </a>

                {{-- Bot activo --}}
                <div class="c-active flex items-center gap-3 px-3 py-3 rounded-xl mb-1">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-cyan-400 grid place-items-center text-d-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714a2.25 2.25 0 00.659 1.591L19 14.5M14.25 3.104c.251.023.501.05.75.082M19 14.5l-2.47 2.47a2.25 2.25 0 01-1.591.659H9.061a2.25 2.25 0 01-1.591-.659L5 14.5m14 0V17a2 2 0 01-2 2H7a2 2 0 01-2-2v-2.5"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-200">CryptoBot IA</p>
                        <p class="text-[11px] text-emerald-400 font-mono">En línea</p>
                    </div>
                </div>
            </div>

            {{-- Botón limpiar historial --}}
            <div class="p-3 border-t border-white/5">
                <form method="POST" action="{{ route('chatbot.clear') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 text-xs text-gray-500 hover:text-rose-400 font-mono py-2 px-3 rounded-lg hover:bg-d-700 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        Limpiar historial
                    </button>
                </form>
            </div>
        </aside>

        {{-- CHAT AREA --}}
        <main class="flex-1 flex flex-col min-w-0">

            {{-- Bot Header --}}
            <div class="bg-d-800/60 backdrop-blur border-b border-white/5 px-4 sm:px-6 py-3 flex items-center gap-3 flex-shrink-0">
                <div class="relative">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-cyan-400 grid place-items-center text-d-900 text-sm font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714a2.25 2.25 0 00.659 1.591L19 14.5M14.25 3.104c.251.023.501.05.75.082M19 14.5l-2.47 2.47a2.25 2.25 0 01-1.591.659H9.061a2.25 2.25 0 01-1.591-.659L5 14.5m14 0V17a2 2 0 01-2 2H7a2 2 0 01-2-2v-2.5"/></svg>
                    </div>
                    <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-d-700 pulse"></div>
                </div>
                <div>
                    <h2 class="text-sm font-semibold text-gray-200">CryptoBot IA</h2>
                    <p class="text-[11px] text-emerald-400/70 font-mono flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        Asistente inteligente · Código · FAQ
                    </p>
                </div>
                {{-- Mobile: limpiar y volver --}}
                <div class="ml-auto flex items-center gap-2 lg:hidden">
                    <a href="{{ route('chat.index') }}" class="text-gray-400 hover:text-cy-400 transition text-xs font-mono bg-d-700 px-2 py-1 rounded-lg">Chat</a>
                    <form method="POST" action="{{ route('chatbot.clear') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-rose-400 transition text-xs font-mono bg-d-700 px-2 py-1 rounded-lg">Limpiar</button>
                    </form>
                </div>
            </div>

            {{-- Messages --}}
            <div id="msgBox" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-2">
                @if(empty($botMessages))
                    {{-- Welcome --}}
                    <div class="flex justify-start fade-in">
                        <div class="max-w-[80%] sm:max-w-[70%]">
                            <div class="msg-in rounded-2xl rounded-bl-md px-4 py-3">
                                <p class="text-sm leading-relaxed">
                                    👋 <strong>¡Hola {{ $currentUser->name }}!</strong> Soy <strong>CryptoBot</strong>, tu asistente de IA.<br><br>
                                    Puedo ayudarte con:<br>
                                    💻 <strong>Código</strong> — Pega tu código y lo analizo<br>
                                    ❓ <strong>Preguntas</strong> — Sobre cualquier tema<br>
                                    🔐 <strong>Cifrado</strong> — Cómo funciona AES-256<br>
                                    ⚡ <strong>Laravel/PHP/JS</strong> — Tips y ejemplos<br><br>
                                    ¡Escríbeme algo!
                                </p>
                            </div>
                            <p class="text-[10px] text-gray-600 mt-0.5 font-mono">CryptoBot · {{ now()->format('H:i') }}</p>
                        </div>
                    </div>
                @else
                    @foreach($botMessages as $msg)
                        <div class="flex {{ $msg['role'] === 'user' ? 'justify-end' : 'justify-start' }} fade-in">
                            <div class="max-w-[80%] sm:max-w-[70%]">
                                <div class="{{ $msg['role'] === 'user' ? 'msg-out rounded-2xl rounded-br-md' : 'msg-in rounded-2xl rounded-bl-md' }} px-4 py-2.5">
                                    <div class="text-sm leading-relaxed break-words bot-content">{!! $msg['role'] === 'assistant' ? nl2br(e($msg['content'])) : e($msg['content']) !!}</div>
                                </div>
                                <p class="text-[10px] text-gray-600 mt-0.5 font-mono {{ $msg['role'] === 'user' ? 'text-right' : '' }}">
                                    {{ $msg['role'] === 'user' ? $currentUser->name : 'CryptoBot' }} · {{ $msg['time'] ?? '' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- Loading indicator (hidden by default) --}}
                <div id="loadingMsg" class="flex justify-start fade-in" style="display:none">
                    <div class="max-w-[60%]">
                        <div class="msg-in rounded-2xl rounded-bl-md px-4 py-3">
                            <div class="flex items-center gap-2 text-sm text-gray-400">
                                <div class="flex gap-1">
                                    <span class="w-2 h-2 bg-cy-400 rounded-full animate-bounce" style="animation-delay:0s"></span>
                                    <span class="w-2 h-2 bg-cy-400 rounded-full animate-bounce" style="animation-delay:.15s"></span>
                                    <span class="w-2 h-2 bg-cy-400 rounded-full animate-bounce" style="animation-delay:.3s"></span>
                                </div>
                                CryptoBot está pensando...
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Input --}}
            <div class="bg-d-800/60 backdrop-blur border-t border-white/5 p-3 sm:p-4 flex-shrink-0">
                <form id="botForm" method="POST" action="{{ route('chatbot.send') }}" class="flex items-end gap-2">
                    @csrf
                    <textarea name="content" id="botInput" rows="1" placeholder="Pregúntale algo a CryptoBot o pega tu código..." required
                        class="flex-1 bg-d-700 text-sm text-gray-200 placeholder-gray-600 rounded-xl px-4 py-3 border border-white/5 focus:border-cy-400/30 transition resize-none max-h-40 font-mono"></textarea>
                    <button type="submit" id="sendBtn" class="bg-gradient-to-r from-emerald-400 to-cyan-400 text-d-900 rounded-xl px-4 sm:px-5 py-3 font-semibold text-sm hover:opacity-90 transition glow flex items-center gap-1.5 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span class="hidden sm:inline">Enviar</span>
                    </button>
                </form>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
// Auto-scroll
const mb = document.getElementById('msgBox');
if (mb) mb.scrollTop = mb.scrollHeight;

// Textarea auto-resize
const ta = document.getElementById('botInput');
if (ta) {
    ta.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 160) + 'px';
    });

    // Enter para enviar, Shift+Enter para nueva línea
    ta.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (this.value.trim()) document.getElementById('botForm').submit();
        }
    });
}

// Mostrar loading al enviar
document.getElementById('botForm')?.addEventListener('submit', function() {
    const loading = document.getElementById('loadingMsg');
    if (loading) {
        loading.style.display = 'flex';
        mb.scrollTop = mb.scrollHeight;
    }
    document.getElementById('sendBtn').disabled = true;
    document.getElementById('sendBtn').style.opacity = '0.5';
});
</script>
@endpush
@endsection
