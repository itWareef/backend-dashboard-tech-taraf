<?php

use App\Exceptions\CustomValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )->withSchedule(function (Schedule $schedule) {
        $schedule->command(\App\Console\Commands\OfferApply::class)->daily();
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(        \Illuminate\Session\Middleware\StartSession::class,
        );
        $middleware->alias([
            'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (ValidationException $exception) {
            throw CustomValidationException::withMessages(
                $exception->validator->getMessageBag()->getMessages()
            );
        });
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'errors' => ['Record not found.']
                ], 404);
            }
        });
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            return response()->json([
                'status' => 'error',
                'errors' => ['Not Authorized.']
            ],403);
        });
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException$e, Request $request) {
            return response()->json([
                'status' => 'error',
                'errors' => ['Not Authenticated.']
            ],403);
        });
    })->create();
