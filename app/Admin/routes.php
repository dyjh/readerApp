<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');

    // 基础信息
    $router->resource('bans', baseinfo\BansController::class);
    $router->resource('grades', baseinfo\GradesController::class);
    $router->resource('semesters', baseinfo\SemestersController::class);
    $router->resource('students', baseinfo\StudentsController::class);
    $router->resource('teachers', baseinfo\TeachersController::class);
    $router->resource('schools', baseinfo\SchoolsController::class);

    // 共享图书
    $router->resource('shared-books', shares\SharedBooksController::class);
    $router->resource('borrow-comments', shares\BorrowCommentsController::class);
    $router->resource('students-books-rents', shares\StudentsBooksRentsController::class);
    $router->resource('borrow-blacklist', shares\BlacklistController::class);
    $router->post('blacklist/remove', 'shares\BlacklistController@remove');

    // 商城
    $router->resource('product-books', stores\ProductsController::class);
    $router->resource('product-categories', stores\CategoryController::class);
    $router->resource('orders', stores\OrdersController::class);
    $router->resource('refund-reasons', stores\RefundReasonController::class);
    $router->resource('product-book-comments', stores\ProductCommentsController::class);

    $router->post('checkOrder/{item}', 'stores\OrdersController@checkOrder');
    $router->post('deliverOrder/{order}', 'stores\OrdersController@deliverOrder');
    $router->post('uploadFile', 'UploadController@file');

    $router->resource('product-beans', stores\ProductBeansController::class);

    // 在线课程
    $router->resource('lesson-categories', mooc\LessonCategoriesController::class);
    $router->resource('lessons', mooc\LessonsController::class);
    $router->resource('lesson-catalogs', mooc\LessonCatalogsController::class);
    $router->resource('lesson-chapters', mooc\LessonChaptersController::class);
    $router->post('lesson-catalog-orders', 'mooc\LessonCatalogsController@saveOrders');
    $router->post('lesson-catalog-add', 'mooc\LessonCatalogsController@saveFormModal');
    $router->post('branches/remove', 'mooc\LessonChaptersController@remove');
    $router->get('branches/edit/{catalog}', 'mooc\LessonCatalogsController@ajaxEditModal');
    $router->post('branches-edit', 'mooc\LessonCatalogsController@ajaxEdit');
    $router->post('lesson-chapter/publish/{lessonChapter}', 'mooc\LessonChaptersController@publishStream');

    // 配置
    $router->get('configs', 'config\ConfigController@index');
    $router->resource('banners', platform\BannersController::class);
});
