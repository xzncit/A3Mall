<?php
namespace app\admin\controller\products;

use app\admin\controller\Auth;
use mall\utils\Tool;
use mall\response\Response;
use think\facade\Db;
use think\facade\Request;
use think\facade\View;
use mall\basic\Area;

class Deliver extends Auth {

    public function index(){
        if(Request::isAjax()){
            $limit = Request::get("limit");
            $count = Db::name("deliver")->count();
            $data = Db::name("deliver")->order('id desc')->paginate($limit);

            if($data->isEmpty()){
                return Response::returnArray("当前还没有数据哦！",1);
            }

            $list = $data->items();
            foreach($list as $key=>$item){
                if($item["province"] && $item["city"] && $item["area"]){
                    $list[$key]['area_name'] = Area::get_area([
                        $item["province"],$item["city"],$item["area"]
                    ]);
                }else{
                    $list[$key]['area_name'] = "";
                }
                $list[$key]['url'] = createUrl("editor",["id"=>$item["id"]]);
            }

            return Response::returnArray("ok",0,$list,$count);
        }

        return View::fetch();
    }

    public function editor(){
        if(!Request::isAjax()){
            $id = (int)Request::param("id");
            $rs = empty($id) ? [] : Db::name("deliver")->where("id",$id)->find();

            $province = Db::name('area')->where(['pid' => 0])->select()->toArray();

            $city = [];
            if(!empty($rs["province"])){
                $city = Db::name('area')->where(['pid' => $rs["province"]])->select()->toArray();
            }

            $area = [];
            if(!empty($rs["city"])){
                $area = Db::name('area')->where(['pid' => $rs["city"]])->select()->toArray();
            }

            return View::fetch("",[
                "province"=>$province,
                "city"=>$city,
                "area"=>$area,
                "data"=>$rs
            ]);
        }

        $data = Request::post();

        $data["is_default"] = isset($data["is_default"]) && is_numeric($data["is_default"]) ? $data["is_default"] : 0;

        if($data["is_default"] == 1){
            Db::name("deliver")->where("1=1")->update([
                "is_default" => 0
            ]);
        }

        if(!empty($data["id"])){
            try {
                Db::name("deliver")->where("id",$data['id'])->update($data);
            } catch (\Exception $ex) {
                return Response::returnArray("操作失败，请重试。",0);
            }
        }else{
            if(!Db::name("deliver")->insert($data)){
                return Response::returnArray("操作失败，请重试。",0);
            }

        }

        return Response::returnArray("操作成功！");
    }

    public function delete(){
        if(!Request::isAjax()){
            return Response::returnArray("本页面不允许直接访问！",0);
        }

        $id = (int)Request::get("id");
        try {
            $row = Db::name("deliver")->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            Db::name("deliver")->delete($id);
        } catch (\Exception $ex) {
            return Response::returnArray("操作失败，请稍候在试。",0);
        }

        return Response::returnArray("ok");
    }

}