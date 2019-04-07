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

use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('/', 'index/Index/index');
Route::get('customer/info', 'index/Customer/info');
Route::post('customer/emit', 'index/Customer/emit');

Route::post('doctor/login', 'index/Doctor/login');
Route::post('doctor/emit', 'index/Doctor/emit');
Route::rule('doctor/check', 'index/Doctor/check', 'GET|POST');
Route::rule('doctor/logout', 'index/Doctor/logout', 'GET|POST');

Route::post('admin/login', 'admin/User/login');
Route::rule('admin/check', 'admin/User/check', 'GET|POST');
Route::rule('admin/logout', 'admin/User/logout', 'GET|POST');

Route::post('admin/user/list', 'admin/User/list');
Route::post('admin/user/create', 'admin/User/create');
Route::rule('admin/user/read/:id', 'admin/User/read', 'GET|POST')
    ->pattern(['id' => '\d+']);
Route::post('admin/user/update', 'admin/User/update');
Route::rule('admin/user/delete', 'admin/User/delete', 'GET|POST');

Route::post('admin/team/list', 'admin/Team/list');
Route::post('admin/team/create', 'admin/Team/create');
Route::rule('admin/team/read/:id', 'admin/Team/read', 'GET|POST')
    ->pattern(['id' => '\d+']);
Route::post('admin/team/update', 'admin/Team/update');
Route::rule('admin/team/delete', 'admin/Team/delete', 'GET|POST');

Route::post('admin/work/list', 'admin/Work/list');
Route::rule('admin/work/read/:id?', 'admin/Work/read', 'GET|POST')
    ->pattern(['id' => '\d+']);
Route::rule('admin/work/delete', 'admin/Work/delete', 'GET|POST');
Route::rule('admin/work/deleteMany', 'admin/Work/deleteMany', 'POST');

return [

];
