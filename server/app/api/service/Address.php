<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;

use mall\basic\Users;
use app\common\models\users\UsersAddress as UsersAddressModel;
use app\common\models\Area as AreaModel;
use mall\utils\Check;

class Address extends Service {

    /**
     * 获取指定会员所有地址
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList(){
        $data = UsersAddressModel::where([ "user_id"=>Users::get("id") ])->select()->toArray();

        $list = [];
        foreach($data as $key=>$item){
            $area = [];
            foreach([$item["province"],$item["city"],$item["area"]] as $value){
                $area[] = AreaModel::where("id",$value)->value("name");
            }

            $list[$key] = [
                "id"=>$item["id"],
                "is_default"=>$item['is_default'],
                "name"=>$item["accept_name"],
                "tel"=>$item["mobile"],
                "address"=>implode(" ", $area) . $item["address"]
            ];
        }

        return $list;
    }

    /**
     * 详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id){
        if(!$row = UsersAddressModel::where([ "user_id"=>Users::get("id"), "id"=>$id ])->find()){
            throw new \Exception("address empty",0);
        }

        $extends_info = json_decode($row["extends_info"],true);

        return [
            "areaCode"=>$extends_info["areaCode"],
            "isDefault"=>$row["is_default"] ? true : false,
            "name"=> $row["accept_name"],
            "tel"=>$row["mobile"],
            "addressDetail"=>$row["address"],
            "province"=>$row["province"],
            "county"=>$row["city"],
            "city"=>$row["area"],
            "area_name"=>AreaModel::getArea([$row["province"],$row["city"],$row["area"]],',')
        ];
    }

    /**
     * 删除地址
     * @param $id
     * @return bool
     */
    public static function delete($id){
        return UsersAddressModel::where([ "user_id"=>Users::get("id"), "id"=>$id ])->delete();
    }

    /**
     * 编辑收货地址
     * @param $post
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function editor($post){
        if(empty($post["name"])){
            throw new \Exception("请填写姓名！",0);
        }else if(!Check::chsAlphaNum($post["name"])){
            throw new \Exception("您填写的姓名不合法！",0);
        }else if(!Check::mobile($post["tel"])){
            throw new \Exception("您填写的手机号码不正确",0);
        }else if(empty($post["addressDetail"])){
            throw new \Exception("请填写地址",0);
        }else if(empty($post["province"])){
            throw new \Exception("请选择省份",0);
        }else if(empty($post["city"])){
            throw new \Exception("请选择市",0);
        }else if(empty($post["county"])){
            throw new \Exception("请选择区",0);
        }else if(empty($post["areaCode"]) && $post["client_type"] == 0){
            throw new \Exception("请选择所在地区",0);
        }else if(!in_array($post["client_type"],[0,1,2])){ // 0:app 1:mini 2: h5
            throw new \Exception("参数错误",0);
        }

        $province = AreaModel::where("level",1)->where("name","like",'%'.$post["province"].'%')->find();
        $city = AreaModel::where("pid",$province["id"])->where("level",2)->where("name","like",'%'.$post["city"].'%')->find();
        $county = AreaModel::where("pid",$city["id"])->where("level",3)->where("name","like",'%'.$post["county"].'%')->find();

        if(empty($province) || empty($city)){
            throw new \Exception("您选择的地址不存在",0);
        }

        try{
            UsersAddressModel::startTrans();
            $is_default = isset($post["is_default"]) ? intval($post["is_default"]) : 0;
            if($is_default){
                UsersAddressModel::where(["user_id"=>Users::get("id")])->update(["is_default"=>0]);
            }

            if(empty($post["id"])){
                $data = [
                    "user_id"=>Users::get("id"),
                    "accept_name"=>$post["name"],
                    "mobile"=>$post["tel"],
                    "province"=>$province["id"],
                    "city"=>$city["id"],
                    "area"=>$county["id"],
                    "address"=>$post["addressDetail"],
                    "is_default" => $is_default,
                    // "extends_info"=>json_encode(["areaCode"=>$post["areaCode"]],JSON_UNESCAPED_UNICODE),
                    "create_time"=>time()
                ];

                if(isset($post["client_type"]) && $post["client_type"] == 0){
                    $data["extends_info"] = json_encode(["areaCode"=>$post["areaCode"]],JSON_UNESCAPED_UNICODE);
                }

                $lastInsID = UsersAddressModel::create($data)->id;
            }else{
                $data = [
                    "accept_name"=>$post["name"],
                    "mobile"=>$post["tel"],
                    "province"=>$province["id"],
                    "city"=>$city["id"],
                    "area"=>$county["id"],
                    "address"=>$post["addressDetail"],
                    "is_default" => $is_default,
                    // "extends_info"=>json_encode(["areaCode"=>$post["areaCode"]],JSON_UNESCAPED_UNICODE),
                    "create_time"=>time()
                ];

                if(isset($post["client_type"]) && $post["client_type"] == 0){
                    $data["extends_info"] = json_encode(["areaCode"=>$post["areaCode"]],JSON_UNESCAPED_UNICODE);
                }

                UsersAddressModel::where("id",intval($post["id"]))->where("user_id",Users::get("id"))->save($data);
                $lastInsID = $post["id"];
            }

            UsersAddressModel::commit();
            return $lastInsID;
        }catch (\Exception $ex){
            UsersAddressModel::rollback();
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 设置默认地址
     * @param $id
     * @return bool
     */
    public static function setDefaultAddress($id){
        UsersAddressModel::where("user_id",Users::get("id"))->update(["is_default"=>0]);
        UsersAddressModel::where(["id"=>$id,"user_id"=>Users::get("id")])->update(["is_default"=> 1]);
        return true;
    }

}