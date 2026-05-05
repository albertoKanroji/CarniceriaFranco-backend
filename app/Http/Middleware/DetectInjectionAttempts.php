<?php
// app/Http/Middleware/DetectInjectionAttempts.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DetectInjectionAttempts
{
    /**
     * Patrones sospechosos de inyección
     */
    protected $suspiciousPatterns = [
        // SQL Injection
        'union.*select',
        'insert.*into',
        'delete.*from',
        'drop.*table',
        'update.*set',
        'exec.*xp_',
        ';\s*drop',

        // Command Injection
        ';\s*(ls|cat|wget|curl|nc|bash|sh)',
        '\|\s*(ls|cat|wget)',
        '`.*`',
        '\$\(.*\)',

        // LDAP Injection
        '\(\|\(',
        '\)\(.*\|',

        // XPath Injection
        'or\s+1=1',
        'and\s+1=1',

        // NoSQL Injection
        '\{\s*\$ne\s*:',
        '\{\s*\$gt\s*:',
        '\{\s*\$regex\s*:',
    ];

    public function handle(Request $request, Closure $next)
    {
        $input = json_encode($request->all());

        foreach ($this->suspiciousPatterns as $pattern) {
            if (preg_match('/' . $pattern . '/i', $input)) {
                Log::channel('security')->warning('Intento de inyección detectado', [
                    'ip' => $request->ip(),
                    'user_id' => auth()->id(),
                    'url' => $request->fullUrl(),
                    'input' => $request->all(),
                    'pattern_matched' => $pattern,
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now(),
                ]);

                // Opcionalmente bloquear la petición
                abort(403, 'Actividad sospechosa detectada');

                break;
            }
        }

        return $next($request);
    }
}
