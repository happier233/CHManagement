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

    public function list(Request $request, $page = 1, $count = 20)
    {
        $keys = [
            'id', 'doctor', 'start_time', 'duration',
            'name', 'college', 'evaluation', 'confirm_time',
        ];
        $data = filterEmpty($request->only($keys, 'post'));
        $keys = array_keys($keys);
        $counts = (new WorkModel())->count('id');
        /** @var Collection $works */
        $works = (new WorkModel())
            ->withJoin(['detail', 'doctor.eteam'], 'LEFT')
            ->withSearch($keys, $data)
            ->page($page, $count)->select();
        return $this->api([
            'counts' => $counts,
            'pages' => max(1, ceil($counts / $count)),
            'list' => $works,
        ]);
    }

    public function read($id)
    {
        /** @var WorkModel $work */
        $work = (new WorkModel())
            ->withJoin(['detail', 'doctor'], 'LEFT')
            ->where('id', '=', $id)
            ->find();
        if ($work == null) {
            return $this->api(null, 1, '工单不存在');
        }
        $team = $work->getRelation('doctor')->eteam;
        $work = $work->toArray();
        $work['doctor']['tnick'] = $team->nick;
        if (!isset($work['detail'])) {
            $work['detail'] = [
                'wid' => 0,
                'name' => '未确认',
                'tel' => '未确认',
                'stu_id' => '未确认',
                'college' => '未确认',
                'evaluation' => '',
                'message' => '',
                'confirm_time' => '未确认',
            ];
        }
        return $this->api($work);
    }

    public function delete($id)
    {
        /** @var WorkModel|null $work */
        $work = WorkModel::get($id);
        if ($work == null) {
            return $this->api(null, 1, '该工单不存在');
        }
        $work->delete();
        return $this->api();
    }

    public function deleteMany(Request $request)
    {
        if (!$request->has('id')) {
            return $this->api(null, 1, '参数类型错误');

        }
        $id = $request->post('id');
        if (!is_array($id)) {
            return $this->api(null, 1, '参数类型错误');
        }
        foreach ($id as &$t) {
            $t = (int)$t;
        }
        /** @var WorkModel|null $work */
        WorkModel::destroy($id);
        return $this->api();
    }
}
