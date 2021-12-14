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
use app\common\models\wechat\WechatQrcode as WechatQrcodeModel;
use app\common\models\users\Users as UsersModel;
use mall\utils\Tool;

class Qrcode extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = WechatQrcodeModel::count();
        $result = WechatQrcodeModel::page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray();

        foreach ($result as $k=>$v){
            $user = UsersModel::where("id",$v['user_id'])->find();
            $list['data'][$k]['image'] = Tool::thumb($v['path']);
            $list['data'][$k]['username'] = $user["username"];
            $list['data'][$k]['nickname'] = $user["nickname"];
        }

        return [ "count"=>$count, "data"=>$result ];
    }

    /**
     * 删除
     * @param $id
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function delete($id){
        $row = WechatQrcodeModel::where('id',$id)->find();
        if(empty($row)){
            throw new \Exception("您要查找的数据不存在！",0);
        }

        WechatQrcodeModel::where('id',$id)->delete();
        Tool::deleteFile(Tool::getRootPath() . 'public' . $row['path']);
        return true;
    }

    /**
     * 清空
     * @return bool
     */
    public static function clear(){
        WechatQrcodeModel::where("1=1")->delete();
        Tool::deleteFile(Tool::getRootPath() . 'public/uploads/qrcode');
        return true;
    }

}