<?php

namespace App\Exceptions;

use App\Exceptions\api\AccessDeniedException;
use App\Exceptions\api\shares\RentBookStateException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // 客户数据验证异常
        if ($exception instanceof ValidationException) {
            return response()->json([
                'status' => -1,
                'message' => $this->formatErrors($exception->errors())
            ], 200);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'status' => -1,
                'errors' => [
                    'url' => 'method not allowed.'
                ]
            ], 405);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'status' => 401,
                'message' => '用户身份认证失败或TOKEN令牌失效'
            ], 200);
        }

        if ($exception instanceof ValidatorException) {
            return response()->json([
                'status' => -1,
                'message' => $exception->getMessageBag()->first()
            ], 200);
        }

        if ($exception instanceof HttpException) {
            if ($exception->getStatusCode() == 429) {
                return response()->json([
                    'status' => -1,
                    'message' => '请求次数超过限制'
                ], 200);
            }
        }

        if ($exception instanceof AccessDeniedException) {
            return response()->json([
                'status' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
        }

        // @TODO:: 替换成抽象异常
        if ($exception instanceof RentBookStateException) {
            return response()->json([
                'status' => $exception->getCode(),
                'message' => $exception->getMessage()
            ]);
        }

//        if ($exception instanceof QueryException) {
//            if (env('APP_DEBUG')) {
//                return response()->json([
//                    'status' => -1,
//                    'message' => [
//                        'hint' => $exception->getMessage(),
//                        'sql' => $exception->getSql()
//                    ]
//                ], 500);
//            } else {
//                return response()->json([
//                    'status' => -1,
//                    'message' => '服务出错!'
//                ], 500);
//            }
//        }

        return parent::render($request, $exception);
    }

    private function formatErrors(array $errors)
    {
        return array_first(array_first(array_values($errors)));
    }
}
