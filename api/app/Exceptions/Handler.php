<?php

namespace App\Exceptions;

use App\Constants\ErrorCode;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;

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
        $this->reportable(function (\Exception $e) {
            //
        });
    }


    public function render($request, Throwable $e)
    {
        if (!$request->is('api/*')) {
            parent::report($e);
        }

//        if (env('APP_DEBUG')) {
//            print_r($e->getTraceAsString());
//            die();
//        }

        $message = $e->getMessage();
        if (empty($message)) {
            $message = data_get(ErrorCode::CODE_MESSAGE, $e->getCode(), '');
        }
        if ($e instanceof HttpResponseException) {
            $code = Response::HTTP_BAD_REQUEST;
        } elseif (($e instanceof AuthenticationException) || ($e instanceof UnauthorizedHttpException)) {
            $code = Response::HTTP_UNAUTHORIZED;
        } elseif ($e instanceof ValidationException) {
            $code = Response::HTTP_BAD_REQUEST;
        } elseif ($e instanceof NotFoundHttpException) {
            $code = Response::HTTP_NOT_FOUND;
        } elseif ($e instanceof MethodNotAllowedHttpException) {
            $code = Response::HTTP_NOT_FOUND;
        } elseif ($e instanceof ApiException) {
            Log::error("Api Exception", [
                'error_code'    => $e->getCode(),
                'error_message' => $message,
                'request_url'   => $request->url(),
                'data'          => $request->all(),
                'headers'       => [
                    'role_id'     => $request->header('X-Request-Role', 0),
                    'resource_id' => $request->header('X-Request-Resource', 0),
                    'journal_id'  => $request->header('X-Request-Journal', 0),
                ],
                'error_file'    => $e->getFile(),
                'error_line'    => $e->getLine(),
            ]);
            $code = $e->getCode();
        } elseif ($e instanceof AuthorizationException) {
            $code = Response::HTTP_FORBIDDEN;
        } else {
            Log::error("Server Exception", [
                'error_code'    => $e->getCode(),
                'error_message' => $e->getMessage(),
                'request_url'   => $request->url(),
                'data'          => $request->all(),
                'error_file'    => $e->getFile(),
                'error_line'    => $e->getLine(),
            ]);
            $code    = ErrorCode::SERVER_ERROR;
            $message = "Service response failed, please try again later.";
        }

        return $this->error($code, $message);
    }
}
