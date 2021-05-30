<?php

namespace App\Exceptions;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;
use Tymon\JWTAuth\Exceptions\InvalidClaimException;
use Tymon\JWTAuth\Exceptions\PayloadException;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

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
     * @throws \Throwable
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
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => 'مسار غير صحيح',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => 'العنصر غير موجود',
                'data' => null
            ], Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => 'Method غير صحيحة',
                'data' => null
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }
        if ($exception instanceof TokenExpiredException) {
            return response()->json([
                'error' => 'Token Has Expired',
            ], 401);
        }
        if ($exception instanceof TokenInvalidException) {
            return response()->json([
                'error' => 'Token Invalid',
            ], 401);
        }
        if ($exception instanceof UnauthorizedHttpException) {
            return response()->json([
                'success' => false,
                'message' => null,
                'error' => 'Unauthorized',
                'data' => null
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ($exception instanceof TokenBlacklistedException) {
            return response()->json([
                'error' => 'Token Blacklisted',
            ], 401);
        }
        if ($exception instanceof UserNotDefinedException) {
            return response()->json([
                'error' => 'user Not Defined',
            ], 401);
        }
        if ($exception instanceof InvalidClaimException) {
            return response()->json([
                'error' => 'Invalid Claim',
            ], 401);
        }
        if ($exception instanceof PayloadException) {
            return response()->json([
                'error' => 'Payload Error',
            ], 401);
        }
        if (Response::HTTP_INTERNAL_SERVER_ERROR) {

            // $this->msg = new Resp();
            if (Str::contains($exception->getMessage(), 'php_network_getaddresses')) {
                return response()->json(
                    [
                        'success' => false,
                        'error' => 'لا يوجد انترنت',
                        'data' => null
                    ],
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
            return response()->json(
                [
                    'success' => false,
                    'error' => $exception->getMessage(),
                    'data' => [
                        'error' => 'internal server error',
                        'line' => $exception->getLine(),
                        'trace' => $exception->getTrace(),
                    ]
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }



        return parent::render($request, $exception);
    }
}
