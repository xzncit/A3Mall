<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller\common;

use app\admin\controller\Auth;
use mall\response\Response;
use think\facade\Request;
use think\facade\View;
use app\admin\service\users\Users as UsersService;
use app\admin\service\products\Goods as GoodsService;
use app\admin\service\products\Area as AreaService;
use app\admin\service\products\Attribute as AttributeService;
use app\admin\service\products\Model as ModelService;

class Ajax extends Auth {

    /**
     * 获取未成为分销会员列表
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get_users(){
        if(Request::isAjax()){
            $list = UsersService::getList(Request::param(),["users.is_spread"=>0]);
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch("common/get_users",UsersService::getSearchData());
    }

    /**
     * 获取商品列表
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get_goods(){
        if(Request::isAjax()){
            $list = GoodsService::getList(Request::param(),"");
            return Response::returnArray("ok",0,$list["data"],$list["count"]);
        }

        return View::fetch("common/get_goods",GoodsService::getSearchData());
    }

    /**
     * 获取商品规格
     * @return \think\response\Json
     */
    public function get_goods_data(){
        try{
            return Response::returnArray("ok",1,GoodsService::getGoodsItemData(Request::param("id",0,"intval")));
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 获取商品列表
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get_goods_list(){
        if(Request::isAjax()){
            $list = GoodsService::getList(Request::param(),"");
            return Response::returnArray("ok",0,$list["data"],$list["count"]);
        }

        return View::fetch("common/get_goods_list",GoodsService::getSearchData());
    }

    /**
     * 获取商品列表
     * @return \think\response\Json
     */
    public function get_goods_list_data(){
        try{
            $result = GoodsService::getGoodsListData(Request::param("id", "0","trim,strip_tags"));
            $html = View::fetch("common/get_goods_list_data",[ "data"=>$result["data"] ]);
            return Response::returnArray("ok",1,[ "content"=>$html, "goods_id"=>implode(",",$result["goods_id"]) ]);
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 获取省级地区列表
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get_area(){
        return Response::returnArray('ok',1,AreaService::getArea(Request::get("id","0","intval")));
    }

    /**
     * 获取所有地址信息
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get_distribution(){
        return View::fetch('common/get_distribution',AreaService::getDistribution());
    }

    /**
     * 获取规格
     * @return \think\response\Json
     */
    public function get_attr(){
        $attribute = AttributeService::getAttr(Request::param());
        $html = View::fetch("common/get_attr",[ "spec_checked"=>$attribute["spec_checked"],"result"=>$attribute["result"] ]);
        return Response::returnArray("ok",1,$html);
    }

    /**
     * 生成规格数据
     * @return \think\response\Json
     */
    public function get_attr_data(){
        try{
            $html = View::fetch("common/get_attr_data",AttributeService::getAttrData(Request::param()));
            return Response::returnArray("ok", 1, $html);
        }catch (\Exception $ex){
            return Response::returnArray("ok", 1,"");
        }
    }

    /**
     * 获取商品参数
     * @return \think\response\Json
     */
    public function get_model(){
        try{
            $html = View::fetch("common/get_model",ModelService::getModel(Request::param()));
            return Response::returnArray("ok",1,$html);
        }catch (\Exception $ex){
            return Response::returnArray($ex->getMessage(),0);
        }
    }

    /**
     * 获取门店核销会员
     * @return string|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get_shop_users(){
        if(Request::isAjax()){
            $list = UsersService::getList(Request::param());
            return Response::returnArray("ok",0,$list['data'],$list['count']);
        }

        return View::fetch("common/get_shop_users",UsersService::getSearchData());
    }

}