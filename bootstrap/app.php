<?php

use App\Http\Middleware\CanAccessBarang;
use App\Http\Middleware\CanAccessBhp;
use App\Http\Middleware\CanAccessLogPeminjaman;
use App\Http\Middleware\CanAccessPermintaan;
use App\Http\Middleware\CanAccessPinjam;
use App\Http\Middleware\CanAccessTodolist;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\isGuru;
use App\Http\Middleware\isPetugas1;
use App\Http\Middleware\isPetugas2;
use App\Http\Middleware\isPetugas3;
use App\Http\Middleware\isSiswa;
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
            'admin' => IsAdmin::class,
            'can.access.barang' => CanAccessBarang::class,
            'can.access.bhp' => CanAccessBhp::class,
            'can.access.log' => CanAccessLogPeminjaman::class,
            'can.access.todolist' => CanAccessTodolist::class,
            'can.access.pinjam' => CanAccessPinjam::class,
            'can.access.permintaan' => CanAccessPermintaan::class,
            'guru' => isGuru::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
