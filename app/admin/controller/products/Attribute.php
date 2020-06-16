<?php
namespace app\admin\controller\products;

use app\admin\controller\Auth;
use mall\utils\Date;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use mall\utils\Data;

class Attribute extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $count = Db::name("products_attribute")->where(['pid'=>0])->count();
            $data = Db::name("products_attribute")->where(['pid'=>0])->order('id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();
            $_list = [];
            foreach($list as $key=>$value){
                $_list[] = $value;
                $children = \mall\basic\Category::getCategoryChildren($value["id"],'products_attribute');
                $arr = Data::analysisTree(Data::familyProcess($children,[],$value["id"]));
                array_splice($_list, count($_list), 0, $arr);
            }

            foreach($_list as $key=>$item){
                $_list[$key]['name'] = (empty($item['level']) ? '' : $item['level']) . $item["name"];
                $_list[$key]['create_time'] = Date::format($item['create_time']);
                $_list[$key]['count'] = Db::name("products_attribute_data")->where(["pid"=>$item["id"]])->count();
                $_list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
            }

            return Response::returnArray("ok",0,$_list,$count);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("products_attribute")->where("id",$id)->find();

            if(!empty($rs)){
                $rs["attr"] = Db::name("products_attribute_data")->where(["pid"=>$rs["id"]])->order("sort ASC")->select()->toArray();
            }

            return View::fetch("",[
                "ch"=>Db::name("products_attribute")->where(['pid'=>0])->select()->toArray(),
                "data"=>$rs
            ]);
        }

        $data = Request::post();
        if(!empty($data["id"])){
            try {
                Db::name("products_attribute")->strict(false)->where("id",$data['id'])->update($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            $data['create_time'] = time();
            if(!Db::name("products_attribute")->strict(false)->insert($data)){
                return Response::returnArray("操作失败，请重试。",0);
            }
            $data["id"] = Db::name("products_attribute")->getLastInsID();
        }

        $i = 0;
        $arr = array();
        if(!empty($data["attr"]["name"])){
            foreach($data["attr"]["name"] as $key=>$val){
                $attr = array(
                    "pid"=>$data["id"],
                    "value"=>$val,
                    "sort"=>$i
                );

                $id = intval($data["attr"]["id"][$key]);
                if($id <= 0){
                    Db::name("products_attribute_data")->insert($attr);
                    $arr[] = Db::name("products_attribute_data")->getLastInsID();
                }else{
                    $arr[] = $id;
                    Db::name("products_attribute_data")->where(["id"=>$id])->update($attr);
                }
                $i++;
            }
        }

        if(!empty($arr)){
            $condition = 'pid="'.$data["id"].'" AND id NOT in('.implode(",",$arr).')';
            Db::name("products_attribute_data")->where($condition)->delete();
        }

        return Response::returnArray("操作成功！");
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("products_attribute")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("products_attribute")->delete($id);
            Db::name("products_attribute_data")->where('pid',$id)->delete();
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

}