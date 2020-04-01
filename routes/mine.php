<?php
/**
 * 我的相关
 */

Route::group([
    'middleware' => 'auth:api',
], function (\Illuminate\Routing\Router $router) {

    // 班级图书借阅排行榜
    $router->get('read-book-ranks', 'BanStatsController@bookReadRankList');
    // 班级图书上传排行榜
    $router->get('upload-book-ranks', 'BanStatsController@bookUploadedRankList');
    // 班级总体状况
    $router->get('ban/briefs', 'BanStatsController@brief');

    // 书豆
    $router->get('bean/products', 'BeanChargeController@productList');
    $router->post('bean/charge', 'BeanChargeController@charge');
});

Route::group([
], function (\Illuminate\Routing\Router $router) {
    $router->any('bean-ali-notify', 'BeanChargeController@alipayNotify')
        ->name('bean-ali-notify');
    $router->any('bean-wx-notify', 'BeanChargeController@wxpayNotify')
        ->name('bean-wx-notify');
});