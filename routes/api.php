<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::any('test', 'api\\TestController@index');
// 用户认证
Route::prefix('v1/auth')
    ->namespace('api\\auth')
    ->group(base_path('routes/auth.php'));

// 基础信息
Route::prefix('v1/baseinfo')
    ->namespace('api\\baseinfo')
    ->group(base_path('routes/baseinfo.php'));

// 共享图书
Route::prefix('v1/shares')
    ->namespace('api\\shares')
    ->group(base_path('routes/shares.php'));

// 我的相关
Route::prefix('v1/mine')
    ->namespace('api\\mine')
    ->group(base_path('routes/mine.php'));

// 课程
Route::namespace('api\\mooc')
    ->group(base_path('routes/mooc.php'));

// 商城
Route::namespace('api\\store')
    ->group(base_path('routes/store.php'));

// 其他先关信息
Route::prefix('v1/platform')
    ->namespace('api\\platform')
    ->group(base_path('routes/platform.php'));