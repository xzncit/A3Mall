<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\install\validate;

use think\Validate;

class Database extends Validate {

    protected $rule =   [
        'hostname'       => 'require',
        'database'       => 'require',
        'username'       => 'require',
        //'password'       => 'require',
        'hostport'       => 'require|number',
        'prefix'         => 'require',
        'admin_user'     => 'require|length:4,20',
        'admin_password' => 'require|min:6|confirm',
        'is_demo'        => 'require|in:0,1',
    ];

    protected $message  =   [
        'hostname'       => '数据库服务器',
        'database'       => '数据库名',
        'username'       => '数据库用户名',
        // 'password'       => '数据库密码',
        'hostport'       => '数据库端口',
        'prefix'         => '数据表前缀',
        'admin_user'     => '管理员账号',
        'admin_password' => '管理员密码',
        'is_demo'        => '导入演示数据',
    ];

}