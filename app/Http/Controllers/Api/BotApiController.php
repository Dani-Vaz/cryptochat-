<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BotApiController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:10000',
            'history' => 'array',
        ]);

        $messages = [];
        if ($request->has('history')) {
            foreach ($request->history as $msg) {
                $messages[] = ['role' => $msg['role'], 'content' => $msg['content']];
            }
        }
        $messages[] = ['role' => 'user', 'content' => $request->content];

        $reply = $this->callOpenRouter($messages);

        return response()->json([
            'reply' => $reply,
            'time' => now()->format('H:i'),
        ]);
    }

    private function callOpenRouter(array $messages): string
    {
        $apiKey = config('services.openrouter.api_key');

        if (empty($apiKey)) {
            return 'API key no configurada.';
        }

        $models = [
            'openrouter/free',
            'meta-llama/llama-3.3-70b-instruct:free',
            'mistralai/mistral-small-3.1-24b-instruct:free',
            'qwen/qwen3-235b-a22b:free',
        ];

        foreach ($models as $model) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$apiKey}",
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => config('app.url', 'http://localhost'),
                    'X-Title' => 'CryptoChat',
                ])->timeout(45)->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => $model,
                    'messages' => array_merge([
                        ['role' => 'system', 'content' => 'Eres CryptoBot, asistente IA de CryptoChat. Respondes en espanol, ayudas con codigo y preguntas tecnicas. Eres amigable y directo.']
                    ], $messages),
                    'max_tokens' => 2048,
                    'temperature' => 0.7,
                ]);

                if ($response->successful()) {
                    $text = $response->json()['choices'][0]['message']['content'] ?? null;
                    if ($text) return $text;
                }
                if (in_array($response->status(), [404, 429, 503])) continue;
                return "Error ({$response->status()})";
            } catch (\Exception $e) {
                continue;
            }
        }
        return 'Modelos no disponibles. Intenta en unos segundos.';
    }
}
