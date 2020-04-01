<?php
/**
 * 共享图书路由
 */

Route::group([
    'middleware' => 'auth:api'
], function (\Illuminate\Routing\Router $router) {
    $router->get('books-in-school', 'SharedBooksController@listBySchool');
    $router->get('books-in-ban', 'SharedBooksController@listByBan');
    // 图书详情
    $router->get('books/{id}', 'SharedBooksController@show');
    $router->get('books/{id}/comments', 'SharedBooksController@comments');
    $router->post('books/{sharedBook}/comments', 'SharedBooksController@review');
    $router->get('books/{id}/owners-school', 'SharedBooksController@listOwnersBySchool');
    $router->get('books/{id}/owners-ban', 'SharedBooksController@listOwnersInBan');

    // 上传我的书籍
    $router->post('private-books', 'PrivateBooksController@upload');
    $router->get('private-books', 'PrivateBooksController@list');
    $router->get('private-books/{privateBook}', 'PrivateBooksController@show');
    //
    $router->post('private-books/{privateBook}/putaway', 'PrivateBooksController@putaway');
    $router->post('private-books/{privateBook}/recycle', 'PrivateBooksController@recycle');

    // 借书申请
    $router->post('rent/application', 'StudentsBooksRentsController@rentApply');
    $router->post('rent/application/cancel/{booksRent}', 'StudentsBooksRentsController@rentCancel');
    // 同意借书申请
    $router->post('rent/application/agreement/{booksRent}', 'StudentsBooksRentsController@rentAgree');
    // 拒绝借书申请
    $router->post('rent/application/rejection/{booksRent}', 'StudentsBooksRentsController@rentReject');
    // 借书归还申请
    $router->post('return/application/{booksRent}', 'StudentsBooksRentsController@returnApply');
    // 借书归还确认
    $router->post('return/application/agreement/{booksRent}', 'StudentsBooksRentsController@returnAgree');

    // 图书借入
    $router->get('rent/books', 'StudentsBooksRentsController@rents');
    $router->get('rent/books/{booksRent}', 'StudentsBooksRentsController@rentDetail');

    // 图书借出
    $router->get('lend/books', 'StudentsBooksRentsController@rents');
    $router->get('lend/books/{booksRent}', 'StudentsBooksRentsController@lendDetail');
});