# thinkphp-jump

适用于thinkphp6.0的跳转扩展

## 安装

~~~php
composer require liliuwei/thinkphp-jump
~~~

## 配置
~~~php
// 安装之后会在config目录里生成jump.php配置文件
return[
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl' => app()->getRootPath().'/vendor/liliuwei/thinkphp-jump/src/tpl/dispatch_jump.tpl',
    'dispatch_error_tmpl'   => app()->getRootPath().'/vendor/liliuwei/thinkphp-jump/src/tpl/dispatch_jump.tpl',
];
~~~

## 用法示例

使用 use \liliuwei\think\Jump; 

在所需控制器内引用该扩展即可：
~~~php
<?php
namespace app\controller;

class Index 
{
    use \liliuwei\think\Jump; 
    public function index()
    {
        //return $this->error('error');
        //return $this->success('success','index/index');
        //return $this->redirect('/admin/index/index');
        return $this->result(['username' => 'liliuwei', 'sex' => '男']);  
    }
}
~~~
下面示例我在框架自带的BaseController里引入，以后所有需要使用跳转的类继承自带的基类即可

以下是自带的基类
~~~php
<?php
declare (strict_types = 1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {}

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                list($validate, $scene) = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
    use \liliuwei\think\Jump;
}

~~~

这里继承BaseController后即可使用success、error、redirect、result方法

~~~php
<?php

namespace app\admin\controller;
class Index extends \app\BaseController
{
    public function demo1()
    {
        /**
         * 操作成功跳转的快捷方法
         * @param  mixed $msg 提示信息
         * @param  string $url 跳转的URL地址
         * @param  mixed $data 返回的数据
         * @param  integer $wait 跳转等待时间
         * @param  array $header 发送的Header信息
         */
        // 一般用法
        return $this->success('登录成功', 'index/index');
        //完整用法
        //return $this->success($msg = '登录成功',  $url = 'index/index', $data = '',  $wait = 3,  $header = []);
    }

    public function demo2()
    {
        /**
         * 操作错误跳转的快捷方法
         * @param  mixed $msg 提示信息
         * @param  string $url 跳转的URL地址
         * @param  mixed $data 返回的数据
         * @param  integer $wait 跳转等待时间
         * @param  array $header 发送的Header信息
         */
        // 一般用法
        return $this->error('登录失败');
        //return $this->success('登录失败','login/index');
        //完整用法
        //return $this->error($msg = '登录失败',  $url = 'login/index', $data = '',  $wait = 3,  $header = []);

    }

    public function demo3()
    {
        /**
         * URL重定向
         * @param  string $url 跳转的URL表达式
         * @param  integer $code http code
         * @param  array $with 隐式传参
         */
        //一般用法
        //第一种方式：直接使用完整地址（/打头）
        //return $this->redirect('/admin/index/index');
        //第二种方式：如果你需要自动生成URL地址，应该在调用之前调用url函数先生成最终的URL地址。
        return $this->redirect(url('index/index', ['name' => 'think']));
        //return $this->redirect('http://www.thinkphp.cn');
        //完整用法
        //return $this->redirect($url= '/admin/index/index', $code = 302, $with = ['data' => 'hello']);
    }

    public function demo4()
    {
        /**
         * 返回封装后的API数据到客户端
         * @param  mixed $data 要返回的数据
         * @param  integer $code 返回的code
         * @param  mixed $msg 提示信息
         * @param  string $type 返回数据格式
         * @param  array $header 发送的Header信息
         */
        //一般用法
        return $this->result(['username' => 'liliuwei', 'sex' => '男']);
        //完整用法
        //return $this->result($data=['username' => 'liliuwei', 'sex' => '男'], $code = 0, $msg = '', $type = '',  $header = []); 
    }
}
~~~