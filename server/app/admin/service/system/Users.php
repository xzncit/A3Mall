<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\system;

use app\admin\service\Service;
use app\admin\model\system\Users as UsersModel;
use app\common\models\system\Manage as ManageModel;

class Users extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $count = UsersModel::withJoin("manage")->count();
        $result = UsersModel::withJoin("manage")->page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray();
        return [ "count"=>$count, "data"=>$result ];
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
        $row = UsersModel::where("id",$id)->find();
        return [
            "cat"=>ManageModel::where(["status"=>0])->select()->toArray(),
            "data"=>$row??[]
        ];
    }

    /**
     * 保存数据
     * @param $data
     * @return bool
     * @throws \Exception
     */
    public static function save($data){
        if(UsersModel::where("id",$data["id"])->count()){
            if(!empty($data["password"]) || !empty($data["confirm_password"])){
                if($data["password"] != $data["confirm_password"]){
                    throw new \Exception("您输入的两次密码不致。",0);
                }

                $data["password"] = md5($data["password"]);
            }else{
                unset($data["password"],$data["confirm_password"]);
            }

            if(UsersModel::where("username",$data["username"])->where("id","<>",$data["id"])->count()){
                throw new \Exception("该用户名己存在，请更换用户名。",0);
            }

            UsersModel::where("id",$data["id"])->save($data);
        }else{
            if(empty($data["password"])){
                throw new \Exception("请填写密码",0);
            }else if(empty($data["confirm_password"])){
                throw new \Exception("请填写确认密码",0);
            }else if($data["password"] != $data["confirm_password"]){
                throw new \Exception("您输入的两次密码不致。",0);
            }

            if(UsersModel::where("username",$data["username"])->count()){
                throw new \Exception("该用户名己存在，请更换用户名。",0);
            }

            $data["password"] = md5($data["password"]);
            $data["time"] = time();
            UsersModel::create($data);
        }

        return true;
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
        $row = UsersModel::where('id',$id)->find();
        if(empty($row)){
            throw new \Exception("您要查找的数据不存在！",0);
        }

        if($row["lock"] == 1){
            throw new \Exception("该用户为系统用户，不允许删除。",0);
        }

        return UsersModel::where("id",$id)->delete();
    }

    /**
     * 更新字段值
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function setFields(){
        $data = self::getFields();
        $result = UsersModel::where('id',$data["id"])->find();
        if(empty($result)){
            throw new \Exception("您要查找的数据不存在！",0);
        }

        if($result["lock"] == 1){
            throw new \Exception("该用户为系统用户，不允许修改。",0);
        }

        UsersModel::where("id",$data["id"])->update([$data["name"]=>$data["value"]]);
        return true;
    }

}