<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\wechat;

use app\admin\service\Service;
use app\common\models\Setting as SettingModel;
use app\common\models\wechat\WechatUsers as WechatUsersModel;
use app\common\models\users\Users as UsersModel;
use app\common\models\users\UsersAddress as UsersAddressModel;
use app\common\models\users\UsersBonus as UsersBonusModel;
use app\common\models\users\UsersComment as UsersCommentModel;
use app\common\models\users\UsersConsult as UsersConsultModel;
use app\common\models\users\UsersFavorite as UsersFavoriteModel;
use app\common\models\users\UsersLog as UsersLogModel;
use app\common\models\users\UsersRechange as UsersRechangeModel;
use app\common\models\users\UsersWithdrawLog as UsersWithdrawLogModel;
use app\common\models\order\Order as OrderModel;
use app\common\models\order\OrderGoods as OrderGoodsModel;
use app\common\models\order\OrderLog as OrderLogModel;
use app\common\models\order\OrderCollection as OrderCollectionModel;
use app\common\models\order\OrderDelivery as OrderDeliveryModel;
use app\common\models\order\OrderRefundment as OrderRefundmentModel;
use app\common\models\order\OrderGroup as OrderGroupModel;
use app\common\models\wechat\WechatUsersTags as WechatUsersTagsModel;
use app\common\library\wechat\Factory;
use mall\utils\Tool;

class Fans extends Service {

    /**
     * 获取粉丝列表
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = WechatUsersModel::withSearch(["nickname"],[ 'nickname'=>$data['key']["title"]??'' ])->count();

        $result = array_map(function ($res){
            $res['photo'] = Tool::thumb($res['headimgurl']);

            $tags = WechatUsersTagsModel::column('name', 'id');
            $res['tags'] = [];
            foreach (explode(',', $res['tagid_list']) as $tagid) {
                if (isset($tags[$tagid])) $res['tags'][] = $tags[$tagid];
            }

            $res['tags'] = implode(",",$res['tags']);
            $res['area'] = implode(",",[$res["country"], $res["province"], $res["city"]]);
            return $res;
        },WechatUsersModel::withSearch(["nickname"],[ 'nickname'=>$data['key']["title"]??'' ])->order("id","desc")->page($data["page"]??1,$data["limit"]??10)->select()->toArray());

        return ["count"=>$count, "data"=>$result];
    }

    /**
     * 批量获取用户基本信息
     * @param string $next
     * @param int $done
     * @return int|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function syncFans($next = '', $done = 0){
        $appid = SettingModel::getArrayData("wechat.appid");
        while (!is_null($next) && is_array($result = Factory::wechat()->user->getUserList($next)) && !empty($result['data']['openid'])) {
            foreach (array_chunk($result['data']['openid'], 100) as $openids) {
                if (is_array($list = Factory::wechat()->user->batchUserInfo($openids)) && !empty($list['user_info_list'])) {
                    foreach ($list['user_info_list'] as $user) {
                        self::saveFans($user, $appid);
                    }
                }
            }
            $next = $result['total'] > $done ? $result['next_openid'] : null;
        }

        return $done;
    }

    /**
     * 保存粉丝资料
     * @param $user
     * @param $appid
     * @return WechatUsersModel|bool|\think\Model
     */
    public static function saveFans($user,$appid){
        if (!empty($user['subscribe_time'])) {
            $user['subscribe_create_time'] = $user['subscribe_time'];
        }

        if (isset($user['tagid_list']) && is_array($user['tagid_list'])) {
            $user['tagid_list'] = is_array($user['tagid_list']) ? join(',', $user['tagid_list']) : '';
        }

        unset($user['privilege'], $user['groupid']);
        $data = array_merge($user,[ 'subscribe'=>'1', 'appid'=>$appid ]);
        $condition = ["openid"=>$user["openid"],"appid"=>$appid];

        if(WechatUsersModel::where($condition)->count()){
            unset($data["subscribe_create_time"]);
            return WechatUsersModel::where($condition)->save($data);
        }

        return WechatUsersModel::create($data);
    }

    /**
     * 获取公众号的黑名单列表
     * @param string $next
     * @param int $done
     * @return int|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function syncBlack($next = '', $done = 0){
        while (!is_null($next) && is_array($result = Factory::wechat()->user->getBlackList($next)) && !empty($result['data']['openid'])) {
            $done += $result['count'];
            foreach (array_chunk($result['data']['openid'], 100) as $chunk) {
                WechatUsersModel::where(['is_black' => '0'])->whereIn('openid', $chunk)->save(['is_black' => '1']);
            }

            $next = $result['total'] > $done ? $result['next_openid'] : null;
        }

        return empty($result['total']) ? 0 : $result['total'];
    }

    /**
     * 获取Tags
     * @return int
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function syncTags(){
        $count = 0;
        if (is_array($list = Factory::wechat()->user_tag->get()) && !empty($list['tags'])) {
            $count = count($list['tags']);

            WechatUsersTagsModel::where("1=1")->delete();
            WechatUsersTagsModel::insertAll($list['tags']);
            return $count;
        }

        return $count;
    }

    /**
     * 拉入黑名单
     * @param $openid
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function addBlack($openid){
        foreach (array_chunk(explode(',', $openid), 20) as $openids) {
            Factory::wechat()->user->batchBlackList($openids);
            WechatUsersModel::whereIn('openid', $openids)->save(['is_black' => '1']);
        }

        return true;
    }

    /**
     * 移出黑名单
     * @param $openid
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function removeBlack($openid){
        foreach (array_chunk(explode(',', $openid), 20) as $openids) {
            Factory::wechat()->user->batchUnBlackList($openids);
            WechatUsersModel::whereIn('openid', $openids)->save(['is_black' => '0']);
        }

        return true;
    }

    /**
     * 删除
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function delete($id){
        try{
            WechatUsersModel::startTrans();

            $row = WechatUsersModel::where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            WechatUsersModel::where("id",$id)->delete();
            if($row["user_id"] <= 0){
                WechatUsersModel::commit();
                return true;
            }

            UsersModel::where("id",$row["user_id"])->delete();
            UsersAddressModel::where("user_id",$row["user_id"])->delete();
            UsersBonusModel::where("user_id",$row["user_id"])->delete();
            UsersCommentModel::where("user_id",$row["user_id"])->delete();
            UsersConsultModel::where("user_id",$row["user_id"])->delete();
            UsersFavoriteModel::where("user_id",$row["user_id"])->delete();
            UsersLogModel::where("user_id",$row["user_id"])->delete();
            UsersRechangeModel::where("user_id",$row["user_id"])->delete();
            UsersWithdrawLogModel::where("user_id",$row["user_id"])->delete();
            $order = OrderModel::where("user_id",$row["user_id"])->select()->toArray();

            $orderId = [];
            foreach($order as $value){
                $orderId[] = $value["id"];
            }

            if(OrderModel::where("id","in",$orderId)->delete()){
                OrderGoodsModel::where("order_id","in",$orderId)->delete();
                OrderLogModel::where("order_id","in",$orderId)->delete();
            }

            OrderCollectionModel::where("user_id",$row["user_id"])->delete();
            OrderDeliveryModel::where("user_id",$row["user_id"])->delete();
            OrderRefundmentModel::where("user_id",$row["user_id"])->delete();
            OrderGroupModel::where("user_id",$row["user_id"])->delete();

            WechatUsersModel::commit();
        }catch (\Exception $ex){
            WechatUsersModel::rollback();
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }
    }

}