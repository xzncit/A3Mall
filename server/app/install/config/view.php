<?php
// +----------------------------------------------------------------------
// | 模板设置
// +----------------------------------------------------------------------

return [
    // 模板后缀
    'view_suffix'              => 'php',
    'layout_on'                => true,
    'layout_name'              => 'main/layout',
    // 视图输出字符串内容替换
    'tpl_replace_string'       => [
        '{__PUBLIC_PATH__}' =>  '/',              //public 目录
        '{__STATIC_PATH__}' =>  '/static',       //全局静态目录
        '{__INSTALL_PATH__}' =>  '/static/install',       //全局静态目录
    ]
];