<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CryptoChat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600;9..40,700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: { extend: {
            fontFamily: { sans:['DM Sans','sans-serif'], mono:['Space Mono','monospace'] },
            colors: {
                d: { 900:'#060a10', 800:'#0b1018', 700:'#10171f', 600:'#172029', 500:'#1c2838' },
                cy: { 400:'#22d3ee', 500:'#06b6d4', 600:'#0891b2' }
            }
        }}
    }
    </script>
    <style>
        *{scrollbar-width:thin;scrollbar-color:#1c2838 #0b1018}
        ::-webkit-scrollbar{width:5px}
        ::-webkit-scrollbar-track{background:#0b1018}
        ::-webkit-scrollbar-thumb{background:#1c2838;border-radius:4px}
        ::selection{background:#22d3ee;color:#060a10}
        .glass{background:rgba(11,16,24,.85);backdrop-filter:blur(16px);border:1px solid rgba(34,211,238,.06)}
        .msg-out{background:linear-gradient(135deg,#22d3ee,#06b6d4);color:#060a10}
        .msg-in{background:#172029;color:#e2e8f0;border:1px solid rgba(34,211,238,.06)}
        .c-active{background:linear-gradient(135deg,rgba(34,211,238,.08),rgba(6,182,212,.05));border-left:3px solid #22d3ee}
        .c-item:hover{background:rgba(28,40,56,.6)}
        .glow{box-shadow:0 0 20px rgba(34,211,238,.18)}
        .grad-text{background:linear-gradient(135deg,#22d3ee,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .fade-in{animation:fi .25s ease-out}
        @keyframes fi{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
        .pulse{animation:pu 2s infinite}
        @keyframes pu{0%,100%{opacity:1}50%{opacity:.4}}
        input:focus,textarea:focus{outline:none;box-shadow:0 0 0 2px rgba(34,211,238,.25)}
    </style>
</head>
<body class="bg-d-900 text-gray-200 h-screen overflow-hidden font-sans">
    <div class="fixed inset-0 pointer-events-none">
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-cy-400/[.03] rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-cy-500/[.02] rounded-full blur-3xl"></div>
    </div>
    <div class="relative z-10 h-screen flex flex-col">
        @yield('content')
    </div>
    @stack('scripts')
</body>
</html>
