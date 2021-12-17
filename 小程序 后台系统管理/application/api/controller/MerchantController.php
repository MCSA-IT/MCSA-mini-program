<?php


namespace app\api\controller;


use app\common\model\WebMerchant;

class MerchantController extends ApiController
{
    /**
     * 商家--列表
     */
    public function search()
    {
        $where = [];
        if(isset($this->param['title'])&&!empty($this->param['title'])){
            $where[] = ['title','like','%'.$this->param['title'].'%'];
        }
        $list = WebMerchant::where($where)
            ->order(['sort'=>'desc','id'=>'desc'])
            ->paginate($this->limit,true,['page'=>$this->page]);
        $count = WebMerchant::where($where)->count();
        $data['page'] = $this->page;
        $data['limit'] = $this->limit;
        $data['total'] = ceil($count/$this->limit);
        $data['data'] = [];
        if(!is_null($list)){
            $list = $list->toArray();
            $data['data'] = $list['data'];
        }
        return $this->success($data);
    }

    /**
     * 活动详情
     */
    public function detail($id)
    {
        $info = WebMerchant::get($id);
        if(!is_null($info)){
            return $this->success($info);
        }
        return $this->error("查无此信息");
    }
}