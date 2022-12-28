<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace xzncit\mini\Live\Goods;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

/**
 * Class Goods
 * @package xzncit\mini\Live\Goods
 * @link https://developers.weixin.qq.com/miniprogram/dev/platform-capabilities/industry/liveplayer/commodity-api.html
 */
class Goods extends App {

    private $error = [
        "-1"     => "系统错误",
        "1003"   => "商品id不存在",
        "47001"  => "入参格式不符合规范",
        "200002" => "入参错误",
        "300001" => "禁止创建/更新商品（如：商品创建功能被封禁）",
        "300002" => "名称长度不符合规则",
        "300003" => "价格输入不合规（如：现价比原价大、传入价格非数字等）",
        "300004" => "商品名称存在违规违法内容",
        "300005" => "商品图片存在违规违法内容",
        "300006" => "图片上传失败（如：mediaID过期）",
        "300007" => "线上小程序版本不存在该链接",
        "300008" => "添加商品失败",
        "300009" => "商品审核撤回失败",
        "300010" => "商品审核状态不对（如：商品审核中）",
        "300011" => "操作非法（API不允许操作非API创建的商品）",
        "300012" => "没有提审额度（每天500次提审额度）",
        "300013" => "提审失败",
        "300014" => "审核中，无法删除（非零代表失败）",
        "300017" => "商品未提审",
        "300018" => "商品图片尺寸过大",
        "300021" => "商品添加成功，审核失败"
    ];

    /**
     * 获取商品列表
     * 调用此接口可获取商品列表
     * @param $offset       分页条数起点
     * @param $limit        分页大小，默认30，不超过100
     * @param $status       商品状态，0：未审核。1：审核中，2：审核通过，3：审核驳回
     * @return array
     * @throws \Exception
     */
    public function getList($offset,$limit,$status){
        return HttpClient::create()->postJson("wxaapi/broadcast/goods/getapproved?access_token=ACCESS_TOKEN",[
            "offset"=>$offset,"limit"=>$limit,"status"=>$status
        ])->toArray();
    }

    /**
     * 商品添加并提审
     * 调用此接口上传并提审需要直播的商品信息，审核通过后商品录入【小程序直播】商品库
     * 注意：开发者必须保存【商品ID】与【审核单ID】，如果丢失，则无法调用其他相关接口
     * @param array $params
     * [
            参数	            类型	    必填	说明
            coverImgUrl	    String	是	填入mediaID（mediaID获取后，三天内有效）；图片mediaID的获取，请参考以下文档： https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/New_temporary_materials.html；图片规则：图片尺寸最大300像素*300像素；
            name	        String	是	商品名称，最长14个汉字，1个汉字相当于2个字符
            priceType	    Number	是	价格类型，1：一口价（只需要传入price，price2不传） 2：价格区间（price字段为左边界，price2字段为右边界，price和price2必传） 3：显示折扣价（price字段为原价，price2字段为现价， price和price2必传）
            price	        Number	是	数字，最多保留两位小数，单位元
            price2	        Number	否	数字，最多保留两位小数，单位元
            url	            String	是	商品详情页的小程序路径，路径参数存在 url 的，该参数的值需要进行 encode 处理再填入
            thirdPartyAppid	String	否	当商品为第三方小程序的商品则填写为对应第三方小程序的appid，自身小程序商品则为''
     * ]
     * @return array
     * @throws \Exception
     */
    public function add($params=[]){
        return HttpClient::create()->postJson("wxaapi/broadcast/goods/add?access_token=ACCESS_TOKEN",["goodsInfo"=>$params])->toArray();
    }

    /**
     * 撤回审核
     * 调用此接口，可撤回直播商品的提审申请，消耗的提审次数不返还
     * @param $auditId      审核单ID
     * @param $goodsId      商品ID
     * @return array
     * @throws \Exception
     */
    public function resetAudit($auditId,$goodsId){
        return HttpClient::create()->postJson("wxaapi/broadcast/goods/resetaudit?access_token=ACCESS_TOKEN",[
            "auditId"=>$auditId,"goodsId"=>$goodsId
        ])->toArray();
    }

    /**
     * 重新提交审核
     * 调用此接口可以对已撤回提审的商品再次发起提审申请
     * @param $goodsId      商品ID
     * @return array
     * @throws \Exception
     */
    public function audit($goodsId){
        return HttpClient::create()->postJson("wxaapi/broadcast/goods/resetaudit?access_token=ACCESS_TOKEN",[
            "goodsId"=>$goodsId
        ])->toArray();
    }

    /**
     * 删除商品
     * 调用此接口，可删除【小程序直播】商品库中的商品，删除后直播间上架的该商品也将被同步删除，不可恢复；
     * @param $goodsId
     * @return array
     * @throws \Exception
     */
    public function delete($goodsId){
        return HttpClient::create()->postJson("wxaapi/broadcast/goods/delete?access_token=ACCESS_TOKEN",[
            "goodsId"=>$goodsId
        ])->toArray();
    }

    /**
     * 更新商品
     * 调用此接口可以更新商品信息，审核通过的商品仅允许更新价格类型与价格，审核中的商品不允许更新，未审核的商品允许更新所有字段， 只传入需要更新的字段。
     * @param $params
     * [
            参数	                类型	    必填	 说明
            coverImgUrl	        String	是	 填入mediaID（mediaID获取后，三天内有效）；图片mediaID的获取，请参考以下文档： https://developers.weixin.qq.com/doc/offiaccount/Asset_Management/New_temporary_materials.html；图片规则：图片尺寸最大300像素*300像素；
            name	            String	是	 商品名称，最长14个汉字，1个汉字相当于2个字符
            priceType	        Number	是	 价格类型，1：一口价（只需要传入price，price2不传） 2：价格区间（price字段为左边界，price2字段为右边界，price和price2必传） 3：显示折扣价（price字段为原价，price2字段为现价， price和price2必传）
            price	            Number	是	 数字，最多保留两位小数，单位元
            price2	            Number	否	 数字，最多保留两位小数，单位元
            url	                String	是	 商品详情页的小程序路径，路径参数存在 url 的，该参数的值需要进行 encode 处理再填入
            goodsId	            Number	是	 商品ID
            thirdPartyAppid	    String	否	 当商品为第三方小程序的商品则填写为对应第三方小程序的appid，自身小程序商品则为''
     * ]
     * @return array
     * @throws \Exception
     */
    public function update($params){
        return HttpClient::create()->postJson("wxaapi/broadcast/goods/update?access_token=ACCESS_TOKEN",["goodsInfo"=>$params])->toArray();
    }

    /**
     * 获取商品状态
     * 调用此接口可获取商品的信息与审核状态
     * @param $goods_ids
     * @return array
     * @throws \Exception
     */
    public function getGoodsWarehouse($goods_ids){
        return HttpClient::create()->postJson("wxa/business/getgoodswarehouse?access_token=ACCESS_TOKEN",[
            "goods_ids"=>$goods_ids
        ])->toArray();
    }

    /**
     * 获取错误信息
     * @param $code
     * @return string
     */
    public function getError($code){
        if($code == 0) return "ok";
        return isset($this->error[$code]) ? $this->error[$code] : "未知错误";
    }

}