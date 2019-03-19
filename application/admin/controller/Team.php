<?php

namespace app\admin\controller;

use app\common\Controller;
use think\Request;
use app\index\model\Team as TeamModel;

class Team extends Controller
{

    protected $middleware = [
        'Auth',
        'ViewUser',
        'EditUser' => ['only' => ['create', 'update', 'delete']],
    ];

    public function list($nick = '', int $page = 1, int $count = 20)
    {
        try {
            $teams = (new TeamModel())
                ->withCount(['doctors'])
                ->page($page, $count);
            if (!empty($nick)) {
                $teams = $teams->whereLike('nick', "%{$nick}%");
            }
            $counts = (new TeamModel())
                ->withCount(['doctors']);
            if (!empty($nick)) {
                $counts = $counts->whereLike('nick', "%{$nick}%");
            }
            $counts = $counts->count('id');
            $teams = $teams->select();
            return $this->api([
                'counts' => $counts,
                'pages' => max(1, ceil($counts / $count)),
                'list' => $teams,
            ]);
        } catch (\Exception $e) {
            return $this->api(null, 1, "系统内部错误");
        }
    }

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

    public function read($id)
    {
        try {
            /** @var TeamModel $team */
            $team = (new TeamModel())->where('id', $id)->withCount(['doctors'])->find();
            if ($team == null) {
                return $this->api(null, 1, "该队伍不存在");
            }
            $team->load(['doctors']);
            return $this->api($team);
        } catch (\Exception $e) {
            return $this->api(null, 1, "系统内部错误");
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['nick'], 'post');
        $result = $this->validate($data, 'app\index\validate\Team');
        if ($result !== true) {
            return $this->api(null, 1, $result);
        }
        try {
            $team = (new TeamModel())->where('id', $id)->withCount(['doctors'])->find();
            if ($team == null) {
                return $this->api(null, 1, "该队伍不存在");
            }
            $team->save($data);
            return $this->api($team);
        } catch (\Exception $e) {
            return $this->api(null, 1, "系统内部错误");
        }
    }

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
