<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace mall\command;

use mall\basic\Setting;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Db;

class A3Mall extends Command {

    protected function configure(){
        $this->setName('task')
            ->addArgument('name', Argument::OPTIONAL, "Order type")
            ->addOption('time', null, Option::VALUE_REQUIRED, 'Time is optional')
            ->setDescription('Order task');
    }

    protected function execute(Input $input, Output $output){
        $name = trim($input->getArgument('name'));
        if(empty($name)){
            $output->writeln("Please enter the type of order to be executed");
            return ;
        }

        $setting = Setting::get("order",true);
        switch($name){
            case "cancle":
                $time = $input->hasOption('time') ? $input->getOption('time') : $setting["cancel_time"];
                $count = Db::name("order")->where(["pay_status"=>0,"status"=>1])->where("create_time","<=",(time() - ($time * 60 * 60 * 24)))->count();
                Db::name("order")->where(["pay_status"=>0,"status"=>1])->where("create_time","<=",(time() - ($time * 60 * 60 * 24)))->update(["status"=>4]);
                break;
            case "complete":
                $time = $input->hasOption('time') ? $input->getOption('time') : $setting["complete_time"];
                $count = Db::name("order")->where(["pay_status"=>1,"status"=>2])->where("pay_time","<=",(time() - ($time * 60 * 60 * 24)))->count();
                Db::name("order")->where(["pay_status"=>1,"status"=>2])->where("pay_time","<=",(time() - ($time * 60 * 60 * 24)))->update([
                    "completion_time"=>time(),"status"=>5
                ]);
                break;
            case "sign":
                $time = $input->hasOption('time') ? $input->getOption('time') : $setting["confirm_time"];
                $count = Db::name("order")->where(["pay_status"=>1,"status"=>2])->where("send_time","<=",(time() - ($time * 60 * 60 * 24)))->count();
                Db::name("order")->where(["pay_status"=>1,"status"=>2])->where("send_time","<=",(time() - ($time * 60 * 60 * 24)))->update([
                    "status"=>5,"accept_time"=>time(),"completion_time"=>time(),"delivery_status"=>1
                ]);
                break;
            case "cart":
                $time = $input->hasOption('time') ? $input->getOption('time') : 30;
                $count = Db::name("cart")->where("create_time","<=",(time() - ($time * 60 * 60 * 24)))->count();
                Db::name("cart")->where("create_time","<=",(time() - ($time * 60 * 60 * 24)))->delete();
                break;
        }

        $output->writeln("Processing {$count} pieces of data");
    }

}