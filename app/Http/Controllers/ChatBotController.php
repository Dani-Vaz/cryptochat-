<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ChatBotController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $botMessages = session()->get('bot_messages', []);
        return view('chat.bot', compact('currentUser', 'botMessages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:10000',
        ]);

        $userMessage = $request->content;
        $botMessages = session()->get('bot_messages', []);

        $botMessages[] = [
            'role' => 'user',
            'content' => $userMessage,
            'time' => now()->format('H:i'),
        ];

        $apiMessages = [];
        foreach ($botMessages as $msg) {
            if ($msg['role'] === 'user' || $msg['role'] === 'assistant') {
                $apiMessages[] = ['role' => $msg['role'], 'content' => $msg['content']];
            }
        }

        $botReply = $this->callOpenRouterAPI($apiMessages);

        $botMessages[] = [
            'role' => 'assistant',
            'content' => $botReply,
            'time' => now()->format('H:i'),
        ];

        session()->put('bot_messages', $botMessages);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'reply' => $botReply,
                'time' => now()->format('H:i'),
            ]);
        }

        return redirect()->route('chatbot.index');
    }

    public function clear()
    {
        session()->forget('bot_messages');
        return redirect()->route('chatbot.index');
    }

    private function callOpenRouterAPI(array $messages): string
    {
        $apiKey = config('services.openrouter.api_key');

        if (empty($apiKey)) {
            return "Falta configurar la API Key de OpenRouter.\n\n"
                 . "1. Ve a: https://openrouter.ai/keys\n"
                 . "2. Crea una API key\n"
                 . "3. Agrega en tu .env: OPENROUTER_API_KEY=tu-key\n"
                 . "4. Reinicia con php artisan serve";
        }

        // Modelos gratuitos a intentar en orden
        $models = [
            'openrouter/free',
            'meta-llama/llama-3.3-70b-instruct:free',
            'mistralai/mistral-small-3.1-24b-instruct:free',
            'qwen/qwen3-235b-a22b:free',
        ];

        foreach ($models as $model) {
            $result = $this->tryModel($apiKey, $model, $messages);
            if ($result !== null) {
                return $result;
            }
        }

        return "No se pudo conectar. Todos los modelos gratuitos estan ocupados.\n"
             . "Intenta de nuevo en unos segundos.";
    }

    private function tryModel(string $apiKey, string $model, array $messages): ?string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$apiKey}",
                'Content-Type' => 'application/json',
                'HTTP-Referer' => 'http://localhost:8000',
                'X-Title' => 'CryptoChat',
            ])->timeout(45)->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => $model,
                'messages' => array_merge([
                    [
                        'role' => 'system',
                        'content' => 'Eres CryptoBot, el asistente de IA integrado en CryptoChat, una app de chat cifrado con AES-256-CBC hecha en Laravel 11. Ayudas con programacion, preguntas tecnicas, codigo, preguntas frecuentes y cualquier tema. Respondes siempre en espanol. Cuando te envien codigo, lo analizas y ayudas a mejorarlo. Eres amigable, claro y directo. Si te preguntan quien eres, dices que eres CryptoBot, el asistente IA de CryptoChat.'
                    ]
                ], $messages),
                'max_tokens' => 2048,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['choices'][0]['message']['content'] ?? null;
                if ($text) {
                    return $text;
                }
            }

            // Si es 404 o 429, intentar siguiente modelo
            if (in_array($response->status(), [404, 429, 503])) {
                return null;
            }

            $error = $response->json();
            return "Error ({$response->status()}): " . ($error['error']['message'] ?? 'Error desconocido');

        } catch (\Exception $e) {
            return null;
        }
    }
}
