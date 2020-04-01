<?php

/**
 * Mooc
 */

Route::group([
    'middleware' => 'auth:api'
], function (\Illuminate\Routing\Router $router) {

    //LessonsComments
    $router->post('lesson/comment/{lesson}', 'LessonsCommentsController@store');

    //UserLessons
    $router->get('user/lessons', 'UserLessonsController@user_lessons');

    //Pay
    $router->post('lesson/pay/{lesson}','PayController@pay');
    $router->post('lesson/getLessons/{lesson}','PayController@getLessons');
    $router->get('lesson/watchLesson/{lessonChapter}', 'LessonsController@watchLesson');
});

Route::group([
    'middleware' => 'web.auth'
], function (\Illuminate\Routing\Router $router) {

    //LessonCategory
    $router->get('mooc/categories', 'LessonCategoryController@categories');
    $router->get('mooc/grades', 'LessonCategoryController@grades');
    $router->get('mooc/bans', 'LessonCategoryController@bans');
    $router->get('mooc/semesters', 'LessonCategoryController@semesters');

    //Teachers
    $router->get('mooc/teachers', 'TeachersController@index');
    $router->get('teachers/lessons/{teacher}', 'TeachersController@lessons');

    //Lesson
    $router->get('mooc/lessons', 'LessonsController@index');
    $router->get('lesson/recommends', 'LessonsController@recommends');
    $router->get('mooc/lesson/{lesson}', 'LessonsController@show');
    $router->get('lesson/catalogs/{lesson}', 'LessonsController@catalogs');


    //UserLessons
    $router->get('user/lesson_records', 'UserLessonsController@lesson_records');

    //LessonsComments
    $router->get('lesson/comments/{lesson}', 'LessonsCommentsController@index');

    //Pay
    $router->post('lesson/ali_notify_url','PayController@ali_notify_url')->name('lesson-ali-notify');
    $router->post('lesson/ali_return_url','PayController@ali_return_url')->name('lesson-ali-return');
    $router->post('lesson/wx_notify_url','PayController@wx_notify_url')->name('lesson-wx-notify');
    $router->post('lesson/wx_return_url','PayController@wx_return_url')->name('lesson-wx-return');

    //Live
    $router->get('lesson/catalogs/{lesson}', 'LessonsController@catalogs');
});
