<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;

use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class Auth extends Base {

    public function initialize(){
        try{
            $this->checkAccess();
        }catch (\Exception $e){
            $code = $e->getCode();
            if(Request::isAjax()){
                exit(json_encode(["msg"=>$e->getMessage(),"code"=>$code],JSON_UNESCAPED_UNICODE));
            }

            switch($code){
                case -999:
                    $this->error($e->getMessage());
                    break;
                case -1000:
                     $this->redirect(createUrl('login/index'));
                    break;
            }
        }

        View::assign("sidebar",$this->sidebar());
    }

    public function sidebar(){
        $result = Db::name("system_menu")->where(["status"=>0,"pid"=>0])->order("sort","ASC")->select()->toArray();
        $controller = Request::controller(true);
        $action = Request::action(true);

        $data = Db::name("system_menu")
            ->where('status=0 AND ((controller="'.$controller.'" AND method="'.$action.'") OR (controller="'.$controller.'" AND FIND_IN_SET("'.$action.'",active)))')
            ->find();

        while (!empty($data) && $data["pid"] != 0){
            $data = Db::name("system_menu")
                ->where(["status"=>0,"id"=>$data["pid"]])
                ->find();
        }

        if(empty($data)){
            return ;
        }

        foreach($result as $key=>$value){
            $result[$key]["active"] = ($data["id"] == $value["id"]) ? true : false;
            $result[$key]["url"] = createUrl($value["controller"].'/'.$value['method']);
        }

        $menu = Db::name("system_menu")->where(["status"=>0,"pid"=>$data["id"]])->order("sort ASC")->select()->toArray();
        foreach($menu as $key=>$value){
            $menu[$key]["url"] = url($value["controller"].'/'.$value['method']);
            $menu[$key]["children"] = Db::name("system_menu")->where(["status"=>0,"pid"=>$value["id"]])->order("sort ASC")->select()->toArray();
            foreach($menu[$key]["children"] as $k=>$v){
                $menu[$key]["children"][$k]["active"] = (($controller.$action == $v["controller"].$v['method']) || ($controller == $v["controller"] && (!empty($v['active']) && in_array($action,explode(",", $v['active'])))));
                if(!isset($menu[$key]["active"]) || !$menu[$key]["active"]){
                    $menu[$key]["active"] = $menu[$key]["children"][$k]["active"] ? true : false;
                }

                $menu[$key]["children"][$k]['url'] = (string)url($v["controller"].'/'.$v['method']);
                $menu[$key]["children"][$k]["children"] = Db::name("system_menu")->where(["status"=>0,"pid"=>$v["id"]])->order("sort ASC")->select()->toArray();
                foreach($menu[$key]["children"][$k]["children"] as $index=>$item){
                    $menu[$key]["children"][$k]["children"][$index]['url'] = (string)url($item["controller"].'/'.$item['method']);
                    $menu[$key]["children"][$k]["children"][$index]["active"] = (($controller.$action == $item["controller"].$item['method']) || ($controller == $item["controller"] && (!empty($item['active']) && in_array($action,explode(",", $item['active'])))));

                    if(!$menu[$key]["children"][$k]["active"]){
                        $menu[$key]["children"][$k]["active"] = $menu[$key]["children"][$k]["children"][$index]["active"] && $menu[$key]["children"][$k]["id"] == $item["pid"];
                    }

                    if(!$menu[$key]["active"]){
                        $menu[$key]["active"] = $menu[$key]["children"][$k]["active"] && $menu[$key]["id"] == $v["pid"];
                    }
                }
            }
        }

        return ["top"=>$this->filterMenu($result),"menu"=>$this->filterMenu($menu)];
    }

    private function filterMenu($data){
        $user = Db::name("system_users")->where("id",Session::get("system_user_id"))->find();
        $manage = Db::name("system_manage")->where("id",$user["role_id"])->find();
        if($manage["purview"] == '-1'){
            return $data;
        }

        $purview = json_decode($manage["purview"],true);
        $array = [];
        $keys = array_keys($purview);
        foreach($data as $key=>$value){
            // 如果children为空，顶部菜单
            if(empty($value["children"])){
                $method = isset($purview[$value["controller"]]) ? array_flip($purview[$value["controller"]]) : [];
                if(in_array($value["controller"],$keys) && in_array($value["method"],$method)){
                    $array[$key] = $value;
                }
            }else{ // 左侧菜单
                $menu = [];
                foreach($value["children"] as $k=>$v){
                    $method = isset($purview[$v["controller"]]) ? array_flip($purview[$v["controller"]]) : [];
                    if(in_array($v["controller"],$keys) && in_array($v["method"],$method)){
                        $menu[$k] = $v;
                    }
                }

                if(!empty($menu)){
                    $array[$key] = $value;
                    $array[$key]["children"] = $menu;
                }
            }
        }

        return $array;
    }

    private function checkAccess(){
        if(!Session::has("system_user_id")){
            throw new \Exception("您还没有登录，请先登录。",-1000);
        }

        $user = Db::name("system_users")->where("id",Session::get("system_user_id"))->find();
        $manage = Db::name("system_manage")->where("id",$user["role_id"])->find();
        $user['title'] = $manage['title'];
        $user['purview'] = $manage['purview'];
        Session::set("users",$user);

        $controller = Request::controller(true);
        $action = Request::action(true);

        if($user["purview"] == '-1'){
            return true;
        }

        if($controller == 'platform.index' && $action == 'index'){
            return true;
        }

        $purview = json_decode($user["purview"],true);
        if(!empty($purview[$controller][$action])){
            return true;
        }

        if(in_array($controller,["common.ajax","common.uploadfiy","common.material","common.index","common.wechat"])){
            return true;
        }

        throw new \Exception("您无权限执行此操作",-999);
    }
}