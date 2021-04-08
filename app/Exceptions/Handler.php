<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Arr;

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
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
            $currentURL = url()->current();
            if (strpos($currentURL, 'member.lumbung') !== false) {
                return redirect()->route('areaLogin');
            }
            if (strpos($currentURL, 'finance.lumbung') !== false) {
                return redirect()->route('finance.login');
            }
            return redirect()->route('login');
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // return $request->expectsJson()
        //             ? response()->json(['message' => $exception->getMessage()], 401)
        //             : redirect()->guest(route('login'));

        if ($request->expectsJson()) {
            return response()->json(['message' =>  $exception->getMessage()], 401);
        }

        $guard = Arr::get($exception->guards(), 0);

        switch ($guard) {
            case 'finance':
                $login = 'finance.login';
                break;
                // case 'vendor':
                //     $login = 'vendor.login';
                //     break;

            default:
                $login = 'areaLogin';
                break;
        }

        return redirect()->guest(route($login));
    }
}
