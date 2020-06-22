<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\basic;

class Queue {

    /**
     * 创建异步任务并返回任务编号
     * @param string $title 任务名称
     * @param string $command 执行内容
     * @param integer $later 延时执行时间
     * @param array $data 任务附加数据
     * @param integer $rscript 任务类型(0单例,1多例)
     * @param integer $loops 循环等待时间
     */
    public static function add($title, $command, $later = 0, $data = [], $rscript = 1, $loops = 0){

    }

    public static function remove(){

    }
}