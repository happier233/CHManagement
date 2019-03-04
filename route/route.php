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
Route::post('doctor/check', 'index/Doctor/check');

Route::post('admin/login', 'admin/User/login');
Route::post('admin/check', 'admin/User/check');
Route::post('admin/user/list', 'admin/User/list');
Route::post('admin/user/create', 'admin/User/create');
Route::post('admin/user/read', 'admin/User/read');
Route::post('admin/user/update', 'admin/User/update');
Route::post('admin/user/delete', 'admin/User/delete');

Route::post('admin/team/list', 'admin/Team/list');
Route::post('admin/team/create', 'admin/Team/create');
Route::post('admin/team/read', 'admin/Team/read');
Route::post('admin/team/update', 'admin/Team/update');
Route::post('admin/team/delete', 'admin/Team/delete');


return [

];
