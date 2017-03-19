<?php

/**
 * Api
 */
$api = app('Dingo\Api\Routing\Router');

app('Dingo\Api\Exception\Handler')->register(function (Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
    \Log::error(['errcode' => 404, 'errmsg' => request()->url()]);
});

$api->version('v1', function ($api) {
    $api->get('/a/index', 'IndexController@index');
    $api->get('/order/index', 'OrderController@index');
    $api->post('/users/login', 'UsersController@login');
    $api->post('/users/regedit', 'UsersController@regedit');
    $api->get('/users/get_token', 'UsersController@get_token');
    $api->get('/users/test', 'UsersController@test');
    //忘记密码发送邮件
    $api->post('/users/password/email', 'UsersController@sendResetLinkEmail');

//    $api->group(['middleware' => 'jwt.auth'], function ($api) {
        $api->group(['prefix' => 'users'], function ($api) {
            $api->post('/logout', 'UsersController@logout');
            $api->get('/me', 'UsersController@me');
            $api->get('/czfb_lists', 'UsersController@czfb_lists');
            $api->get('/ckyd_lists', 'UsersController@ckyd_lists');
            //重置密码
            $api->post('password/reset', 'UsersController@reset');
        });

        $api->post('/order/save', 'OrderController@save');
        $api->get('/order/orderList', 'OrderController@orderList');
        $api->get('/order/getInfoByid', 'OrderController@getInfoByid');

        $api->post('/orderPassanger/reserve', 'OrderPassengerController@reserve');
        $api->get('/orderPassanger/getUsersByOrderId', 'OrderPassengerController@getUsersByOrderId');
//    });

});