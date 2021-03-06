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
        '/admin/neditor/serve/*',
        '/admin/ueditor/serve',
        '/renav',
        '/resale',
        '/restrategy',
        '/repurchase',
        '/replan',
        '/refee',
        '/savefee',
        '/savesale',
        '/savestrategy',
        '/saveplan',
        '/savepurchase',
        '/admin/users/createnav',
    ];
}
