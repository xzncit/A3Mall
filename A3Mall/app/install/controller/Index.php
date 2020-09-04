<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace app\install\controller;
use app\BaseController;
use app\install\validate\Database;
use mall\utils\Tool;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Request;
use think\facade\Session;
use think\facade\View;

class Index extends BaseController {

    protected $path = '';

    protected function initialize(){
        $this->path = dirname(dirname(__FILE__)) . '/data';
        if (file_exists( $this->path.'/install.lock')) {
            exit('程序已安装，如果需要重新安装，请删除 install 模块下 data 目录下的 install.lock 文件');
        }
    }

    public function index(){
        Session::set("step",1);
        return View::fetch();
    }

    public function check(){
        Session::set("step",2);
        Session::set("error",false);
        // 环境检测
        $env = $this->check_env();

        // 目录文件读写检测
        $dirFile = $this->check_dirfile();

        // 函数检测
        $func = $this->check_func();

        return View::fetch("",[
            "env"=>$env,
            "dirFile"=>$dirFile,
            "func"=>$func,
            "isNext"=>false == Session::get('error')
        ]);
    }

    public function config(){
        if(Request::isAjax()){
            $data = Request::param();
            try {
                validate(Database::class)->check($data);
            }catch (\Exception $ex){
                return json(["status"=>0,"msg"=>$ex->getMessage()]);
            }

            // 缓存配置数据
            $data['type'] = 'mysql';
            Session::set('installData', $data);

            try {
                $mysqli = mysqli_connect(
                    $data['hostname'],
                    $data['username'],
                    $data['password']
                );

                if(!$mysqli){
                    throw new \Exception("连接数据库失败，请检查配置是否正确。");
                }

                // 检测数据库连接并检测版本
                $r = mysqli_query($mysqli,'select version() as version limit 1;');
                $version = mysqli_fetch_assoc($r);

                if (version_compare($version['version'], '5.5.3', '<')) {
                    throw new \Exception('数据库版本过低，必须 5.5.3 及以上');
                }

                // 检测是否已存在数据库
                $sql = 'SELECT count(*) as count FROM information_schema.schemata WHERE schema_name="'.$data['database'].'"';
                $result = mysqli_query($mysqli,$sql);
                $row = mysqli_fetch_assoc($result);
                if ($row["count"] <= 0) {
                    // 创建数据库
                    $sql = "CREATE DATABASE IF NOT EXISTS `{$data['database']}` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;";
                    if (!mysqli_query($mysqli,$sql)) {
                        throw new \Exception("新建数据库失败，请手动建立数据库。");
                    }
                }

                mysqli_close($mysqli);
            } catch (\Exception $e) {
                return json(["status"=>0,"msg"=>$e->getMessage()]);
            }

            // 准备工作完成
            return json(["status"=>1,"msg"=>"ok","url"=>createUrl("install")]);
        }

        if (Session::get('step') != 2) {
            $this->redirect("check");
        }

        Cache::clear();
        Session::set("step",3);
        return View::fetch();
    }

    public function install(){
        if(Request::isAjax()){
            // 数据准备
            $data = Session::get('installData');
            $type = Request::param('type');
            $result = ['code' => 1, "data"=>['type' => $type]];

            if (!$type) {
                $result['data']['type'] = 'database';
                $result['data']['status'] = 1;
                $result['msg'] = '开始安装数据库表';
                $result['url'] = createUrl('install');
                return json($result);
            }

            // 连接数据库
            $db = null;
            if (in_array($type, ['database', 'config'])) {
                try {
                    $db = mysqli_connect($data['hostname'],$data['username'],$data['password'],$data['database'],$data['hostport']);
                } catch (\Exception $e) {
                    $result['code'] = 0;
                    $result['msg'] = $e->getMessage();
                    return json($result);
                }

                $db->set_charset('utf8mb4');
            }


            if ('database' == $type) {
                $database = Cache::remember('database', function () use ($data) {
                    $sql = file_get_contents($this->path . '/a3mall' . ($data['is_demo'] == 1 ? '_demo' : '') . '.sql');
                    $sql = str_replace("`mall_","`".$data["prefix"],$sql);
                    $sql = str_replace("\r", "\n", $sql);
                    $sql = explode(";\n", $sql);
                    Cache::tag('install', 'database');
                    return $sql;
                });

                // 数据库表安装完成
                $msg = '';
                $idx = Request::param('idx','0','intval');

                if ($idx >= count($database)) {
                    $result['data']['type'] = 'config';
                    $result['data']['status'] = 1;
                    $result['msg'] = '开始安装配置文件';
                    $result['url'] = createUrl('install');
                    return json($result);
                }

                // 插入数据库表
                if (array_key_exists($idx, $database)) {
                    $sql = $value = trim($database[$idx]);
                    if(!empty($value)) {
                        try {
                            if (false !== mysqli_query($db,$sql)) {
                                $msg = $this->get_sql_message($sql);
                            } else {
                                throw new \Exception(mysqli_error($db));
                            }
                        } catch (\Exception $e) {
                            $result['code'] = 0;
                            $result['msg'] = $e->getMessage();
                            return json($result);
                        }
                    }
                }

                // 返回下一步
                $result['data']['status'] = 1;
                $result['msg'] = $msg;
                $result['url'] = createUrl('install',['idx'=>$idx+1]);
                return json($result);
            }

            // 安装配置文件
            if ('config' == $type) {
                // 创建数据库配置文件
                $string = <<<EOF
APP_DEBUG = false

[APP]
DEFAULT_TIMEZONE = Asia/Shanghai

[DATABASE]
TYPE = mysql
HOSTNAME = {$data['hostname']}
DATABASE = {$data['database']}
PREFIX   = {$data['prefix']}
USERNAME = {$data['username']}
PASSWORD = {$data['password']}
HOSTPORT = {$data['hostport']}
CHARSET = utf8
DEBUG = false

[LANG]
default_lang = zh-cn
EOF;

                if (!file_put_contents(Tool::getRootPath() . '.env', $string)) {
                    $result['code'] = 0;
                    $result['msg'] = "数据库配置文件写入失败。";
                    return json($result);
                }

                // 创建超级管理员
                $adminData = [
                    'id'    => 1,
                    'role_id'    => 1,
                    'username'  => $data["admin_user"],
                    'password'    => md5($data['admin_password']),
                    'lock'        => 1,
                    'status'      => 0,
                    'count'       => 1,
                    'time' => time()
                ];

                try {
                    $u = mysqli_query($db,"select * from {$data['prefix']}system_users where id=1");
                    $users = mysqli_fetch_assoc($u);
                    if(!empty($users)){
                        $sql = "UPDATE `{$data['prefix']}system_users` SET ";
                        unset($adminData['id']);
                        foreach($adminData as $k=>$v){
                            $sql .= '`'.$k.'` = ' . "'" . $v . "',";
                        }
                        $sql = trim($sql,',');
                        $sql .= " WHERE `id` = 1;";
                        mysqli_query($db,$sql);
                    }else{
                        $sql = "INSERT INTO `{$data['prefix']}system_users` (".implode(",",array_keys($adminData)).") VALUES (".("'".implode("','",array_values($adminData)) . "'").")";
                        mysqli_query($db,$sql);
                    }

                } catch (\Exception $e) {
                    $result['code'] = 0;
                    $result['msg'] = $e->getMessage();
                    return json($result);
                }

                $result['data']['status'] = 0;
                $result['msg'] = '安装完成！';
                $result['url'] = createUrl('complete');
                return json($result);
            }

            $result['code'] = 0;
            $result['msg'] = "安装出现错误，未完成安装。";
            return json($result);
        }

        if (Session::get('step') != 3) {
            $this->redirect("config");
        }

        Session::set('step', 4);
        return View::fetch();
    }

    public function complete(){
        file_put_contents($this->path . '/install.lock', '');
        return View::fetch("",[
            "data"=>Session::get('installData')
        ]);
    }

    private function check_env(){
        $items = [
            'os'     => ['操作系统', '不限制', '类Unix', PHP_OS, 'check'],
            'php'    => ['PHP版本', '7.2', '7.2+', PHP_VERSION, 'check'],
            'upload' => ['附件上传', '不限制', '2M+', '未知', 'check'],
            'gd'     => ['GD库', '2.0', '2.0+', '未知', 'check'],
            'disk'   => ['磁盘空间', '100M', '不限制', '未知', 'check'],
        ];

        // PHP环境检测
        if ($items['php'][3] < $items['php'][1]) {
            $items['php'][4] = 'error';
            Session::set('error', true);
        }

        // 附件上传检测
        if (@ini_get('file_uploads'))
            $items['upload'][3] = ini_get('upload_max_filesize');

        // GD库检测
        $tmp = function_exists('gd_info') ? gd_info() : [];
        if (empty($tmp['GD Version'])) {
            $items['gd'][3] = '未安装';
            $items['gd'][4] = 'error';
            Session::set('error', true);
        } else {
            $items['gd'][3] = $tmp['GD Version'];
        }
        unset($tmp);

        // 磁盘空间检测
        if (function_exists('disk_free_space')) {
            $disk_size = floor(disk_free_space(Tool::getRootPath()) / (1024 * 1024));
            $items['disk'][3] = $disk_size . 'M';
            if ($disk_size < 100) {
                $items['disk'][4] = 'error';
                Session::set('error', true);
            }
        }

        return $items;
    }

    private function check_dirfile(){
        $items = [
            ['dir', '可写', 'check', 'app'],
            ['dir', '可写', 'check', 'runtime'],
            ['dir', '可写', 'check', 'public/static'],
            ['dir', '可写', 'check', 'public/uploads'],
            ['dir', '可写', 'check', 'mall'],
        ];

        foreach ($items as &$val) {
            $item = Tool::getRootPath() . $val[3];
            if ('dir' == $val[0]) {
                if (!is_writable($item)) {
                    if (is_dir($item)) {
                        $val[1] = '可读';
                        $val[2] = 'error';
                        Session::set('error', true);
                    } else {
                        $val[1] = '不存在';
                        $val[2] = 'error';
                        Session::set('error', true);
                    }
                }
            } else {
                if (file_exists($item)) {
                    if (!is_writable($item)) {
                        $val[1] = '不可写';
                        $val[2] = 'error';
                        Session::set('error', true);
                    }
                } else {
                    if (!is_writable(dirname($item))) {
                        $val[1] = '不存在';
                        $val[2] = 'error';
                        Session::set('error', true);
                    }
                }
            }
        }

        return $items;
    }

    private function check_func(){
        $items = [
            ['pdo', '支持', 'check', '类'],
            ['pdo_mysql', '支持', 'check', '模块'],
            ['openssl', '支持', 'check', '模块'],
            ['curl', '支持', 'check', '模块'],
            ['bcmath', '支持', 'check', '模块'],
            ['mbstring', '支持', 'check', '模块'],
            ['scandir', '支持', 'check', '函数'],
            ['file_get_contents', '支持', 'check', '函数'],
            ['version_compare', '支持', 'check', '函数'],
        ];

        foreach ($items as &$val) {
            if (('类' == $val[3] && !class_exists($val[0]))
                || ('模块' == $val[3] && !extension_loaded($val[0]))
                || ('函数' == $val[3] && !function_exists($val[0]))
            ) {
                $val[1] = '不支持';
                $val[2] = 'error';
                Session::set('error', true);
            }
        }

        return $items;
    }

    private function get_sql_message($sql){
        if (preg_match('/CREATE TABLE? `([^ ]*)`/is', $sql, $matches)) {
            return !empty($matches[1]) ? "创建数据库表 {$matches[1]} 完成" : '';
        }

        if (preg_match('/INSERT INTO? `([^ ]*)`/is', $sql, $matches)) {
            return !empty($matches[1]) ? "插入数据库行 {$matches[1]} 完成" : '';
        }

        if (preg_match('/ALTER TABLE? `([^ ]*)`/is', $sql, $matches)) {
            if (mb_strpos($sql, 'MODIFY')) {
                return !empty($matches[1]) ? "调整数据库索引 {$matches[1]} 完成" : '';
            } else {
                return !empty($matches[1]) ? "创建数据库索引 {$matches[1]} 完成" : '';
            }
        }

        return '';
    }
}