<?php
namespace app\admin\controller;

use think\App;

class Index extends Auth {

    public function index(){
        return redirect(createUrl('platform.index/index'));
    }

}