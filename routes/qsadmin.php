<?php
use Illuminate\Routing\Router;

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => 'Qs\\Admin\\Controllers',
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('auth/sysconf', 'SysconfController@index');

});