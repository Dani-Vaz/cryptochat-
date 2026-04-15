@extends('layouts.chat')

@section('content')
<div class="flex flex-col h-screen">

    <header class="glass px-4 py-3 flex items-center justify-between flex-shrink-0 border-b border-white/5">
        <div class="flex items-center gap-3">
            <a href="{{ route('chat.index') }}" class="text-gray-400 hover:text-cy-400 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cy-400 to-cy-500 grid place-items-center">
                    <svg class="w-[18px] h-[18px] text-d-900" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <span class="font-mono text-[15px] font-bold"><span class="grad-text">Crypto</span><span class="text-gray-300">Chat</span></span>
            </div>
            <span class="text-[11px] text-gray-500 font-mono bg-d-700 px-2 py-0.5 rounded-full">Mi Perfil</span>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto flex items-start justify-center p-6">
        <div class="w-full max-w-lg">

            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl mb-6 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="glass rounded-2xl p-6">
                <div class="flex items-center gap-4 mb-8">
                    <div class="relative">
                        @if($currentUser->avatar && str_starts_with($currentUser->avatar, 'data:'))
                            <img src="{{ $currentUser->avatar }}" class="w-20 h-20 rounded-full object-cover border-2 border-cy-400/30">
                        @else
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-violet-500 to-fuchsia-500 grid place-items-center text-white text-2xl font-bold">
                                {{ strtoupper(substr($currentUser->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-200">{{ $currentUser->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $currentUser->email }}</p>
                        @if($currentUser->bio)
                            <p class="text-xs text-gray-400 mt-1">{{ $currentUser->bio }}</p>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('user-profile.update') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5 font-mono uppercase tracking-wider">Nombre</label>
                        <input type="text" name="name" value="{{ $currentUser->name }}" required
                            class="w-full bg-d-700 text-sm text-gray-200 rounded-xl px-4 py-3 border border-white/5 focus:border-cy-400/30 transition">
                        @error('name') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5 font-mono uppercase tracking-wider">Bio</label>
                        <textarea name="bio" rows="3" placeholder="Escribe algo sobre ti..."
                            class="w-full bg-d-700 text-sm text-gray-200 rounded-xl px-4 py-3 border border-white/5 focus:border-cy-400/30 transition resize-none">{{ $currentUser->bio }}</textarea>
                        @error('bio') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1.5 font-mono uppercase tracking-wider">Foto de perfil</label>
                        <input type="file" name="avatar" accept="image/*" id="avatarInput"
                            class="w-full bg-d-700 text-sm text-gray-400 rounded-xl px-4 py-3 border border-white/5 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-cy-400/10 file:text-cy-400 file:text-xs file:font-mono">
                        <div id="previewContainer" class="mt-3 hidden">
                            <img id="previewImg" class="w-20 h-20 rounded-full object-cover border-2 border-cy-400/30">
                        </div>
                        @error('avatar') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-cy-400 to-cy-500 text-d-900 rounded-xl px-4 py-3 font-semibold text-sm hover:opacity-90 transition glow">
                        Guardar cambios
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('avatarInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('previewContainer').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection
