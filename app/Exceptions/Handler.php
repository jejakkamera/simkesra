<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Models\ErrorLog;

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

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    // public function render($request, Throwable $exception)
    // {
    //     // Tangani hanya error 500

    //     // if ($exception->getStatusCode() == 500) {
    //     //     // Simpan error ke database
    //     //     ErrorLog::create([
    //     //         'message' => $exception->getMessage(),
    //     //         'trace' => $exception->getTraceAsString(),
    //     //         'url' => $request->fullUrl(),
    //     //         'method' => $request->method(),
    //     //         'ip_address' => $request->ip(),
    //     //     ]);

    //     // Redirect ke halaman kustom error 500
    //     $pageConfigs = ['myLayout' => 'blank'];
    //     return view('content.pages.error500', ['pageConfigs' => $pageConfigs]);
    //     // return view('content.pages.error500');
    //     // }

    //     // return parent::render($request, $exception);
    // }
}
