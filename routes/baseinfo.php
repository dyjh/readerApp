<?php
/**
 * 基础信息模块路由
 */

Route::group([], function (\Illuminate\Routing\Router $router) {
    // 学校信息
    $router->get('locations/provinces', 'SchoolsController@provinces');
    $router->get('locations/cities', 'SchoolsController@cities');
    $router->get('locations/districts', 'SchoolsController@districts');
    $router->get('schools', 'SchoolsController@schools');

    // 用户相关
    $router->post('users/sign-up', 'StudentsController@signUp');
    $router->post('users/sms-verification-code', 'StudentsController@bindPhoneCode');
    $router->post('users/password/reset', 'StudentsController@resetPassword');
});

Route::group([
    'middleware' => 'auth:api',
], function (\Illuminate\Routing\Router $router) {

    // 用户相关
    $router->get('users/profile', 'StudentsController@getProfile');
    $router->post('users/profile', 'StudentsController@updateProfile');
    $router->post('user/avatar', 'StudentsController@updateAvatar');
    $router->post('user/qiniu-uptoken', 'StudentsController@qiniuUpload');
    $router->post('users/phone', 'StudentsController@changePhone');

    $router->post('users/sign', 'StudentSignsController@sign');
    $router->get('users/sign', 'StudentSignsController@signLog');

    //
    $router->get('users/beans/history', 'BeanRecordsController@history');
});