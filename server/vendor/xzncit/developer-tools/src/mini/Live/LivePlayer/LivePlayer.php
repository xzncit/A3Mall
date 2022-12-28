<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\mini\Live\LivePlayer;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

/**
 * Class LivePlayer
 * @package xzncit\mini\Live\LivePlayer
 * @link https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/liveplayer/studio-api.html
 */
class LivePlayer extends App {

    private $error = [
        "-1"=>"系统错误",
        "1"=>"未创建直播间",
        "1003"=>"商品id不存在",
        "47001"=>"入参格式不符合规范",
        "200002"=>"入参错误",
        "300001"=>"禁止创建/更新商品 或 禁止编辑&更新房间",
        "300002"=>"名称长度不符合规则",
        "300006"=>"图片上传失败（如: mediaID过期）",
        "300022"=>"此房间号不存在",
        "300023"=>"房间状态 拦截（当前房间状态不允许此操作）",
        "300024"=>"商品不存在",
        "300025"=>"商品审核未通过",
        "300026"=>"房间商品数量已经满额",
        "300027"=>"导入商品失败",
        "300028"=>"房间名称违规",
        "300029"=>"主播昵称违规",
        "300030"=>"主播微信号不合法",
        "300031"=>"直播间封面图不合规",
        "300032"=>"直播间分享图违规",
        "300033"=>"添加商品超过直播间上限",
        "300034"=>"主播微信昵称长度不符合要求",
        "300035"=>"主播微信号不存在",
        "300036"=>"主播微信号未实名认证",
        "300037"=>"购物直播频道封面图不合规",
        "300038"=>"未在小程序管理后台配置客服",
        "300039"=>"主播副号微信号不合法",
        "300040"=>"名称含有非限定字符（含有特殊字符）",
        "300041"=>"创建者微信号不合法",
        "300042"=>"推流中禁止编辑房间",
        "300043"=>"每天只允许一场直播开启关注",
        "300044"=>"商品没有讲解视频",
        "300045"=>"讲解视频未生成",
        "300046"=>"讲解视频生成失败",
        "300047"=>"已有商品正在推送，请稍后再试",
        "300048"=>"拉取商品列表失败",
        "300049"=>"商品推送过程中不允许上下架",
        "300050"=>"排序商品列表为空",
        "300051"=>"解析JSON出错",
        "300052"=>"已下架的商品无法推送",
        "300053"=>"直播间未添加此商品",
        "500001"=>"副号不合规",
        "500002"=>"副号未实名",
        "500003"=>"已经设置过副号了，不能重复设置",
        "500004"=>"不能设置重复的副号",
        "500005"=>"副号不能和主号重复",
        "600001"=>"用户已被添加为小助手",
        "600002"=>"找不到用户",
        "9410000"=>"直播间列表为空",
        "9410001"=>"获取房间失败",
        "9410002"=>"获取商品失败",
        "9410003"=>"获取回放失败"
    ];

    /**
     * 创建直播间
     * 调用此接口创建直播间，创建成功后将在直播间列表展示
     * 调用额度：10000次/一天
     * @param $params
     [
        参数	                类型	    必填	    说明
        name	            String	是	    直播间名字，最短3个汉字，最长17个汉字，1个汉字相当于2个字符
        coverImg	        String	是	    背景图，填入mediaID（mediaID获取后，三天内有效）；图片mediaID的获取，请参考以下文档： https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/New_temporary_materials.html；直播间背景图，图片规则：建议像素1080*1920，大小不超过2M
        startTime	        Number	是	    直播计划开始时间（开播时间需要在当前时间的10分钟后 并且 开始时间不能在 6 个月后）
        endTime	            Number	是	    直播计划结束时间（开播时间和结束时间间隔不得短于30分钟，不得超过24小时）
        anchorName	        String	是	    主播昵称，最短2个汉字，最长15个汉字，1个汉字相当于2个字符
        anchorWechat	    String	是	    主播微信号，如果未实名认证，需要先前往“小程序直播”小程序进行实名验证, 小程序二维码链接：https://res.wx.qq.com/op_res/9rSix1dhHfK4rR049JL0PHJ7TpOvkuZ3mE0z7Ou_Etvjf-w1J_jVX0rZqeStLfwh
        subAnchorWechat	    String	否	    主播副号微信号，如果未实名认证，需要先前往“小程序直播”小程序进行实名验证, 小程序二维码链接：https://res.wx.qq.com/op_res/9rSix1dhHfK4rR049JL0PHJ7TpOvkuZ3mE0z7Ou_Etvjf-w1J_jVX0rZqeStLfwh
        createrWechat	    String	否	    创建者微信号，不传入则此直播间所有成员可见。传入则此房间仅创建者、管理员、超管、直播间主播可见
        shareImg	        String	是	    分享图，填入mediaID（mediaID获取后，三天内有效）；图片mediaID的获取，请参考以下文档： https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/New_temporary_materials.html；直播间分享图，图片规则：建议像素800*640，大小不超过1M；
        feedsImg	        String	是	    购物直播频道封面图，填入mediaID（mediaID获取后，三天内有效）；图片mediaID的获取，请参考以下文档： https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/New_temporary_materials.html; 购物直播频道封面图，图片规则：建议像素800*800，大小不超过100KB；
        isFeedsPublic	    Number	否	    是否开启官方收录 【1: 开启，0：关闭】，默认开启收录
        type	            Number	是	    直播间类型 【1: 推流，0：手机直播】
        closeLike	        Number	是	    是否关闭点赞 【0：开启，1：关闭】（若关闭，观众端将隐藏点赞按钮，直播开始后不允许开启）
        closeGoods	        Number	是	    是否关闭货架 【0：开启，1：关闭】（若关闭，观众端将隐藏商品货架，直播开始后不允许开启）
        closeComment	    Number	是	    是否关闭评论 【0：开启，1：关闭】（若关闭，观众端将隐藏评论入口，直播开始后不允许开启）
        closeReplay	        Number	否	    是否关闭回放 【0：开启，1：关闭】默认关闭回放（直播开始后允许开启）
        closeShare	        Number	否	    是否关闭分享 【0：开启，1：关闭】默认开启分享（直播开始后不允许修改）
        closeKf	            Number	否	    是否关闭客服 【0：开启，1：关闭】 默认关闭客服（直播开始后允许开启）
     ]
     * @return array
     * @throws \Exception
     */
    public function create($params=[]){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/create?access_token=ACCESS_TOKEN",$params)->toArray();
    }

    /**
     * 获取直播间列表
     * 调用此接口获取直播间列表及直播间信息
     * 调用额度：100000次/一天（与获取回放接口共用次数）
     * @param int $start    起始房间，0表示从第1个房间开始拉取
     * @param int $limit    每次拉取的房间数量，建议100以内
     * @return array
     * @throws \Exception
     */
    public function getLiveInfo($start=0,$limit=10){
        return HttpClient::create()->postJson("wxa/business/getliveinfo?access_token=ACCESS_TOKEN",[
            "start"=>$start,"limit"=>$limit
        ])->toArray();
    }

    /**
     * 获取直播间回放
     * 调用接口获取已结束直播间的回放源视频（一般在直播结束后10分钟内生成，源视频无评论等内容）
     * 调用额度：100000次/一天
     * @param int $room_id
     * @param int $start
     * @param int $limit
     * @param string $action
     * @return array
     * @throws \Exception
     */
    public function getVideoLiveBack($room_id=0,$start=0,$limit=10,$action="get_replay"){
        return HttpClient::create()->postJson("wxa/business/getliveinfo?access_token=ACCESS_TOKEN",[
            "start"=>$start,"limit"=>$limit,"room_id"=>$room_id,"action"=>$action
        ])->toArray();
    }

    /**
     * 直播间导入商品
     * 调用接口往指定直播间导入已入库的商品
     * 调用额度：10000次/一天
     * @param $roomId
     * @param array $ids
     * @return array
     * @throws \Exception
     */
    public function addGoods($roomId,$ids=[]){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/addgoods?access_token=ACCESS_TOKEN",[
            "ids"=>$ids,"roomId"=>$roomId
        ])->toArray();
    }

    /**
     * 删除直播间
     * @param $id   房间ID
     * @return array
     * @throws \Exception
     */
    public function deleteLive($id){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/deleteroom?access_token=ACCESS_TOKEN",[
            "id"=>$id
        ])->toArray();
    }

    /**
     * 编辑直播间
     * 调用额度：10000次/一天
     * @param array $params [
        参数	            类型	    必填	说明
        id	            Number	是	直播间id
        name	        String	是	直播间名字，最短3个汉字，最长17个汉字，1个汉字相当于2个字符
        coverImg	    String	是	背景图，填入mediaID（mediaID获取后，三天内有效）；图片mediaID的获取，请参考以下文档： https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/New_temporary_materials.html；直播间背景图，图片规则：建议像素1080*1920，大小不超过2M
        startTime	    Number	是	直播计划开始时间（开播时间需要在当前时间的10分钟后 并且 开始时间不能在 6 个月后）
        endTime	        Number	是	直播计划结束时间（开播时间和结束时间间隔不得短于30分钟，不得超过24小时）
        anchorName	    String	是	主播昵称，最短2个汉字，最长15个汉字，1个汉字相当于2个字符
        anchorWechat	String	是	主播微信号，如果未实名认证，需要先前往“小程序直播”小程序进行实名验证, 小程序二维码链接：https://res.wx.qq.com/op_res/9rSix1dhHfK4rR049JL0PHJ7TpOvkuZ3mE0z7Ou_Etvjf-w1J_jVX0rZqeStLfwh
        shareImg	    String	是	分享图，填入mediaID（mediaID获取后，三天内有效）；图片mediaID的获取，请参考以下文档： https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/New_temporary_materials.html；直播间分享图，图片规则：建议像素800*640，大小不超过1M；
        feedsImg	    String	是	购物直播频道封面图，填入mediaID（mediaID获取后，三天内有效）；图片mediaID的获取，请参考以下文档： https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/New_temporary_materials.html; 购物直播频道封面图，图片规则：建议像素800*800，大小不超过100KB；
        isFeedsPublic	Number	否	是否开启官方收录 【1: 开启，0：关闭】，默认开启收录
        closeLike	    Number	是	是否关闭点赞 【0：开启，1：关闭】（若关闭，观众端不展示点赞入口，直播开始后不允许开启）
        closeGoods	    Number	是	是否关闭货架 【0：开启，1：关闭】（若关闭，观众端不展示商品货架，直播开始后不允许开启）
        closeComment	Number	是	是否关闭评论 【0：开启，1：关闭】（若关闭，观众端不展示评论入口，直播开始后不允许开启）
        closeReplay	    Number	否	是否关闭回放 【0：开启，1：关闭】默认关闭回放（直播开始后允许开启）
        closeShare	    Number	否	是否关闭分享 【0：开启，1：关闭】默认开启分享（直播开始后不允许修改）
        closeKf	        Number	否	是否关闭客服 【0：开启，1：关闭】 默认关闭客服（直播开始后允许开启）
     ]
     * @return array
     * @throws \Exception
     */
    public function editLive($params=[]){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/editroom?access_token=ACCESS_TOKEN",$params)->toArray();
    }

    /**
     * 获取直播间推流地址
     * 调用额度：10000次/一天
     * @param $roomId   房间ID
     * @return array
     * @throws \Exception
     */
    public function getPushUrl($roomId){
        return HttpClient::create()->get("wxaapi/broadcast/room/getpushurl?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId
        ])->toArray();
    }

    /**
     * 获取直播间分享二维码
     * 调用额度：10000次/一天
     * @param $roomId           房间ID
     * @param array $params     自定义参数
     * @return array
     * @throws \Exception
     */
    public function getSharedCode($roomId,$params=[]){
        return HttpClient::create()->get("wxaapi/broadcast/room/getpushurl?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"params"=>urlencode(json_encode($params,JSON_UNESCAPED_UNICODE))
        ])->toArray();
    }

    /**
     * 添加管理直播间小助手
     * @param $roomId       房间ID
     * @param array $users  用户数组，支持一维数组和二维数组
     * [
     *      "username"=>"","nickname"=>""
     * ]
     * @return array
     * @throws \Exception
     */
    public function addAssistant($roomId,$users=[]){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/addassistant?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"users"=>count($users)==count($users,COUNT_RECURSIVE)?[$users]:$users
        ])->toArray();
    }

    /**
     * 修改管理直播间小助手
     * 调用额度：10000次/一天
     * @param $roomId       房间ID
     * @param $username     用户微信号
     * @param $nickname     用户微信昵称
     * @return array
     * @throws \Exception
     */
    public function updateAssistant($roomId,$username,$nickname){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/modifyassistant?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"username"=>$username,"nickname"=>$nickname
        ])->toArray();
    }

    /**
     * 删除管理直播间小助手
     * 调用额度：10000次/一天
     * @param $roomId       房间ID
     * @param $username     用户微信号
     * @return array
     * @throws \Exception
     */
    public function deleteAssistant($roomId,$username){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/modifyassistant?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"username"=>$username
        ])->toArray();
    }

    /**
     * 查询管理直播间小助手
     * 调用额度：10000次/一天
     * @param $roomId       房间ID
     * @return array
     * @throws \Exception
     */
    public function getAssistantList($roomId){
        return HttpClient::create()->get("wxaapi/broadcast/room/getassistantlist?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId
        ])->toArray();
    }

    /**
     * 添加主播副号
     * @param $roomId       房间ID
     * @param $username     用户微信号
     * @return array
     * @throws \Exception
     */
    public function addSubAnchor($roomId,$username){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/addsubanchor?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"username"=>$username
        ])->toArray();
    }

    /**
     * 修改主播副号
     * @param $roomId       房间ID
     * @param $username     用户微信号
     * @return array
     * @throws \Exception
     */
    public function updateSubAnchor($roomId,$username){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/modifysubanchor?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"username"=>$username
        ])->toArray();
    }

    /**
     * 删除主播副号
     * @param $roomId       房间ID
     * @return array
     * @throws \Exception
     */
    public function deleteSubAnchor($roomId){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/deletesubanchor?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId
        ])->toArray();
    }

    /**
     * 获取主播副号
     * @param $roomId       房间ID
     * @return array
     * @throws \Exception
     */
    public function getSubAnchor($roomId){
        return HttpClient::create()->get("wxaapi/broadcast/room/getsubanchor?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId
        ])->toArray();
    }

    /**
     * 开启/关闭直播间官方收录
     * @param $roomId           房间ID
     * @param $isFeedsPublic    是否开启官方收录 【1: 开启，0：关闭】
     * @return array
     * @throws \Exception
     */
    public function updateFeedPublic($roomId,$isFeedsPublic){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/updatefeedpublic?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"isFeedsPublic"=>$isFeedsPublic
        ])->toArray();
    }

    /**
     * @param $roomId           房间ID
     * @param $closeReplay      是否关闭回放 【0：开启，1：关闭】
     * @return array
     * @throws \Exception
     */
    public function updateReplay($roomId,$closeReplay){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/updatereplay?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"closeReplay"=>$closeReplay
        ])->toArray();
    }

    /**
     * 开启/关闭客服功能
     * @param $roomId       房间ID
     * @param $closeKf      是否关闭客服 【0：开启，1：关闭】
     * @return array
     * @throws \Exception
     */
    public function updateKF($roomId,$closeKf){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/updatekf?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"closeKf"=>$closeKf
        ])->toArray();
    }

    /**
     * 开启/关闭直播间全局禁言
     * @param $roomId       房间ID
     * @param $banComment   1-禁言，0-取消禁言
     * @return array
     * @throws \Exception
     */
    public function updateComment($roomId,$banComment){
        return HttpClient::create()->postJson("wxaapi/broadcast/room/updatecomment?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"banComment"=>$banComment
        ])->toArray();
    }

    /**
     * 上下架商品
     * @param $roomId       房间ID
     * @param $goodsId      商品ID
     * @param $onSale       上下架 【0：下架，1：上架】
     * @return array
     * @throws \Exception
     */
    public function onsaleGoods($roomId,$goodsId,$onSale){
        return HttpClient::create()->postJson("wxaapi/broadcast/goods/onsale?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"goodsId"=>$goodsId,"onSale"=>$onSale
        ])->toArray();
    }

    /**
     * 删除直播间商品
     * @param $roomId       房间ID
     * @param $goodsId      商品ID
     * @return array
     * @throws \Exception
     */
    public function deleteInRoomGoods($roomId,$goodsId){
        return HttpClient::create()->postJson("wxaapi/broadcast/goods/deleteInRoom?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"goodsId"=>$goodsId
        ])->toArray();
    }

    /**
     * 推送商品
     * @param $roomId       房间ID
     * @param $goodsId      商品ID
     * @return array
     * @throws \Exception
     */
    public function pushGoods($roomId,$goodsId){
        return HttpClient::create()->postJson("wxaapi/broadcast/goods/push?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"goodsId"=>$goodsId
        ])->toArray();
    }

    /**
     * 直播间商品排序
     * @param $roomId       房间ID
     * @param $goodsId      商品ID列表
     * @return array
     * @throws \Exception
     */
    public function sortGoods($roomId,$goodsId){
        return HttpClient::create()->postJson("wxaapi/broadcast/goods/sort?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"goodsId"=>$goodsId
        ])->toArray();
    }

    /**
     * 下载商品讲解视频
     * @param $roomId       房间ID
     * @param $goodsId      商品ID
     * @return array
     * @throws \Exception
     */
    public function getVideoGoods($roomId,$goodsId){
        return HttpClient::create()->postJson("wxaapi/broadcast/goods/getVideo?access_token=ACCESS_TOKEN",[
            "roomId"=>$roomId,"goodsId"=>$goodsId
        ])->toArray();
    }

    /**
     * 获取直播间错误信息
     * @param $code
     * @return string
     */
    public function getError($code){
        return isset($this->error[$code]) ? $this->error[$code] : '未知错误';
    }

}