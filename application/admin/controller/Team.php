<?php

namespace app\admin\controller;

use app\common\Controller;
use think\Request;
use app\index\model\Team as TeamModel;

class Team extends Controller
{

    /**
     * 显示队伍列表
     *
     * @param $nick
     * @param int $page
     * @param int $count
     * @return \think\Response
     */
    public function list($nick, int $page, int $count = 20)
    {
        try {
            $teams = (new TeamModel())->page($page, $count)
                ->where('nick', 'LIKE', '%' . $nick . '%')
                ->withCount(['doctors'])
                ->select();
        } catch (\Exception $e) {
            return $this->api(null, 1, "系统内部错误");
        }
        return $this->api($teams);
    }

    /**
     * 创建队伍
     *
     * @return \think\Response
     */
    public function create(Request $request)
    {
        $data = $request->only(['nick'], 'post');
        $result = $this->validate($data, 'app\index\validate\Team');
        if ($result !== true) {
            return $this->api(null, 1, $result);
        }
        try {
            $team = (new TeamModel())->where('nick', $data['nick'])->lock(true)->find();
            if ($team !== null) {
                return $this->api(null, 1, "该名字已存在");
            }
            $team = new TeamModel();
            $team->save($data);
            return $this->api($team);
        } catch (\Exception $e) {
            return $this->api(null, 1, "系统内部错误");
        }
    }

    /**
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
        try {
            $team = (new TeamModel())->where('id', $id)->lock(true)->find();
            if ($team == null) {
                return $this->api(null, 1, "该队伍不存在");
            }
            return $this->api($team);
        } catch (\Exception $e) {
            return $this->api(null, 1, "系统内部错误");
        }
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only(['nick'], 'post');
        $result = $this->validate($data, 'app\index\validate\Team');
        if ($result !== true) {
            return $this->api(null, 1, $result);
        }
        try {
            $team = (new TeamModel())->where('id', $id)->lock(true)->find();
            if ($team == null) {
                return $this->api(null, 1, "该队伍不存在");
            }
            $team->save($data);
            return $this->api($team);
        } catch (\Exception $e) {
            return $this->api(null, 1, "系统内部错误");
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        try {
            $team = (new TeamModel())->where('id', $id)->lock(true)->find();
            if ($team == null) {
                return $this->api(null, 1, "该队伍不存在");
            }
            $team->delete();
            return $this->api();
        } catch (\Exception $e) {
            return $this->api(null, 1, "系统内部错误");
        }
    }
}
