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
use app\common\models\system\UsersLog as UsersLogModel;

class Log extends Service {

    public static function getList($data){
        $count = UsersLogModel::count();
        $list = UsersLogModel::page($data["page"]??1,$data["limit"]??10)->order("id","desc")->select()->toArray();

        return [ "count"=>$count,"data"=>$list ];
    }

}