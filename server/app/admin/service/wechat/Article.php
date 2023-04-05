<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\wechat;

use think\facade\Session;
use app\admin\service\Service;
use app\common\models\wechat\WechatNews as WechatNewsModel;
use app\common\models\wechat\WechatNewsArticle as WechatNewsArticleModel;
use mall\utils\CString;
use app\common\models\Attachments as AttachmentsModel;
use think\facade\Db;

class Article extends Service {

    /**
     * 获取列表数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getList($data){
        $page = $data["page"]??1;
        $size = 4*4;
        $count = WechatNewsModel::count();
        $total_page = ceil($count/$size);

        $result = [ "info"=>"ok", "status"=>0, "data"=>[], "total"=>$total_page, "page"=>$page ];
        if($total_page == $page -1){
            return $result;
        }

        $data = WechatNewsModel::limit((($page - 1) * $size),$size)->select()->toArray();
        if(empty($data)){
            return $result;
        }

        foreach($data as $key=>$item){
            $data[$key] = $item;
            $row = WechatNewsArticleModel::where("id","in",$item["article_id"])->select()->toArray();
            $data[$key]["list"] = $row;
            $data[$key]["editor"] = createUrl("editor",["id"=>$item["id"]]);
            $data[$key]["remove"] = createUrl("delete",["id"=>$item["id"]]);
        }

        $result["data"] = $data;
        return $result;
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
        $data = WechatNewsModel::where("id",$id)->find();
        if(!empty($data)) {
            $article = WechatNewsArticleModel::where("id", "in", $data["article_id"])->select()->toArray();
            foreach($article as $k=>$v){
                $article[$k]["images"] = Db::name("attachments")->where([
                    "pid"=>$v["id"],"module"=>"wechat","method"=>"article"
                ])->select()->toArray();
            }
            $data["article"] = json_encode($article,JSON_UNESCAPED_UNICODE);
        }

        return [ "data"=>$data ];
    }

    /**
     * 保存数据
     * @param $data
     * @throws \Exception
     */
    public static function save($data){
        try{
            WechatNewsModel::startTrans();

            $post = $data["post"]??[];
            $pid = $data["pid"] ?? 0;
            $arrIds = [];

            foreach($post as $value){
                $attachment_id = [];
                if(empty($value["digest"])){
                    $value["digest"] = CString::msubstr($value["content"],100,false);
                }

                if(!empty($value["create_time"])){
                    unset($value["create_time"]);
                }

                if($value["id"] == 0){
                    $value["create_time"] = time();
                    unset($value["id"]);
                    $value["id"] = WechatNewsArticleModel::create($value)->id;
                    $arrIds[] = $value["id"];
                }else{
                    WechatNewsArticleModel::where("id",$value["id"])->save($value);
                    $arrIds[] = $value["id"];
                }

                if(!empty($value["images"])){
                    foreach($value["images"] as $img){
                        $attachment_id[] = $img["id"];
                    }

                    $res = AttachmentsModel::where('id','in',$attachment_id)->select()->toArray();

                    foreach($res as $value){
                        AttachmentsModel::where('id',$value['id'])->save([ "pid"=>$value["id"] ]);
                    }
                }
            }

            if($pid <= 0){
                $pid = WechatNewsModel::create([ "admin_id"=>Session::get("system_user_id"), "article_id"=>implode(",",$arrIds), "create_time"=>time() ])->id;
            }else{
                $row = WechatNewsModel::where(["id"=>$pid])->find();
                WechatNewsModel::where(["id"=>$pid])->save(["admin_id"=>Session::get("system_user_id"), "article_id"=>implode(",",$arrIds), "update_time"=>time()]);
                $a = array_diff(explode(",",$row["article_id"]),$arrIds);
                WechatNewsArticleModel::where("id","in",$a)->delete();
            }

            WechatNewsModel::commit();
        }catch (\Exception $ex){
            WechatNewsModel::rollback();
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }
    }

    /**
     * 删除
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public static function delete($id){
        $row = Db::name("wechat_news")->where("id",$id)->find();
        if(empty($row)){
            throw new \Exception("您要查找的内容不存在！",0);
        }

        if(WechatNewsModel::where("id",$id)->delete()){
            return WechatNewsArticleModel::where("id","in",$row["article_id"])->delete();
        }

        return false;
    }

}