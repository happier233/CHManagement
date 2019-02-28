<?php
/**
 * Created by PhpStorm.
 * User: Happy233
 * Date: 2019/2/21
 * Time: 18:13
 */

namespace app\common;


use think\Response;

trait ApiResponse
{

    /**
     * 返回封装后的API数据到客户端
     * @access protected
     * @param  mixed     $data 要返回的数据
     * @param  integer   $code 返回的code
     * @param  mixed     $msg 提示信息
     * @param  int       $http_code http代码
     * @param  array     $header 发送的Header信息
     * @return Response
     */
    protected function api($data, $code = 0, $msg = '', $http_code = 200, array $header = [])
    {
        $result = [
            'code' => $code,
            'msg' => $msg,
            'time' => time(),
            'data' => $data,
        ];
        $response = Response::create($result, 'json')->code($http_code)->header($header);
        return $response;
    }

}