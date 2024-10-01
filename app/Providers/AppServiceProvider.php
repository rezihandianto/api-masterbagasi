<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($message, $data = null) {
            return response()->json([
                'message' => $message,
                'data' => $data
            ], 200);
        });
        Response::macro('created', function ($message, $data = null) {
            return response()->json([
                'message' => $message,
                'data' => $data
            ], 201);
        });
        Response::macro('unauthorized', function ($message) {
            return response()->json([
                'message' => $message,
            ], 401);
        });
        Response::macro('notFound', function ($message) {
            return response()->json([
                'message' => $message,
            ], 404);
        });
        Response::macro('badRequest', function ($message, $errors = null) {
            return response()->json([
                'message' => $message,
                'errors' => $errors
            ], 400);
        });
        Response::macro('internalServerError', function ($message, $errors = null) {
            return response()->json([
                'message' => $message,
                'errors' => $errors
            ], 500);
        });
    }
}
