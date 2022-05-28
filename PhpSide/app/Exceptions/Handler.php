<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param Throwable $exception
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        $data = json_decode(file_get_contents(storage_path('configs.json')), true);
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', [
                'home' => $data['address'],
                'nodePort' => $data['nodePort'],
                'phpPort' => $data['phpPort']
            ], 404);
        }
        return response()->view('errors.500', [
            'home' => $data['address'],
            'nodePort' => $data['nodePort'],
            'phpPort' => $data['phpPort']
        ], 500);
    }
}
