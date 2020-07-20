<?php

/**
 * Created by PhpStorm.
 * User: jialinzhang
 * Date: 2018/9/9
 * Time: 00:09
 */
return [
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID', ''),
        'client_secret' => env('GITHUB_CLIENT_SECRET', ''),
        'redirect'      => env('GITHUB_REDIRECT_URL', ''),
    ],
    'qq' => [
        'client_id' => env('QQ_CLIENT_ID', ''),
        'client_secret' => env('QQ_CLIENT_SECRET', ''),
        'redirect'      => env('QQ_REDIRECT_URL', ''),
    ],
    'weibo' => [
        'client_id' => env('WEIBO_CLIENT_ID', ''),
        'client_secret' => env('WEIBO_CLIENT_SECRET', ''),
        'redirect'      => env('WEIBO_REDIRECT_URL', ''),
    ]
];
