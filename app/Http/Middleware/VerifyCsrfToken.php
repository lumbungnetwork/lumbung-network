<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/zMbH9dshaPZqdGIJtgvQNfsj38MfPRizcDuNeGu5xyvOWJaswzhkhFJaoeHddWaW/webhook',
        '/hok0zyfi75ale6zm080wovqo9gdy559y5lpwfxagca8hepu1tovfkf8u8dl9ypnlk2zznkjzdxtl3bgt/webhook',
    ];
}
