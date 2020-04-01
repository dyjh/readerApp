<?php

use Illuminate\Routing\Router;
/**
 * Store
 */

Route::group([
    'middleware' => 'web.auth'
], function (Router $router) {

    /**
     * ProductBook
     */
    $router->get('product/books', 'ProductBookController@index');
    $router->get('product/book/{book}', 'ProductBookController@show');

    /**
     * ProductCategory
     */
    $router->get('product/categories', 'ProductBookController@categories');

    /**
     * ProductComment
     */
    $router->get('product/comments/{book}', 'CommentController@index');

    /**
     * Pay
     */
    $router->post('order/ali_notify_url','PayController@ali_notify_url')->name('order-ali-notify');
    $router->post('order/ali_return_url','PayController@ali_return_url')->name('order-ali-return');
    $router->post('order/wx_notify_url','PayController@wx_notify_url')->name('order-wx-notify');
    $router->post('order/wx_return_url','PayController@wx_return_url')->name('order-wx-return');

});

Route::group([
    'middleware' => 'auth:api'
], function (Router $router) {

    /**
     * ProductComment
     */
    $router->post('product/comment/{orderItem}', 'CommentController@store');

    /**
     * Cart
     */
    $router->get('carts', 'CartController@index');
    $router->post('cart', 'CartController@store');
    $router->post('cart/update/{cart}', 'CartController@update');
    $router->post('cart/delete', 'CartController@destroy');

    /**
     * Order
     */
    $router->post('order','OrderController@store');
    $router->post('order/confirmOrder/{order}','OrderController@confirmOrder');
    $router->post('order/cancelOrder/{order}','OrderController@cancelOrder');
    $router->post('order/refundOrder/{order}','OrderController@refundOrder');
    $router->post('order/remindOrder/{order}','OrderController@remindOrder');
    $router->get('orders','OrderController@index');
    $router->get('order/{order}','OrderController@show');
    $router->get('order/refundOrders/list','OrderController@refundOrders');
    $router->get('order/refundOrderDetail/{item}','OrderController@refundOrderDetail');
    $router->get('order/refundReasons/list','OrderController@refundReasons');

    /**
     * Pay
     */
    $router->post('order/pay/{order}','PayController@pay');

});
