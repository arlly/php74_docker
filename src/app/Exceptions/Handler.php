<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Request;
use Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

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
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param AuthenticationException $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        if (in_array('admin', $exception->guards())) {
            return redirect()->guest(route('admin.login'));
        }

        return redirect()->guest(route('login'));
    }

    /**
     * @param HttpExceptionInterface $e
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        $status = $e->getStatusCode();
        return response()->view("errors.exception",
            [
                'exception' => $e,
                'title' => $this->getTitle($status),
                'message' => $this->getMessage($status),
            ],
            $status
        );
    }

    /**
     * @param $status
     * @return string
     */
    private function getTitle($status)
    {
        switch ($status) {
            case 400:
                $title = '400 Bad Request.';
                break;

            case 401:
                $title = '401 Unauthorized.';
                break;

            case 403:
                $title = '403 Forbidden.';
                break;

            case 404:
                $title = '404 Not Found.';
                break;

            case 405:
                $title = '405 Method Not Allowed.';
                break;

            case 408:
                $title = '408 Request Timeout.';
                break;

            case 414:
                $title = '414 URI Too Long.';
                break;

            case 500:
                $title = '500 Internal Server Error.';
                break;

            case 503:
                $title = '503 Service Unavailable.';
                break;

            default:
                $title = 'Any Errors.';
        }

        return $title;
    }

    /**
     * エラーViewのメッセージを取得する
     *
     * @param string $status
     * @return string $message
     */
    private function getMessage($status)
    {
        switch ($status) {
            case 400:
                $message = "リクエストが不正です。";
                break;

            case 401:
                $message = "認証に失敗しました。";
                break;

            case 403:
                $message = "アクセス権がありません";
                break;

            case 404:
                $message = "存在しないページです。";
                break;

            case 405:
                $message = "許可されていないメソッドです。";
                break;

            case 408:
                $message = "セッションタイムアウトです。";
                break;

            case 414:
                $message = "リクエストURIが長すぎます。";
                break;

            case 500:
                $message = "サーバ内部エラーです。";
                break;

            case 503:
                $message = "申し訳ございませんが、ただ今メンテナンス中です。";
                break;

            default:
                $message = "予期せぬエラーです。";
        }

        return $message;
    }
}
