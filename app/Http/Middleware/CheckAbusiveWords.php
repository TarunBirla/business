<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ProfanityFilter;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CheckAbusiveWords
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check state-mutating HTTP methods: POST, PUT, PATCH
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            // Exclude system and file upload inputs
            $inputs = $request->except([
                '_token', '_method', 'password', 'password_confirmation',
                'file', 'image', 'photo', 'logo', 'thumbnail', 'gallery', 'avatar', 'banner'
            ]);

            $detectedWord = ProfanityFilter::findAbusiveWord($inputs);

            if ($detectedWord) {
                $errorMessage = "Your submission contains inappropriate language (\"{$detectedWord}\"). Please remove abusive words to proceed.";

                if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'error' => $errorMessage,
                        'message' => $errorMessage,
                    ], 422);
                }

                throw ValidationException::withMessages([
                    'abusive_word' => $errorMessage,
                ]);
            }
        }

        return $next($request);
    }
}
