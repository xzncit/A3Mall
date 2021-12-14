<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\controller;

use app\common\exception\BaseException;
use think\facade\Request;
use app\api\service\Ucenter as UcenterService;
use app\api\service\Address as AddressService;
use app\api\service\PaymentOrder as PaymentOrderService;

class Ucenter extends Base {

    /**
     * 收藏列表
     * @return \think\response\Json
     */
    public function favorite(){
        try{
            return $this->returnAjax("ok",1,UcenterService::getFavoriteList(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

    /**
     * 删除收藏
     * @return \think\response\Json
     */
    public function favorite_delete(){
        try{
            UcenterService::favoriteDelete(Request::param("id",""));
            return $this->returnAjax("ok",1);
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),0);
        }
    }

    /**
     * 优惠劵
     * @return \think\response\Json
     */
    public function coupon(){
        try{
            return $this->returnAjax("ok",1,UcenterService::getCouponList(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

    /**
     * 积分列表
     * @return \think\response\Json
     */
    public function point(){
        try{
            return $this->returnAjax("ok",1,UcenterService::getPointList(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

    /**
     * 获取会员信息
     * @return \think\response\Json
     * @throws \Exception
     */
    public function info(){
        return $this->returnAjax("ok",1,UcenterService::getUsersInfo());
    }

    /**
     * 我的钱包
     * @return \think\response\Json
     */
    public function wallet(){
        return $this->returnAjax("ok",1,UcenterService::wallet());
    }

    /**
     * 获取会员资料
     * @return \think\response\Json
     */
    public function get_setting(){
        return $this->returnAjax("ok",1,UcenterService::getSetting());
    }

    /**
     * 保存会员设置
     * @return \think\response\Json
     */
    public function setting(){
        try{
            UcenterService::settingSave(Request::param());
            return $this->returnAjax("会员资料更新成功");
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),0);
        }
    }

    /**
     * 地址详细信息
     * @return \think\response\Json
     */
    public function address(){
        try{
            return $this->returnAjax("ok",1,AddressService::detail(Request::param("id","","intval")));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 获取地址列表
     * @return \think\response\Json
     */
    public function address_list(){
        return $this->returnAjax("ok",1,AddressService::getList());
    }

    /**
     * 删除地址
     * @return \think\response\Json
     */
    public function address_delete(){
        AddressService::delete(Request::param("id","","intval"));
        return $this->returnAjax("ok");
    }

    /**
     * 编辑地址
     * @return \think\response\Json
     */
    public function address_editor(){
        try{
            return $this->returnAjax("操作成功",1,AddressService::editor(Request::param()));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 设置默认地址
     * @return \think\response\Json
     */
    public function set_default_address(){
        AddressService::setDefaultAddress(Request::param("id","0","intval"));
        return $this->returnAjax("ok",1);
    }

    /**
     * 获取帮助内容
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function help(){
        return $this->returnAjax("ok",1, UcenterService::getHelpList());
    }

    /**
     * 资金明细
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function fund(){
        try{
            return $this->returnAjax("ok",1,UcenterService::getFundList(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

    /**
     * 提现记录
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function cashlist(){
        try{
            return $this->returnAjax("ok",1,UcenterService::getCashList(Request::param()));
        }catch (BaseException $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode(),$ex->getRaw());
        }
    }

    /**
     * 充值
     * @return \think\response\Json
     */
    public function rechange(){
        try{
            return $this->returnAjax("ok",1,PaymentOrderService::recharge(Request::param()));
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 获取充值金额
     * @return \think\response\Json
     */
    public function rechange_price(){
        return $this->returnAjax('ok',1,UcenterService::rechangePrice());
    }

    /**
     * 申请提现
     * @return \think\response\Json
     */
    public function settlement(){
        return $this->returnAjax("ok",1,UcenterService::settlement());
    }

    /**
     * 提交提现申请
     * @return \think\response\Json
     */
    public function settlement_save(){
        try{
            UcenterService::settlementSave(Request::post());
            return $this->returnAjax("申请提现成功，请等待管理员审核");
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),0);
        }
    }

    /**
     * 上传头像
     * @return \think\response\Json
     */
    public function avatar() {
        try{
            return $this->returnAjax("ok",1,UcenterService::upload());
        }catch (\Exception $ex){
            return $this->returnAjax($ex->getMessage(),$ex->getCode());
        }
    }

}