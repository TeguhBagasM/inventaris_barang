<?php

use App\Http\Middleware\AdminOrOperator;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsMember;
use App\Http\Middleware\IsOperator;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'operator' => IsOperator::class,
            'admin' => IsAdmin::class,
            'member' => IsMember::class,
            'admin_or_operator' => AdminOrOperator::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
