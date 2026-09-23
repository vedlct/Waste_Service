<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Shared guard for public form endpoints.
 *
 * Oversized bodies are refused outright, and honeypot submissions are accepted silently
 * so automated spam sees success and does not retry. This mirrors the behaviour the
 * Next.js contact route already implements.
 */
class HandlePublicFormSubmission
{
    private const MAX_BODY_BYTES = 20000;

    public function handle(Request $request, Closure $next, ?string $honeypotField = null): Response
    {
        if ((int) $request->header('Content-Length', '0') > self::MAX_BODY_BYTES) {
            return response()->json([
                'message' => 'Your submission is too large to accept.',
            ], 413);
        }

        $field = $honeypotField ?: config('enquiries.submit.honeypot_field', 'company');

        if (filled($request->input($field))) {
            return response()->json(['ok' => true]);
        }

        return $next($request);
    }
}
