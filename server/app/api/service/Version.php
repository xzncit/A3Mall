<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\api\service;

use app\common\models\Version as VersionModel;
use mall\utils\Check;
use think\facade\Request;

class Version extends Service {

    /**
     * 获取APP升级数据
     * @param $data
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getData($data){
        $ver = $data["ver"]??"";
        $type = $data["type"]??1;
        if(empty($ver)){
            throw new \Exception("获取版本号失败",0);
        }

        $row = VersionModel::where("type='{$type}' AND version > '" . $ver . "'")->order("version","ASC")->find();
        if(empty($row)){
            throw new \Exception("当前己是最新版本",0);
        }

        if(!Check::url($row["url"])){
            $row["url"] = str_replace("\\", "/", Request::domain() . "/" . trim($row["url"],"/"));
        }

        return [
            "status"=>1,"info"=>"ok",
            "data"=>[
                "url"=>$row["url"],
                "content"=>$row["content"]
            ]
        ];
    }

}