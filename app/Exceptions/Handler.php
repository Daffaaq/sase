<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            $statusCode = $exception->getStatusCode();

            if (Auth::check()) {
                Log::info('User is logged in');
                if ($statusCode == 404) {
                    return response()->view('Error-Template.404-login', [], 404);
                }
                if ($statusCode == 500) {
                    return response()->view('Error-Template.500', [], 500);
                }
            } else {
                Log::info('User is not logged in');
                if ($statusCode == 404) {
                    return response()->view('Error-Template.404', [], 404);
                }
                if ($statusCode == 500) {
                    return response()->view('Error-Template.500', [], 500);
                }
            }
        }

        return parent::render($request, $exception);
    }
}
