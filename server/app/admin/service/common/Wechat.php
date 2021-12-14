<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\common;

use app\admin\service\Service;
use app\admin\model\wechat\WechatNews as WechatNewsModel;
use app\common\models\wechat\WechatNewsArticle as WechatNewsArticleModel;


class Wechat extends Service {

    /**
     * 按公众号文章类型获取内容
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getContent($data){
        $type = $data["type"]??"text";
        $content = $data["content"]??"";

        $news = [];
        if($type == "image"){
            $content = file_exists(ltrim($content,"/")) ? $content : "/static/images/default.jpg";
        }else if($type == "news"){
            $news = WechatNewsModel::where(["id"=>$content])->find();
            if(!empty($news["article_id"])){
                $news["article"] = WechatNewsArticleModel::where("id","in",$news["article_id"])
                    ->orderRaw('find_in_set(id,"'.$news["article_id"].'")')
                    ->select()->toArray();
            }
        }

        return [ "content"=>$content, "news"=>$news ];
    }

    /**
     * 获取文章列表
     * @param $data
     * @return array
     */
    public static function getArticle($data){
        $count = WechatUsersModel::withSearch(["title"],[ 'title'=>$data['key']["title"]??'' ])->count();
        $result = WechatUsersModel::withSearch(["title"],[ 'title'=>$data['key']["title"]??'' ])->order("id","desc")->page($data["page"]??1,$data["limit"]??10)->select()->toArray();

        foreach($result as $key=>$item){
            $article = WechatNewsArticleModel::where("id","in",$item["article_id"])->orderRaw('find_in_set(id,"'.$item["article_id"].'")')->find();
            $list[$key]["title"] = $article["title"];
            $list[$key]["local_url"] = $article["local_url"];
        }

        return ["count"=>$count, "data"=>$result];
    }

}