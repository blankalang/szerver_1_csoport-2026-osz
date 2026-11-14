<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

// A fájl elsősorban az alkalmazás bootstrap-konfigurációját, útvonalait, middleware-jeit és kivételkezelését állítja be.
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        // Az apiPrefix beállítás határozza meg, hogy az API útvonalak milyen prefix-szel kezdődjenek. Például, ha az apiPrefix 'api', akkor az összes API végpont a /api/ útvonal alatt lesz elérhető (pl. /api/posts). Ha üres stringre állítjuk, akkor az API végpontok közvetlenül a gyökérútvonalon lesznek elérhetők (pl. /posts).
        apiPrefix: 'api',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn(Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
