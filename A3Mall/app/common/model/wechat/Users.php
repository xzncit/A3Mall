<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\wechat;

use app\common\model\base\A3Mall;

class Users extends A3Mall {

    protected $name = "wechat_users";

    protected $type = [
        "id"=>"integer",
        "user_id"=>"integer",
        "is_black"=>"integer",
        "subscribe"=>"integer",
        "sex"=>"integer",
        "subscribe_time"=>"integer",
        "subscribe_create_time"=>"integer",
        "create_time"=>"integer"
    ];

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order('id','DESC')->paginate($size);

        $list = array_map(function ($res){
            $res['subscribe_time'] = $res->subscribe_time;
            $res['photo'] = $res['headimgurl'];

            $tags = UsersTags::column('name', 'id');
            $res['tags'] = [];
            foreach (explode(',', $res['tagid_list']) as $tagid) {
                if (isset($tags[$tagid])) $res['tags'][] = $tags[$tagid];
            }

            $res['tags'] = implode(",",$res['tags']);
            $res['area'] = implode(",",[$res["country"], $res["province"], $res["city"]]);
            return $res;
        },$data->items());

        return [
            "count"=>$count,
            "data"=>$list
        ];
    }

    public function del($id){
        try {
            $row = $this->where('id',$id)->find();
            if(empty($row)){
                throw new \Exception("您要查找的数据不存在！",0);
            }

            $this->delete($id);
            $users = new \app\common\model\users\Users();
            $users->where("id",$row["user_id"])->delete();
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage(),$ex->getCode());
        }

        return true;
    }

    public function getSubscribeTimeAttr($value){
        return date("Y-m-d H:i:s",$value);
    }

    public function setAppidAttr($value){
        return strip_tags(trim($value));
    }

    public function setUnionidAttr($value){
        return strip_tags(trim($value));
    }

    public function setOpenidAttr($value){
        return strip_tags(trim($value));
    }

    public function setTagidListAttr($value){
        return strip_tags(trim($value));
    }

    public function setNicknameAttr($value){
        return strip_tags(trim($value));
    }

    public function setCountryAttr($value){
        return strip_tags(trim($value));
    }

    public function setProvinceAttr($value){
        return strip_tags(trim($value));
    }

    public function setCityAttr($value){
        return strip_tags(trim($value));
    }

    public function setLanguageAttr($value){
        return strip_tags(trim($value));
    }

    public function setHeadimgurlAttr($value){
        return strip_tags(trim($value));
    }

    public function setRemarkAttr($value){
        return strip_tags(trim($value));
    }

    public function setSubscribeSceneAttr($value){
        return strip_tags(trim($value));
    }

    public function setQrCceneAttr($value){
        return strip_tags(trim($value));
    }

    public function setQrSceneStrAttr($value){
        return strip_tags(trim($value));
    }

}