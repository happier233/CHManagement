<?php

namespace app\admin\controller;

use app\common\Controller;
use think\model\Collection;
use think\Request;
use app\index\model\Work as WorkModel;

class Work extends Controller
{

    protected $middleware = [
        'Auth',
        'ViewWork',
        'EditWork' => ['only' => ['delete']],
    ];

    public function list(Request $request, $page = 1, $count = 20) {
        $keys = [
            'id', 'doctor', 'start_time', 'duration',
            'name', 'college', 'evaluation', 'confirm_time',
        ];
        $data = filterEmpty($request->only($keys, 'post'));
        $keys = array_keys($keys);
        $counts = (new WorkModel())
            ->withJoin(['work_detail', 'doctor'], 'LEFT')
            ->withSearch($keys, $data)
            ->count('id');
        /** @var Collection $works */
        $works = (new WorkModel())
            ->withJoin(['work_detail', 'doctor'], 'LEFT')
            ->withSearch($keys, $data)
            ->page($page, $count)->select();
        $works->visible([
            'id', 'doctor.name', 'start_time', 'duration',
            'work_detail.name', 'work_detail.college', 'work_detail.evaluation', 'work_detail.confirm_time',
        ]);
        return $this->api([
            'counts' => $counts,
            'pages' => max(1, ceil($counts / $count)),
            'list' => $works,
        ]);
    }

    public function read($id) {
        $work = (new WorkModel())
            ->withJoin(['work_detail', 'doctor'])
            ->where('id', '=', $id)
            ->find();
        if ($work == null) {
            return $this->api(null, 1, '工单不存在');
        }
        return $this->api($work);
    }

    public function delete($id) {
        /** @var WorkModel|null $work */
        $work = WorkModel::get($id);
        if ($work == null) {
            return $this->api(null, 1, '该工单不存在');
        }
        $work->delete();
        return $this->api();
    }
}
