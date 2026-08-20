<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $plain = $request->header('X-API-Key') ?? $request->query('api_key');
        if (!$plain) {
            return response()->json([
                'error' => 'missing_api_key',
                'message' => 'API key tidak ditemukan. Kirim via header X-API-Key atau query ?api_key=...',
            ], 401);
        }

        $apiKey = ApiKey::findByPlaintext($plain);
        if (!$apiKey) {
            return response()->json([
                'error' => 'invalid_api_key',
                'message' => 'API key tidak valid atau sudah dinonaktifkan.',
            ], 401);
        }

        $apiKey->increment('usage_count');
        $apiKey->update(['last_used_at' => now()]);

        $request->attributes->set('api_key', $apiKey);
        return $next($request);
    }
}
