<?php
/**
 * Created by PhpStorm.
 * User: happy233
 * Date: 2019-03-01
 * Time: 11:47
 */

namespace app\http\middleware;


use app\common\ApiResponse;
use app\index\model\Work;
use think\facade\Config;
use think\Request;

class CheckWork
{

    use ApiResponse;

    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed|\think\Response
     * @throws \Exception
     */
    public function handle(Request $request, \Closure $next) {
        if (!$request->has('id')) {
            return $this->api(null, 1, '参数错误');
        }
        $id = $request->get('id');
        /** @var Work|null $work */
        try {
            $work = Work::with(['doctor', 'detail'])->get($id);
        } catch (\Exception $e) {
            if(Config::get('app_debug')) {
                throw $e;
            }else{
                return $this->api(null, 404, '系统错误');
            }
        }
        if ($work == null) {
            return $this->api(null, 1, '该工单不存在');
        }
        if ($work->detail != null) {
            return $this->api(null, 1, '该工单已失效');
        }
        return $next($request);
    }

}