<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'mahasiswa' => \App\Http\Middleware\MahasiswaMiddleware::class,
            'dosen' => \App\Http\Middleware\DosenMiddleware::class,
            'kaprodi' => \App\Http\Middleware\KaprodiMiddleware::class,
            'kajur' => \App\Http\Middleware\KajurMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
