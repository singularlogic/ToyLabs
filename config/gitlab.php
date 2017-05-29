<?php

/*
 * This file is part of Laravel GitLab.
 *
 * (c) Vincent Klaiber <hello@vinkla.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
     */

    'default'     => env('APP_ENV'),

    /*
    |--------------------------------------------------------------------------
    | GitLab Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
     */

    'connections' => [

        'production' => [
            'token'    => env('GITLAB_TOKEN'),
            'base_url' => 'http://git.yourdomain.com/api/v3/',
        ],

        'stagging'   => [
            'token'    => env('GITLAB_TOKEN'),
            'base_url' => 'http://git.yourdomain.com/api/v3/',
        ],

        'local'      => [
            'token'    => env('GITLAB_TOKEN'),
            'base_url' => 'http://localhost:8888/api/v4/',
        ],
    ],

];
