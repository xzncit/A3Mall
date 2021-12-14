<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;

use app\common\exception\BaseException;
use app\common\library\utils\Image;
use app\common\models\Attachments as AttachmentsModel;
use app\common\models\goods\Goods as GoodsModel;
use app\common\models\chat\ChatService as ChatServiceModel;
use app\common\models\chat\ChatMessage as ChatMessageModel;
use GatewayWorker\Lib\Gateway;
use mall\basic\Users;
use mall\utils\CString;
use mall\utils\Tool;
use think\facade\Config;
use app\common\service\upload\Uploadfiy as UploadfiyService;

class Chat extends Service {

    public static function getList($data){
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;

        $count = ChatServiceModel::where('is_del',0)->where("status",0)->count();
        $result = ChatServiceModel::field("id,nickname,avatar")->where(["is_del"=>0,"status"=>0])->page($page,$size)->select()->toArray();

        $array = [ "list"=>array_map(function ($rs){
            $rs["avatar"] = Image::avatar($rs["avatar"]);
            $rs["count"] = ChatMessageModel::where(["send_type"=>0,"service_id"=>$rs["id"],"user_id"=>Users::get("id"),"is_read"=>0])->count();
            $message = ChatMessageModel::where(["service_id"=>$rs["id"],"user_id"=>Users::get("id")])->order("id","desc")->find();
            if(!empty($message)){
                switch($message["type"]){
                    case "image":
                        $rs["message"] = "[图片]";
                        break;
                    case "order":
                        $rs["message"] = "[订单推送]";
                        break;
                    case "goods":
                        $rs["message"] = "[商品推送]";
                        break;
                    case "text":
                        $rs["message"] = CString::msubstr($message["content"],50,false);
                        break;
                }
                $rs["last_time"] = date("H:i:s",strtotime($message["create_time"]));
            }
            return $rs;
        },$result), "page"=>$page, "total"=>0, "size"=>$size ];

        $total = ceil($count / $size);
        $array["total"] = $total;
        if($total == $page -1){
            throw new BaseException("没有数据了哦！",-1,$array);
        }

        return $array;
    }

    /**
     * 获取消息数据
     * @param $data
     * @return array
     * @throws BaseException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getMessageList($data){
        $size = Config::get("website.pageSize");
        $page = $data["page"]??1;
        $kf_id = intval($data["kf_id"])??0;

        if(empty($kf_id)){
            throw new BaseException("ok",-1,[]);
        }

        $condition = "(service_id=" . $kf_id . " AND user_id=" . Users::get("id") . ")";
        if($page > 0){
            $condition .= " AND id < " . $page;
        }

        $result = ChatMessageModel::where($condition)->page($page,$size)->order("id","desc")->select()->toArray();
        $service = ChatServiceModel::where("id",$kf_id)->find();

        $data = [];
        foreach($result as $key=>$item){
            switch($item["type"]){
                case "goods":
                    $goods = GoodsModel::where(["status"=>0,"id"=>$item["goods_id"]])->find();
                    if(!empty($goods)){
                        $data[] = [
                            "id"=>$item["id"],
                            "type"=>"goods",
                            "avatar"=>Image::avatar($item["send_type"] == 1 ? Users::get("avatar") : $service["avatar"]),
                            "goods_id"=>$item["goods_id"],
                            "sClass"=>$item["send_type"] == 1 ? "r" : "l",
                            "goods"=>[
                                "id"=>$goods["id"],
                                "goods_name"=>$goods["title"],
                                "sell_price"=>$goods["sell_price"],
                                "store_nums"=>$goods["store_nums"],
                                "sale"=>$goods["sale"],
                                "photo"=>Tool::thumb($goods["photo"],"medium",true)
                            ]
                        ];
                    }
                    break;
                case "text":
                    $data[] = [
                        "id"=>$item["id"],
                        "type"=>"text",
                        "avatar"=>Image::avatar($item["send_type"] == 1 ? Users::get("avatar") : $service["avatar"]),
                        "content"=>$item["content"],
                        "sClass"=>$item["send_type"] == 1 ? "r" : "l"
                    ];
                    break;
                case "image":
                    $data[] = [
                        "id"=>$item["id"],
                        "type"=>"image",
                        "avatar"=>Image::avatar($item["send_type"] == 1 ? Users::get("avatar") : $service["avatar"]),
                        "content"=>Tool::thumb($item["content"],null,true),
                        "sClass"=>$item["send_type"] == 1 ? "r" : "l"
                    ];
                    break;
            }
        }

        return array_reverse($data);
    }

    /**
     * 更新信息状态
     * @param $kf_id
     * @return bool
     * @throws \Exception
     */
    public static function updateMessage($kf_id){
        if(empty($kf_id)){
            throw new \Exception("ok",0);
        }

        ChatMessageModel::where(["service_id"=>$kf_id,"user_id"=>Users::get("id"),"send_type"=>0])->update([ "is_read"=>1 ]);
        return true;
    }

    /**
     * 上传图片
     * @param $kf_id
     * @return bool
     * @throws \Exception
     */
    public static function upload($kf_id){
        $client_id = Gateway::getClientIdByUid($kf_id."@".Users::get("id"));
        if(empty($client_id[0])){
            throw new \Exception("您还没有登录，请先登录。",0);
        }

        $dataInfo = UploadfiyService::upload("file",true,"public",["jpg","png","gif","jpeg"]);

        AttachmentsModel::create(array_merge($dataInfo,[
            "module"=>'chat',
            "method"=>'',
            "cat_id"=>'0'
        ]));

        $session = Gateway::getSession($client_id[0]);
        $message = [
            "type"=>"image",
            "service_id"=>$session["service_id"],
            "content"=>Tool::thumb('/'.trim($dataInfo["path"],"/"))
        ];

        $create_time = time();
        $message["id"] = ChatMessageModel::create(array_merge($message,[
            "send_type"=>1,
            "user_id"=>Users::get("id"),"is_read"=>0,"create_time"=>$create_time
        ]))->id;

        $message["content"] = Tool::thumb('/'.trim($dataInfo["path"],"/"),null,true);
        $message["avatar"] = Image::avatar(Users::get("avatar"));

        Gateway::sendToClient($client_id[0],json_encode(["type"=>"say","info"=>"ok","status"=>1,"data"=>array_merge($message,["sClass"=>"r"])],JSON_UNESCAPED_UNICODE));
        if(Gateway::isUidOnline("kf_".$session["service_id"])){
            Gateway::sendToUid("kf_".$session["service_id"],json_encode(["type"=>"say","info"=>"ok","status"=>1,"data"=>array_merge($message,[
                "user_id"=>$session["user_id"],"create_time"=>$create_time,"sClass"=>"l"
            ])],JSON_UNESCAPED_UNICODE));
        }

        return true;
    }

}