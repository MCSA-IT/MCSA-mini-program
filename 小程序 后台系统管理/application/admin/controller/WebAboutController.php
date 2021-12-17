<?php
/**
 * 关于我们控制器
 */

namespace app\admin\controller;

use think\Request;
use app\common\model\WebAbout;
use app\common\validate\WebAboutValidate;

class WebAboutController extends Controller
{
    //修改
    public function index(Request $request, WebAbout $model, WebAboutValidate $validate)
    {
        $data = $model::get(6);
        if ($request->isPost()) {
            $param = $request->param();
            $validate_result = $validate->scene('edit')->check($param);
            if (!$validate_result) {
                return error($validate->getError());
            }
            $param['content'] = $request->param(false)['content'];
            $param['content1'] = $request->param(false)['content1'];
//处理多视频上传上传
            if (!empty($_FILES['video']['name'][0])) {
                $attachment_video = new \app\common\model\Attachment;
                $file = $attachment_video->uploadMulti('video');
                if ($file) {
                    //$param['video'] = $file;
                    $param['video'] = array_merge($data['video'],$file);
                } else {
                    return error($attachment_video->getError());
                }
            }
//处理多图片上传上传
            if (!empty($_FILES['img']['name'][0])) {
                $attachment_img = new \app\common\model\Attachment;
                $file = $attachment_img->uploadMulti('img');
                if ($file) {
                    //$param['img'] = $file;
                    $param['img'] = array_merge($data['img'],$file);
                } else {
                    return error($attachment_img->getError());
                }
            }
            //$data_new = WebAbout::get(1);
            //halt($data_new);
//            $user = new WebAbout($param);
//            $result = $user->save();
            $result = $data->save($param);
            return $result ? success('操作成功','','/admin/web_about/index') : error('操作失败','/admin/web_about/index');
        }
        $this->assign([
            'data' => $data,
        ]);
        return $this->fetch('add');
    }
    //删除
    public function delimg(Request $request,WebAbout $model)
    {
        //在原基础上删除
        $param = $request->param();
        $key = $param['key'];
        $data = $model::get(6);
        $zutu = $data['img'];
        if(array_key_exists($key,$zutu)){
            unset($zutu[$key]);
        }
        $data->img = array_values($zutu);
        $a = $data->save();
        return 1;
    }
    //删除
    public function delvideo(Request $request,WebAbout $model)
    {
        //在原基础上删除
        $param = $request->param();
        $key = $param['key'];
        $data = $model::get(6);
        $zutu = $data['video'];
        if(array_key_exists($key,$zutu)){
            unset($zutu[$key]);
        }
        $data->video = array_values($zutu);
        $a = $data->save();
        return 1;
    }

    //图片编辑排序
    public function imgpaixu(Request $request,WebAbout $model)
    {
        $param = $request->param();
        //halt($param['stack']);
        $data = $model::get(6);
        $zutu = $data['img'];
        $new_arr = [];
        foreach ($param['stack'] as $k=>$v){
            $new_arr[] = $zutu[$v['key']];
        }
        $data->img = $new_arr;
        if($data->save()){
            return 1;
        }
    }
    //图片编辑排序
    public function videopaixu(Request $request,WebAbout $model)
    {
        $param = $request->param();
        //halt($param['stack']);
        $data = $model::get(6);
        $zutu = $data['video'];
        $new_arr = [];
        foreach ($param['stack'] as $k=>$v){
            $new_arr[] = $zutu[$v['key']];
        }
        $data->video = $new_arr;
        if($data->save()){
            return 1;
        }
    }
//    //列表
//    public function index(Request $request, WebAbout $model)
//    {
//        $param = $request->param();
//        $model = $model->scope('where', $param);
//        $data = $model->paginate($this->admin['per_page'], false, ['query' => $request->get()]);
//        //关键词，排序等赋值
//        $this->assign($request->get());
//        $this->assign([
//            'data' => $data,
//            'page' => $data->render(),
//            'total' => $data->total(),
//        ]);
//        return $this->fetch();
//    }
//
//    //添加
//    public function add(Request $request, WebAbout $model, WebAboutValidate $validate)
//    {
//        if ($request->isPost()) {
//            $param = $request->param();
//            $validate_result = $validate->scene('add')->check($param);
//            if (!$validate_result) {
//                return error($validate->getError());
//            }
//            $param['content'] = $request->param(false)['content'];
//            $param['content1'] = $request->param(false)['content1'];
////处理多视频上传上传
//            $attachment_video = new \app\common\model\Attachment;
//            $file = $attachment_video->uploadMulti('video');
//            if ($file) {
//                $param['video'] = $file;
//            } else {
//                return error($attachment_video->getError());
//            }
////处理多图片上传上传
//            $attachment_img = new \app\common\model\Attachment;
//            $file = $attachment_img->uploadMulti('img');
//            if ($file) {
//                $param['img'] = $file;
//            } else {
//                return error($attachment_img->getError());
//            }
//            $result = $model::create($param);
//            $url = URL_BACK;
//            if (isset($param['_create']) && $param['_create'] == 1) {
//                $url = URL_RELOAD;
//            }
//            return $result ? success('添加成功', $url) : error();
//        }
//        return $this->fetch();
//    }
//
//    //修改
//    public function edit($id, Request $request, WebAbout $model, WebAboutValidate $validate)
//    {
//        $data = $model::get($id);
//        if ($request->isPost()) {
//            $param = $request->param();
//            $validate_result = $validate->scene('edit')->check($param);
//            if (!$validate_result) {
//                return error($validate->getError());
//            }
//            $param['content'] = $request->param(false)['content'];
//            $param['content1'] = $request->param(false)['content1'];
////处理多视频上传上传
//            if (!empty($_FILES['video']['name'][0])) {
//                $attachment_video = new \app\common\model\Attachment;
//                $file = $attachment_video->uploadMulti('video');
//                if ($file) {
//                    $param['video'] = $file;
//                } else {
//                    return error($attachment_video->getError());
//                }
//            }
////处理多图片上传上传
//            if (!empty($_FILES['img']['name'][0])) {
//                $attachment_img = new \app\common\model\Attachment;
//                $file = $attachment_img->uploadMulti('img');
//                if ($file) {
//                    $param['img'] = $file;
//                } else {
//                    return error($attachment_img->getError());
//                }
//            }
//            $result = $data->save($param);
//            return $result ? success() : error();
//        }
//        $this->assign([
//            'data' => $data,
//        ]);
//        return $this->fetch('add');
//    }
//
//    //删除
//    public function del($id, WebAbout $model)
//    {
//        if (count($model->noDeletionId) > 0) {
//            if (is_array($id)) {
//                if (array_intersect($model->noDeletionId, $id)) {
//                    return error('ID为' . implode(',', $model->noDeletionId) . '的数据无法删除');
//                }
//            } else if (in_array($id, $model->noDeletionId)) {
//                return error('ID为' . $id . '的数据无法删除');
//            }
//        }
//        if ($model->softDelete) {
//            $result = $model->whereIn('id', $id)->useSoftDelete('delete_time', time())->delete();
//        } else {
//            $result = $model->whereIn('id', $id)->delete();
//        }
//        return $result ? success('操作成功', URL_RELOAD) : error();
//    }
}
