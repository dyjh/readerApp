<?php
/**
 * 杂项
 */

Route::group([
], function (\Illuminate\Routing\Router $router) {

    // 班级图书借阅排行榜
    $router->get('bulletins', 'PlatformController@bulletins');
    $router->get('banners', 'PlatformController@banners');
    $router->get('banners/{banner}', 'PlatformController@bannerDetail');
    $router->get('about', 'PlatformController@about');
    $router->get('rent-rules', 'PlatformController@rentRule');
    $router->get('bean-usage', 'PlatformController@beanUsage');
    $router->get('protocol', 'PlatformController@protocol');


});

Route::group([
    'middleware' => 'auth:api'
], function (\Illuminate\Routing\Router $router) {

    // 互动白板
    $router->post('white-boards', 'WhiteBoardController@createRoom');
});