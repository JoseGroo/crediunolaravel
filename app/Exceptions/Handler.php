<?php

namespace App\Exceptions;

use App\Helpers\HelperCrediuno;
use App\tbl_log_errors;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $datetime_now = HelperCrediuno::get_datetime();
        $log = new tbl_log_errors();
        $log->message = $exception->getMessage();
        $log->code = $exception->getCode();
        $log->file = $exception->getFile();
        $log->line = $exception->getLine();
        $log->creado_por = 0;//$request->auth()->user()->id;
        $log->fecha_creacion = $datetime_now;

        $response = tbl_log_errors::create($log);

        // Print log id in logs
        logger("LogId {$log->log_error_id}");

        //Return view error with log id
        /*return response()->view('errors', ['logId' => $log->log_error_id]);*/
        return response()->view('general.errors', ['log' => $log]);
        //return parent::render($request, $exception);
    }
}
