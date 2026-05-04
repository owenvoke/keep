<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Log\Context\Repository as ContextRepository;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Str;

readonly class ContextMiddleware
{
    public function __construct(
        private ContextRepository $context,
    ) {}

    public function handle(Request $request, Closure $next): mixed
    {
        $this->context->add([
            'request.id' => Str::uuid7()->toString(),
            'request.path' => $request->path(),
            'request.method' => $request->method(),
        ]);

        // Add user context if authenticated
        if ($request->user()) {
            $this->context->add([
                'user.id' => $request->user()->id,
            ]);
        }

        return $next($request);
    }
}
