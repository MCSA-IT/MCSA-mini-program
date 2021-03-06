<?php
/**
 * 活动控制器
 */

namespace app\admin\controller;

use think\Request;
use app\common\model\WebActivity;
use app\common\validate\WebActivityValidate;

class WebActivityController extends Controller
{
    //列表
    public function index(Request $request, WebActivity $model)
    {
        $param = $request->param();
        $model = $model->scope('where', $param);
        $data = $model->paginate($this->admin['per_page'], false, ['query' => $request->get()]);
        //关键词，排序等赋值
        $this->assign($request->get());
        $this->assign([
            'data' => $data,
            'page' => $data->render(),
            'total' => $data->total(),
        ]);
        return $this->fetch();
    }

    //添加
    public function add(Request $request, WebActivity $model, WebActivityValidate $validate)
    {
        if ($request->isPost()) {
            $param = $request->param();
            $validate_result = $validate->scene('add')->check($param);
            if (!$validate_result) {
                return error($validate->getError());
            }
            //处理封面图上传
            $attachment_img = new \app\common\model\Attachment;
            $file_img = $attachment_img->upload('img');
            if ($file_img) {
                $param['img'] = $file_img->url;
            } else {
                return error($attachment_img->getError());
            }
            $param['content'] = $request->param(false)['content'];
            $result = $model::create($param);
            $url = URL_BACK;
            if (isset($param['_create']) && $param['_create'] == 1) {
                $url = URL_RELOAD;
            }
            return $result ? success('添加成功', $url) : error();
        }
        return $this->fetch();
    }

    //修改
    public function edit($id, Request $request, WebActivity $model, WebActivityValidate $validate)
    {
        $data = $model::get($id);
        if ($request->isPost()) {
            $param = $request->param();
            $validate_result = $validate->scene('edit')->check($param);
            if (!$validate_result) {
                return error($validate->getError());
            }
            //处理封面图上传
            if (!empty($_FILES['img']['name'])) {
                $attachment_img = new \app\common\model\Attachment;
                $file_img = $attachment_img->upload('img');
                if ($file_img) {
                    $param['img'] = $file_img->url;
                }
            }
            $param['content'] = $request->param(false)['content'];
            $result = $data->save($param);
            return $result ? success() : error();
        }
        $this->assign([
            'data' => $data,
        ]);
        return $this->fetch('add');
    }

    //删除
    public function del($id, WebActivity $model)
    {
        if (count($model->noDeletionId) > 0) {
            if (is_array($id)) {
                if (array_intersect($model->noDeletionId, $id)) {
                    return error('ID为' . implode(',', $model->noDeletionId) . '的数据无法删除');
                }
            } else if (in_array($id, $model->noDeletionId)) {
                return error('ID为' . $id . '的数据无法删除');
            }
        }
        if ($model->softDelete) {
            $result = $model->whereIn('id', $id)->useSoftDelete('delete_time', time())->delete();
        } else {
            $result = $model->whereIn('id', $id)->delete();
        }
        return $result ? success('操作成功', URL_RELOAD) : error();
    }

    public function disable_status($id)
    {
        $user         = WebActivity::get($id);
        $user->status = $user->status == 1 ? 0 : 1;
        $result       = $user->save();
        return $result ? success('操作成功', URL_RELOAD) : error();
    }

    public function disable_state($id)
    {
        $user         = WebActivity::get($id);
        $user->state = $user->state == 1 ? 0 : 1;
        $result       = $user->save();
        return $result ? success('操作成功', URL_RELOAD) : error();
    }
}
