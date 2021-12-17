<?php
/**
 * Api身份验证
 */

namespace app\api\traits;

use app\common\model\ClockinList;
use app\common\model\RetroactiveCard;
use think\Db;

trait Retroactive
{
    /**
     * 自动补签
     */
    static function index($type,$uid)
    {
        //需补签的日期
        $no_date_all = ClockinList::get_retroactive_date($uid,$type);
        $data = [];
        if(count($no_date_all)==0){
            return ['code'=>1,'msg'=>'无需补签'];
        }
        //获取补签卡
        $card_model_count = RetroactiveCard::where(['user_id'=>$uid,'status'=>0])->count();
        if($card_model_count==0) {
            return ['code'=>1,'msg'=>'没有补签卡'];
        }
        $card_model = RetroactiveCard::where(['user_id'=>$uid,'status'=>0])->select()->toArray();
        //获取各个表的补签数据
        $create_clockin_list_arr = [];//创建打卡数据
        $update_retroactive_card_arr = [];//更新补签卡状态---id数组
        $time = date('H:i',time());
        foreach($card_model as $k=>$v){
            foreach ($no_date_all as $kk=>$vv){
                if($k == $kk){
                    //创建打卡数据
                    $create_clockin_list_arr[$k]['user_id'] = $uid;
                    $create_clockin_list_arr[$k]['type'] = $type;
                    $create_clockin_list_arr[$k]['date'] = $vv;
                    $create_clockin_list_arr[$k]['time'] = $time;
                    $create_clockin_list_arr[$k]['is_card'] = 1;//补签的不算累计
                    $create_clockin_list_arr[$k]['card_id'] = $v['id'];
                    //更新补签卡状态---id数组
                    $update_retroactive_card_arr[] = $v['id'];
                }
            }
        }
        // 启动事务
        Db::startTrans();
        try {
            Db::table('clockin_list')->insertAll($create_clockin_list_arr);
            Db::table('retroactive_card')
                ->where('id','in',$update_retroactive_card_arr)
                ->update(['status' => 1]);
            // 提交事务
            Db::commit();
            return ['code'=>0,'msg'=>'成功'];
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return ['code'=>1,'msg'=>'失败'];
        }
    }
}
