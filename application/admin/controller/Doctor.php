<?php

namespace app\admin\controller;

use app\common\Controller;
use think\db\exception\DataNotFoundException;
use think\model\Collection;
use think\Request;
use app\index\model\Doctor as DoctorModel;
use app\index\model\User as UserModel;
use app\index\model\Team as TeamModel;

class Doctor extends Controller
{

    protected $middleware = [
        'Auth',
        'ViewUser',
        'EditUser' => ['only' => ['create', 'update', 'delete']],
    ];

    public function create(Request $request, $uid)
    {
        $data = $request->only(['name', 'id_code', 'stu_id', 'team', 'position'], 'post');
        $data['uid'] = $uid;
        $result = $this->validate($data, 'app\index\validate\Doctor.create');
        if ($result !== true) {
            return $this->api(null, 1, $result);
        }
        /** @var UserModel $user */
        $user = (new UserModel())->withJoin(['doctor'], 'LEFT')->where('id', '=', $data['uid'])->find();
        if ($user == null) {
            return $this->api(null, 1, '绑定的用户不存在');
        }
        if ($user->doctor != null) {
            return $this->api(null, 1, '该用户已是电医');
        }
        /** @var TeamModel $team */
        $team = (new TeamModel())->where('id', '=', $data['team'])->find();
        if ($team == null) {
            return $this->api(null, 1, '该队伍不存在');
        }
        $doctor = new DoctorModel();
        $doctor->save($data);
        return $this->api($doctor);
    }

    public function list(Request $request, $count = 20, $page = 1)
    {
        $keys = ['uid', 'name', 'id_code', 'stu_id', 'team', 'position'];
        $data = filterEmpty($request->only($keys, 'post'));
        $keys = array_keys($data);
        $counts = (new DoctorModel())
            ->withSearch($keys, $data)
            ->count('uid');
        /** @var Collection $doctors */
        $doctors = (new DoctorModel())
            ->withJoin(['eteam'])
            ->withSearch($keys, $data)
            ->page($page, $count)
            // ->visible(['uid', 'name', 'id_code', 'stu_id', 'team', 'team.nick', 'position'])
            ->select();
        $doctors = $doctors->toArray();
        foreach ($doctors as &$doctor) {
            $doctor['team'] = $doctor['eteam'];
            unset($doctor['eteam']);
        }
        return $this->api([
            'counts' => $counts,
            'pages' => max(1, ceil($counts / $count)),
            'list' => $doctors,
        ]);
    }

    /**
     * @param $uid
     * @return \think\Response
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function read($uid)
    {
        try {
            /** @var DoctorModel $doctor */
            $doctor = (new DoctorModel())->withJoin(['eteam'])->where('uid', '=', $uid)->findOrFail();
            $doctor->visible(['uid', 'name', 'id_code', 'stu_id', 'team', 'position']);
            return $this->api($doctor);
        } catch (DataNotFoundException $e) {
            return $this->api(null, 0, '该电医不存在');
        }
    }

    public function update(Request $request, $uid)
    {
        $data = $request->only(['name', 'id_code', 'stu_id', 'team', 'position'], 'post');
        $result = $this->validate($data, 'app\index\validate\Doctor.create');
        if ($result !== true) {
            return $this->api(null, 1, $result);
        }
        if (isset($data['team'])) {
            $team = TeamModel::get($data['team']);
            if ($team == null) {
                return $this->api(null, 1, '该队伍不存在');
            }
        }
        /** @var DoctorModel $doctor */
        $doctor = DoctorModel::get($uid);
        if ($doctor == null) {
            return $this->api(null, 0, '该电医不存在');
        }
        $doctor->save($data);
        return $this->api($doctor);
    }

    public function delete($uid)
    {
        /** @var DoctorModel $doctor */
        $doctor = DoctorModel::get($uid);
        if ($doctor == null) {
            return $this->api(null, 0, '该电医不存在');
        }
        $doctor->delete();
        return $this->api();
    }
}
