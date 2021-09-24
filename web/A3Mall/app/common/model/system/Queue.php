<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\common\model\system;

use app\common\model\base\A3Mall;

class Queue extends A3Mall {

    protected $name = "system_queue";

    public function getList($condition=[],$size=10,$page=1){
        $count = $this->where($condition)->count();
        $data = $this->where($condition)->order('id','desc')->paginate($size);

        $array = array_map(function ($res){
            return [
                "id"=>$res->id,
                "count"=>$res->count,
                "title"=>$res->title,
                "command"=>$res->command,
                "status"=>$res->status,
                "value"=>$res->value,
                "type"=>$res["type"],
                "exec_type"=>$res->exec_type,
                "start_time"=>$res->start_time,
                "end_time"=>$res->end_time,
                "format_create_time"=>date("Y-m-d H:i:s",is_numeric($res->create_time) ? $res->create_time : strtotime($res->create_time)),
                "format_exec_time"=>date("Y-m-d H:i:s",$res->exec_time),
                "format_start_time"=>date("Y-m-d H:i:s",$res->start_time),
                "format_end_time"=>date("Y-m-d H:i:s",$res->end_time),
                "format_duration_time"=>$res->start_time > 0 && $res->end_time > 0 ? sprintf("%.4f",$res->end_time-$res->start_time) : 0
            ];
        },$data->items());
        return [
            "count"=>$count,
            "data"=>$array
        ];
    }

}