<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
Route::bind('index');
Route::get('xx/:id','xx.xx/find');
Route::get('xx/:id','xx.xx/find');
Route::get('blog/:id','Blog.Blog/find');
Route::get('route','Route.Route/index');
Route::get('user','User/index');
//Route::group(['namespace' => 'Route'], function () {
//    Route::get('route', 'Route/index');
//});
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
