<?php
/**
 * 产品控制器
 */

namespace app\admin\controller;

use think\facade\Cache;
use think\facade\Env;
use think\Request;
use app\common\model\WebProduct;
use app\common\validate\WebProductValidate;
use EasyWeChat\Factory;
use think\facade\Config;
use ZipArchive;

class WebProductController extends Controller
{
//    protected $authExcept = [
//        'admin/web_product/ceshi_down'
//
//    ];

//    public function ceshi()
//    {
//        for ($i=1;$i<=1;$i++) {
//            $path = \think\facade\Request::scheme() . '://' . \think\facade\Request::host();//https://wxapp.qslbwg.cn/
//            $method = "GET";
//            $url = "/admin/web_product/down";
//            $postData = [];
//            request_asynchronous($url, $method, $postData, $path);
//        }
//    }

//    public function down($files)
//    {
//        $zipname = 'uploads/zip/'.date('Y-m-d').'-Export_Code--'.time().rand(1,9999).'.zip';
//        //$files = WebProduct::limit(1000)->column('ewm');
//        if(!empty($files)){
//            $data_list = [];
//            foreach ($files as $k=>$v){
//                $data_list[] = substr($v,1);
//            }
//            $zip = new ZipArchive();
//            $zip->open($zipname,\ZIPARCHIVE::OVERWRITE | \ZIPARCHIVE::CREATE);
//            //循环压缩文件
//            foreach($data_list as $key => $value){
//                if(file_exists($value)){
//                    $zip->addFile($value,basename($value));
//                }
//            }
//            //打包zip
//            $zip->close();
//            header("Cache-Control:public");
//            header("Content-Description: File Transfer");
//            header("Content-disposition: attachment; filename=".basename($zipname));//文件名
//            header("Content-Type:application/zip"); //格式为zip
//            header("Content-Transfer-Encoding:binary"); //这是二进制文件
//            header("Content-Length:".filesize($zipname)); //文件大小
//            @readfile($zipname);
//        }
//    }
    //列表
    public function index(Request $request, WebProduct $model)
    {
        $param = $request->param();
        $model = $model->scope('where', $param);
        $data_id_arr = [];
        if (isset($param['export_data']) && $param['export_data'] == 1) {
//            $header = ['ID', '溯源码', '二维码'];
//            $body   = [];
//            $data   = $model->where('id','in',$request->param('id'))->select();
//            foreach ($data as $item) {
//                $record                  = [];
//                $record['id']            = $item->id;
//                $record['code']        = $item->code;
//                $record['ewm']        = $item->ewm;
//                $body[] = $record;
//                $data_id_arr[] = ['id'=>$item['id'],'export_type'=>1];
//            }
//            if(!empty($data_id_arr)){
//                $product_model = new WebProduct;
//                $product_model->saveAll($data_id_arr);
//            }
//            return $this->exportData_new($header, $body, 'user-' . date('Y-m-d-H-i-s'));
        }elseif(isset($param['export_data']) && $param['export_data'] == 2){
            $where[] = ['export_type','=','0'];
            $where[] = ['ewm','<>','null'];
            $where[] = ['ewm','<>',''];
            $data = WebProduct::where($where)->field('id,code,ewm')->order('id', 'asc')->limit(1000)->select();
            if(!is_null($data)){
                $zipname = 'uploads/zip/'.date('Y-m-d').'-Export_Code--'.time().rand(1,9999).'.zip';
                //$data_list = [];
//                foreach ($data as $k=>$v){
//                    $data_list[] = substr($v,1);
//                }
                $zip = new ZipArchive();
                $zip->open($zipname,\ZIPARCHIVE::OVERWRITE | \ZIPARCHIVE::CREATE);
                //循环压缩文件
                foreach($data as $key => $value){
                    $ewm_new = substr($value['ewm'],1);
                    if(file_exists($ewm_new)){
                        $zip->addFile($ewm_new,basename($ewm_new));
                    }
                    $data_id_arr[] = ['id'=>$value['id'],'export_type'=>1];
                }
                if(!empty($data_id_arr)){
                    $product_model = new WebProduct;
                    $product_model->startTrans();
                    try {
                        $product_model->saveAll($data_id_arr);
                        //打包zip
                        $zip->close();
                        $result = true;
                        $product_model->commit();
                    } catch (\Exception $e) {
                        $result = false;
                        $product_model->rollback();
                    }
                    header("Cache-Control:public");
                    header("Content-Description: File Transfer");
                    header("Content-disposition: attachment; filename=".basename($zipname));//文件名
                    header("Content-Type:application/zip"); //格式为zip
                    header("Content-Transfer-Encoding:binary"); //这是二进制文件
                    header("Content-Length:".filesize($zipname)); //文件大小
                    return @readfile($zipname);
                }
            }
        }
        if(isset($param['product_state'])&&$param['product_state']!=''){
            if($param['product_state']==0){
                $model->where('img_arr|ms','=',Null);
            }else{
                $model->where('img_arr&ms','<>','');
            }
        }
        $data = $model->paginate($this->admin['per_page'], false, ['query' => $request->get()]);
        //关键词，排序等赋值
        $this->assign($request->get());

        //查询需要生成的二维码
        $ewm_num = WebProduct::where('ewm','null')->whereOr('ewm','')->count();

        //查询需要生成的二维码
        $export_where[] = ['export_type','=','0'];
        $export_where[] = ['ewm','<>','null'];
        $export_where[] = ['ewm','<>',''];
        $export_ewm_num = WebProduct::where($export_where)->count();
        $this->assign([
            'export_ewm_num' =>$export_ewm_num,
            'ewm_num' =>$ewm_num,
            'data' => $data,
            'page' => $data->render(),
            'total' => $data->total(),
        ]);
        return $this->fetch();
    }
//    public function index(Request $request, WebProduct $model)
//    {
//        $param = $request->param();
//        $model = $model->scope('where', $param);
//        $data_id_arr = [];
//        if (isset($param['export_data']) && $param['export_data'] == 1) {
//            $header = ['ID', '溯源码', '二维码'];
//            $body   = [];
//            $data   = $model->where('id','in',$request->param('id'))->select();
//            foreach ($data as $item) {
//                $record                  = [];
//                $record['id']            = $item->id;
//                $record['code']        = $item->code;
//                $record['ewm']        = $item->ewm;
//                $body[] = $record;
//                $data_id_arr[] = ['id'=>$item['id'],'export_type'=>1];
//            }
//            if(!empty($data_id_arr)){
//                    $product_model = new WebProduct;
//                    $product_model->saveAll($data_id_arr);
//            }
//            return $this->exportData_new($header, $body, 'user-' . date('Y-m-d-H-i-s'));
//        }elseif(isset($param['export_data']) && $param['export_data'] == 2){
//            $header = ['ID', '溯源码', '二维码'];
//            $body   = [];
//            $data = WebProduct::where('export_type','0')->field('id,code,ewm')->order('id', 'asc')->limit(200)->select();
//
//            foreach ($data as $item) {
//                $record                  = [];
//                $record['id']            = $item->id;
//                $record['code']        = $item->code;
//                $record['ewm']        = $item->ewm;
//                $body[] = $record;
//                $data_id_arr[] = ['id'=>$item['id'],'export_type'=>1];
//            }
//            if(!empty($data_id_arr)){
//                    $product_model = new WebProduct;
//                    $product_model->saveAll($data_id_arr);
//            }
//            return $this->exportData_new($header, $body, 'user-' . date('Y-m-d-H-i-s'));
//        }
//        if(isset($param['product_state'])&&$param['product_state']!=''){
//            if($param['product_state']==0){
//                $model->where('img_arr|ms','=',Null);
//            }else{
//                $model->where('img_arr&ms','<>','');
//            }
//        }
//        $data = $model->paginate($this->admin['per_page'], false, ['query' => $request->get()]);
//        //关键词，排序等赋值
//        $this->assign($request->get());
//
//        //查询需要生成的二维码
//        $ewm_num = WebProduct::where('ewm','null')->count();
//
//        //查询需要生成的二维码
//        $export_ewm_num = WebProduct::where('export_type','0')->count();
//        $this->assign([
//            'export_ewm_num' =>$export_ewm_num,
//            'ewm_num' =>$ewm_num,
//            'data' => $data,
//            'page' => $data->render(),
//            'total' => $data->total(),
//        ]);
//        return $this->fetch();
//    }

    //添加
    public function add(Request $request, WebProduct $model, WebProductValidate $validate)
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
//处理多图上传上传
            $attachment_img_arr = new \app\common\model\Attachment;
            $file = $attachment_img_arr->uploadMulti('img_arr');
            if ($file) {
                $param['img_arr'] = $file;
            } else {
                return error($attachment_img_arr->getError());
            }
            $param['ms'] = $request->param(false)['ms'];
            $param['note'] = $request->param(false)['note'];
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
    public function edit($id, Request $request, WebProduct $model, WebProductValidate $validate)
    {
        $data = $model::get($id);
        if ($request->isPost()) {
            $param = $request->param();
            $validate_result = $validate->scene('edit')->check($param);
            if (!$validate_result) {
                return error($validate->getError());
            }
            //处理封面图上传
//            if (!empty($_FILES['img']['name'])) {
//                $attachment_img = new \app\common\model\Attachment;
//                $file_img = $attachment_img->upload('img');
//                if ($file_img) {
//                    $param['img'] = $file_img->url;
//                }
//            }
//处理多图上传上传
            if (!empty($_FILES['img_arr']['name'][0])) {
                $attachment_img_arr = new \app\common\model\Attachment;
                $file = $attachment_img_arr->uploadMulti('img_arr');
                if ($file) {
                    //$param['img_arr'] = $file;
                    $param['img_arr'] = array_merge($data['img_arr'],$file);
                } else {
                    return error($attachment_img_arr->getError());
                }
            }
            $param['ms'] = $request->param(false)['ms'];
//            $param['note'] = $request->param(false)['note'];
            $result = $data->save($param);
            return $result ? success() : error();
        }
        $this->assign([
            'data' => $data,
        ]);
        return $this->fetch('add');
    }

    //删除
    public function del($id, WebProduct $model)
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

    //删除
    public function delimg($id,Request $request,WebProduct $model)
    {
        //在原基础上删除
        $param = $request->param();
        $key = $param['key'];
        $data = $model::get($id);
        $zutu = $data['img_arr'];
        if(array_key_exists($key,$zutu)){
            unset($zutu[$key]);
        }
        $data->img_arr = array_values($zutu);
        $a = $data->save();
        return 1;
    }

    //生成溯源码--批量生成
    public function add_code(Request $request,WebProduct $model)
    {
        $num = $request->param('num',0);
        if($num){
            $model->startTrans();
            try {
                // 这里是主体代码
                $result = $model::create_code($num);
                if(!$result){
                    $model->rollback();
                }
                $result = true;
                $model->commit();
            } catch (\Exception $e) {
                // 这是进行异常捕获
                $result = false;
                $model->rollback();
            }
            if($result){
                return success('操作成功');
            }
            return error('操作失败');
        }else{
            return error("操作失败");
        }
    }

    /**
     * 生成二维码
     * $arr
     * */
    public function ewm($id,Request $request,WebProduct $model)
    {
        $app = Factory::miniProgram(Config::get("api.easywechat"));
        foreach ($id as $k=>$v){
            $code = $model::get_code($v);//溯源码
            //$path = "pages/result/result?code=".$code;
            $path = "pages/result/result";
            //$response = $app->app_code->getQrCode($path, $width = null);
            $response = $app->app_code->getUnlimit($code, [
                'page'  => $path,
                'width' => 600,
            ]);
            $local_folder = Env::get('root_path').'/public/uploads/ewm/';//本地绝对路径，存放生成的二维码
            $name = $code.".png";
            if(file_exists($local_folder.$name)){
                $a = unlink($local_folder.$name);
            }
            if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
                $filename = $response->saveAs($local_folder, $name);
            }
            $file_path = "/uploads/ewm/".$filename;
            if(!$model::set_ewm($v,$file_path)){
                return error("id:".$v."的数据生成二维码失败");
            }
        }
        return success('操作成功', URL_RELOAD);
    }

    /**
     * 生成剩余二维码--最多1000条
     * $arr
     * */
    public function ewm_all(Request $request,WebProduct $model)
    {
        date_default_timezone_set("PRC");
        set_time_limit(0);
        $app = Factory::miniProgram(Config::get("api.easywechat"));
        $product_all = WebProduct::where('ewm','null')->whereOr('ewm','')->field('id,code')->order('id', 'asc')->limit(200)->select();
        $data = [];
        foreach ($product_all as $k=>$v){
            $code = $v['code'];//溯源码
            $path = "pages/result/result";
            $response = $app->app_code->getUnlimit($code, [
                'page'  => $path,
                'width' => 600,
            ]);
            $local_folder = Env::get('root_path').'/public/uploads/ewm/';//本地绝对路径，存放生成的二维码
            $name = $code.".png";
            if(file_exists($local_folder.$name)){
                $a = unlink($local_folder.$name);
            }
            if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
                $filename = $response->saveAs($local_folder, $name);
            }
            $file_path = "/uploads/ewm/".$filename;
            $data[] = ['id'=>$v['id'], 'ewm'=>$file_path];
        }
        if(!empty($data)&&$model::set_ewm_all($data)){
            return success('操作成功', URL_RELOAD);
        }
        return error("生成二维码失败");
    }
//    public function ewm($id,Request $request,WebProduct $model)
//    {
//        $app = Factory::miniProgram(Config::get("api.easywechat"));
//        foreach ($id as $k=>$v){
//            $code = $model::get_code($v);//溯源码
//            $path = "pages/result/result?code=".$code;
//            $response = $app->app_code->getQrCode($path, $width = null);
//            $local_folder = Env::get('root_path').'/public/uploads/ewm/';//本地绝对路径，存放生成的二维码
//            $name = $code.".png";
//            if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
//                $filename = $response->saveAs($local_folder, $name);
//            }
//            $file_path = "/uploads/ewm/".$filename;
//            if(!$model::set_ewm($v,$file_path)){
//                return error("id:".$v."的数据生成二维码失败");
//            }
//        }
//        return success('操作成功', URL_RELOAD);
//    }
}


