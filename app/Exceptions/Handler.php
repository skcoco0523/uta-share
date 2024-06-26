<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Session\TokenMismatchException; // 追加

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
        // 419エラー（TokenMismatchException）のハンドリング
        //dd($exception);
        if ($exception instanceof TokenMismatchException) {
            $message = ['message' => 'セッションの有効期限が切れました。もう一度ログインしてください。','type' => '419','sec' => '2000'];
            return redirect()->route('login')->with($message);
        };
        return parent::render($request, $exception);
    }
}
