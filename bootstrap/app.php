<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'v1',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'api.key' => \App\Http\Middleware\VerifyApiKey::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $handleTokenExpired = function ($e, Request $request) {
            $is419 = ($e instanceof TokenMismatchException)
                || ($e instanceof HttpException && $e->getStatusCode() === 419)
                || ($e->getPrevious() instanceof TokenMismatchException);

            if (! $is419) {
                return null;
            }

            $message = 'Sesi Anda telah berakhir karena tidak ada aktivitas. Silakan masuk kembali.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 419);
            }

            if ($request->hasSession()) {
                if (Auth::check()) {
                    Auth::logout();
                }

                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            return redirect()->route('login')->with('warning', $message);
        };

        $exceptions->render($handleTokenExpired);
    })->create();
