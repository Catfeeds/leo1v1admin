<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [
        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => database_path('database.sqlite'),
            'prefix'   => '',
        ],
        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            // 'host'      => env('DB_HOST', '127.0.0.1'),
            'database'  => env('DB_DATABASE', 'db_weiyi_admin'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', '123456'),
            'charset'   => 'latin1',
            'collation' => 'latin1_bin',
            'prefix'    => '',
            'strict'    => false,
            'engine'    => null,
        ],
        'mysql_readonly' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST_READONLY', 'localhost'),
            'database'  => env('DB_DATABASE', 'db_weiyi_admin'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', '123456'),
            'charset'   => 'latin1',
            'collation' => 'latin1_bin',
            'prefix'    => '',
            'strict'    => false,
            'engine'    => null,
        ],
        'mysql_tongji' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST_TONGJI', 'localhost'),
            'database'  => env('DB_DATABASE', 'db_weiyi_admin'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', '123456'),
            'charset'   => 'latin1',
            'collation' => 'latin1_bin',
            'prefix'    => '',
            'strict'    => false,
            'engine'    => null,
        ],
        'mysql_question' => [
            'driver'    => 'mysql',
            'host'      => env('DB_QUESTION_HOST', '192.168.0.5'),
            'database'  => env('DB_QUESTION_DATABASE', 'db_question'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', '123456'),
            'charset'   => 'latin1',
            'collation' => 'latin1_bin',
            'prefix'    => '',
            'strict'    => false,
            'engine'    => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [
        'cluster' => false,
        'default' => [
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port'     => env('REDIS_PORT', 6379),
            'database' => 0,
        ],
        'cache_nick' => [
            'host'     => env('CACHE_REDIS_HOST', '127.0.0.1'),
            'password' => env('CACHE_REDIS_PASSWORD', null),
            'port'     => env('CACHE_REDIS_PORT', 6379),
            'database' => 0,
        ],
        'api' => [
            'host'     => env('API_REDIS_HOST', '127.0.0.1'),
            'password' => env('API_REDIS_PASSWORD', null),
            'port'     => env('API_REDIS_PORT', 6379),
            'database' => 0,
        ],
        'session' => [
            'host'     => env('SESSION_REDIS_HOST', '127.0.0.1'),
            'password' => env('SESSION_REDIS_PASSWORD', null),
            'port'     => env('SESSION_REDIS_PORT', 6379),
            'database' => 8,
        ],
    ],
];
