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

// +----------------------------------------------------------------------
// | 日志设置
// +----------------------------------------------------------------------

use think\facade\Env;

return [
    // 日志记录方式，内置 file socket 支持扩展
    'type'        => Env::get('log.type', 'File'),
    // 日志保存目录
    'path'        => '',
    // 日志记录级别
    'level'       => [],
    // 单文件日志写入
    'single'      => false,
    // 独立日志级别
    'apart_level' => [],
    // 最大日志文件数量
    'max_files'   => 0,
    // 是否关闭日志写入
    'close'       => false,
    'host'                => Env::get('log.host', ''),
    //日志强制记录到配置的client_id
    'force_client_ids'    => explode(',', Env::get('log.force_client_ids', '')),
    //限制允许读取日志的client_id
    'allow_client_ids'    => explode(',', Env::get('log.allow_client_ids', '')),
];
