<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service\wechat;

use app\api\service\Service;
use app\common\models\wechat\WechatNewsArticle as WechatNewsArticleModel;

class News extends Service {

    /**
     * 新闻详情
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function detail($id){
        WechatNewsArticleModel::where(["id"=>$id])->inc("visit")->update();
        $data = WechatNewsArticleModel::where([ "id"=>$id ])->find();
        return [ "data"=>$data ];
    }

}