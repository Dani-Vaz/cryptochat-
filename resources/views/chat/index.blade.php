@extends('layouts.chat')

@section('content')
<div class="flex flex-col h-screen">

    {{-- HEADER --}}
    <header class="glass px-4 py-3 flex items-center justify-between flex-shrink-0 border-b border-white/5">
        <div class="flex items-center gap-3">
            <button id="menuBtn" class="lg:hidden text-gray-400 hover:text-cy-400 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cy-400 to-cy-500 grid place-items-center">
                    <svg class="w-[18px] h-[18px] text-d-900" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <span class="font-mono text-[15px] font-bold"><span class="grad-text">Crypto</span><span class="text-gray-300">Chat</span></span>
            </div>
            <span class="hidden sm:flex items-center gap-1 text-[11px] text-gray-500 font-mono bg-d-700 px-2 py-0.5 rounded-full">
                <svg class="w-3 h-3 text-cy-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                AES-256-CBC
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
        <aside id="sidebar" class="w-72 bg-d-800 border-r border-white/5 flex flex-col flex-shrink-0 hidden lg:flex">
            <div class="p-3 border-b border-white/5">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" id="searchBox" placeholder="Buscar contacto..." class="w-full bg-d-700 text-sm text-gray-300 placeholder-gray-600 rounded-xl pl-10 pr-4 py-2.5 border border-white/5 focus:border-cy-400/30 transition">
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-2" id="contactList">
                {{-- CryptoBot IA --}}
                <a href="{{ route('chatbot.index') }}" class="c-item flex items-center gap-3 px-3 py-3 rounded-xl mb-2 transition-all cursor-pointer border border-emerald-400/10 hover:border-emerald-400/20">
                    <div class="relative flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-cyan-400 grid place-items-center text-d-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714a2.25 2.25 0 00.659 1.591L19 14.5M14.25 3.104c.251.023.501.05.75.082M19 14.5l-2.47 2.47a2.25 2.25 0 01-1.591.659H9.061a2.25 2.25 0 01-1.591-.659L5 14.5m14 0V17a2 2 0 01-2 2H7a2 2 0 01-2-2v-2.5"/></svg>
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-d-800 pulse"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-emerald-300">CryptoBot IA</p>
                        <p class="text-[11px] text-emerald-400/60 font-mono">Asistente · Código · FAQ</p>
                    </div>
                </a>

                @forelse($contacts as $contact)
                    <a href="{{ route('chat.index', ['contact' => $contact->id]) }}"
                       class="c-item flex items-center gap-3 px-3 py-3 rounded-xl mb-1 transition-all cursor-pointer {{ $selectedContact && $selectedContact->id === $contact->id ? 'c-active' : '' }}">
                        <div class="relative flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ ['from-blue-500 to-cyan-400','from-violet-500 to-fuchsia-400','from-amber-500 to-orange-400','from-emerald-500 to-teal-400','from-rose-500 to-pink-400'][($contact->id - 1) % 5] }} grid place-items-center text-white text-sm font-bold">
                                {{ strtoupper(substr($contact->name, 0, 2)) }}
                            </div>
                            <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-d-800 pulse"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-200 truncate">{{ $contact->name }}</p>
                            <p class="text-[11px] text-gray-500 truncate font-mono">{{ $contact->email }}</p>
                        </div>
                    </a>
                @empty
                    <div class="flex flex-col items-center justify-center h-full text-center p-6">
                        <svg class="w-12 h-12 text-gray-700 mb-3" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="text-gray-500 text-sm">Sin contactos</p>
                        <p class="text-gray-600 text-xs mt-1">Registra más usuarios</p>
                    </div>
                @endforelse
            </div>

            <div class="p-3 border-t border-white/5">
                <p class="flex items-center gap-2 text-[11px] text-gray-600 font-mono">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    {{ $currentUser->name }}
                </p>
            </div>
        </aside>

        {{-- CHAT AREA --}}
        <main class="flex-1 flex flex-col min-w-0">
            @if($selectedContact)

                {{-- Contact Header --}}
                <div class="bg-d-800/60 backdrop-blur border-b border-white/5 px-4 sm:px-6 py-3 flex items-center gap-3 flex-shrink-0">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ ['from-blue-500 to-cyan-400','from-violet-500 to-fuchsia-400','from-amber-500 to-orange-400','from-emerald-500 to-teal-400','from-rose-500 to-pink-400'][($selectedContact->id - 1) % 5] }} grid place-items-center text-white text-sm font-bold">
                            {{ strtoupper(substr($selectedContact->name, 0, 2)) }}
                        </div>
                        <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-400 rounded-full border-2 border-d-700"></div>
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-gray-200">{{ $selectedContact->name }}</h2>
                        <p class="text-[11px] text-cy-400/60 font-mono flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                            Cifrado extremo a extremo
                        </p>
                    </div>
                </div>

                {{-- Messages --}}
                <div id="msgBox" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-2">
                    @if($messages->isEmpty())
                        <div class="flex flex-col items-center justify-center h-full text-center">
                            <div class="w-16 h-16 rounded-2xl bg-d-600 grid place-items-center mb-3">
                                <svg class="w-8 h-8 text-cy-400/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <p class="text-gray-500 text-sm">Sin mensajes aún</p>
                            <p class="text-gray-600 text-xs mt-1 font-mono">Envía el primer mensaje cifrado</p>
                        </div>
                    @else
                        @php $lastDate = null; @endphp
                        @foreach($messages as $msg)
                            @if($lastDate !== $msg->created_at->format('d/m/Y'))
                                @php $lastDate = $msg->created_at->format('d/m/Y'); @endphp
                                <div class="flex items-center gap-3 my-3">
                                    <div class="flex-1 h-px bg-white/5"></div>
                                    <span class="text-[10px] text-gray-600 font-mono bg-d-800 px-2.5 py-0.5 rounded-full">{{ $lastDate }}</span>
                                    <div class="flex-1 h-px bg-white/5"></div>
                                </div>
                            @endif
                            <div class="flex {{ $msg->sender_id === $currentUser->id ? 'justify-end' : 'justify-start' }} fade-in">
                                <div class="max-w-[75%] sm:max-w-[60%]">
                                    <div class="{{ $msg->sender_id === $currentUser->id ? 'msg-out rounded-2xl rounded-br-md' : 'msg-in rounded-2xl rounded-bl-md' }} px-4 py-2.5">
                                        <p class="text-sm leading-relaxed break-words">{{ $msg->content }}</p>
                                    </div>
                                    <p class="text-[10px] text-gray-600 mt-0.5 font-mono flex items-center gap-1 {{ $msg->sender_id === $currentUser->id ? 'justify-end' : '' }}">
                                        <svg class="w-2.5 h-2.5 text-cy-400/30" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                        {{ $msg->created_at->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Input --}}
                <div class="bg-d-800/60 backdrop-blur border-t border-white/5 p-3 sm:p-4 flex-shrink-0">
                    <form id="chatForm" method="POST" action="{{ route('chat.send') }}" class="flex items-end gap-2">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $selectedContact->id }}">
                        <textarea name="content" id="msgInput" rows="1" placeholder="Escribe un mensaje cifrado..." required
                            class="flex-1 bg-d-700 text-sm text-gray-200 placeholder-gray-600 rounded-xl px-4 py-3 border border-white/5 focus:border-cy-400/30 transition resize-none max-h-28"></textarea>
                        <button type="submit" class="bg-gradient-to-r from-cy-400 to-cy-500 text-d-900 rounded-xl px-4 sm:px-5 py-3 font-semibold text-sm hover:opacity-90 transition glow flex items-center gap-1.5 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            <span class="hidden sm:inline">Enviar</span>
                        </button>
                    </form>
                </div>

            @else
                {{-- Empty State --}}
                <div class="flex-1 flex flex-col items-center justify-center text-center p-8">
                    <div class="w-28 h-28 rounded-3xl bg-d-700 grid place-items-center mb-5 relative">
                        <svg class="w-14 h-14 text-cy-400/15" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <div class="absolute -top-1.5 -right-1.5 w-6 h-6 bg-cy-400 rounded-full grid place-items-center">
                            <svg class="w-3.5 h-3.5 text-d-900" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                    <h2 class="text-xl font-bold grad-text mb-2 font-mono">CryptoChat</h2>
                    <p class="text-gray-500 text-sm max-w-xs">Cifrado AES-256-CBC. Selecciona un contacto para chatear.</p>
                </div>
            @endif
        </main>
    </div>
</div>

<div id="overlay" class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden"></div>

@push('scripts')
<script>
const sb=document.getElementById('sidebar'),ov=document.getElementById('overlay');
document.getElementById('menuBtn')?.addEventListener('click',()=>{
    sb.classList.toggle('hidden');sb.classList.toggle('fixed');sb.classList.toggle('inset-y-0');
    sb.classList.toggle('left-0');sb.classList.toggle('z-50');ov.classList.toggle('hidden');
});
ov?.addEventListener('click',()=>{sb.classList.add('hidden');sb.classList.remove('fixed','inset-y-0','left-0','z-50');ov.classList.add('hidden')});

const mb=document.getElementById('msgBox');
if(mb) mb.scrollTop=mb.scrollHeight;

const ta=document.getElementById('msgInput');
if(ta){
    ta.addEventListener('input',function(){this.style.height='auto';this.style.height=Math.min(this.scrollHeight,112)+'px'});
    ta.addEventListener('keydown',function(e){if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();if(this.value.trim())document.getElementById('chatForm').submit()}});
}

document.getElementById('searchBox')?.addEventListener('input',function(){
    const q=this.value.toLowerCase();
    document.querySelectorAll('.c-item').forEach(el=>{el.style.display=el.textContent.toLowerCase().includes(q)?'':'none'});
});

@if($selectedContact)
let cnt={{$messages->count()}};
setInterval(async()=>{try{const r=await fetch('{{route("chat.messages",$selectedContact->id)}}');const d=await r.json();if(d.length>cnt)location.reload()}catch(e){}},3000);
@endif
</script>
@endpush
@endsection
