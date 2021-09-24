<?php

use think\facade\Route;

Route::miss(function () {
    return view(app()->getRootPath() . 'public/wap/index.html');
});