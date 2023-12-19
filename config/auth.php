<?php
return [
    'defaults' => ['guard' => 'users', 'passwords' => 'users',],
    'guards' => [
        'users' => ['driver' => 'session', 'provider' => 'users',],
        'api' => ['driver' => 'jwt', 'provider' => 'users',],
        'token' => ['driver' => 'token', 'provider' => 'tokens',],
    ],
    'providers' => [
        'users' => ['driver' => 'eloquent', 'model' => App\Models\User::class,],
        'tokens' => ['driver' => 'eloquent', 'model' => App\Models\User::class,],
    ],
    'passwords' => [
        'users' => ['provider' => 'users', 'table' => 'password_resets', 'expire' => 60, 'throttle' => 60,],
    ],
    'password_timeout' => 10800,
];
