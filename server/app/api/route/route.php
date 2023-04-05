<?php
use think\facade\Route;
use think\facade\Request;
use think\Response;
use mall\middleware\AllowOrigin;
use mall\middleware\VerifyToken;
use mall\middleware\StoreAuth;

Route::group(function() {
    Route::any("wechat/notify", "api/pay.wechat/index");
    Route::any("wechat.index/", "api/wechat.index/index");
    Route::any("qrcode", "api/ajax/qrcode")->name("qrcode");
});

Route::group(function(){
    Route::any("update", "api/ajax/update");
    Route::any("copy", "api/ajax/copy");
    Route::post('share/config','api/wechat.Index/config');
    Route::any('oauth', 'api/wechat.OAuth/index');
    Route::any("auth", "api/wechat.OAuth/login");
    Route::any("autologin", "api/Users/autologin");
    Route::any("users", "api/wechat.OAuth/users");
    Route::any('config', 'api/wechat.index/config');
    Route::any('template', 'api/wechat.mini/template');
    Route::get('index', 'api/index/index');
    Route::get('custom', 'api/index/custom');
    Route::get('index/list', 'api/index/get_list');
    Route::get('search', 'api/search/index');
    Route::get('search/keywords', 'api/search/keywords');
    Route::get('search/list', 'api/search/get_list');
    Route::get('category', 'api/category/index');
    Route::get('goods/list', 'api/goods/index');
    Route::get('products/hot', 'api/products/hot');
    Route::get('products/recommend', 'api/products/recommend');
    Route::get('comments/list', 'api/comments/index');
    Route::post('public/login', 'api/users/login');
    Route::get('send_sms', 'api/users/send_sms');
    Route::post('register', 'api/users/register');
    Route::post('forget', 'api/users/forget');
    Route::get('goods/view', 'api/goods/view');
    Route::get('news', 'api/news/index');
    Route::get('news/view', 'api/news/view');
    Route::get('article/view', 'api/wechat.news/view');
    Route::post('payment/index', 'api/payment/index');
})->middleware(AllowOrigin::class);

Route::group(function(){
    Route::post('wx/info','api/Ucenter/wxmini_userinfo');
    Route::get('goods/favorite', 'api/goods/favorite');
    Route::get('cart', 'api/cart/index');
    Route::post('cart/add', 'api/cart/add');
    Route::post('cart/change', 'api/cart/change');
    Route::post('cart/delete', 'api/cart/delete');
    Route::get('ucenter/favorite', 'api/ucenter/favorite');
    Route::get('ucenter/favorite_delete', 'api/ucenter/favorite_delete');
    Route::get('ucenter/coupon', 'api/ucenter/coupon');
    Route::get('ucenter/coupon/goods', 'api/ucenter/goods');
    Route::get('ucenter/point', 'api/ucenter/point');
    Route::get('ucenter/get_setting', 'api/ucenter/get_setting');
    Route::post('ucenter/setting', 'api/ucenter/setting');
    Route::get('ucenter/info', 'api/ucenter/info');
    Route::get('ucenter/wallet', 'api/ucenter/wallet');
    Route::get('ucenter/address', 'api/ucenter/address');
    Route::get('ucenter/address/list', 'api/ucenter/address_list');
    Route::post('ucenter/address/save', 'api/ucenter/address_editor');
    Route::post('ucenter/address/set_address', 'api/ucenter/set_default_address');
    Route::get('ucenter/address/delete', 'api/ucenter/address_delete');
    Route::get('ucenter/help', 'api/ucenter/help');
    Route::post('order/create', 'api/order/create');
    Route::get('order/confirm', 'api/order/confirm');
    Route::post('order/detail', 'api/order/detail');
    Route::post('order/express', 'api/order/express');
    Route::get('order/list', 'api/order/get_list');
    Route::get('order/payment', 'api/order/payment');
    Route::post('order/refund', 'api/order/refund');
    Route::post('order/apply_refund', 'api/order/apply_refund');
    Route::post('order/delivery', 'api/order/delivery');
    Route::post('order/confirm_delivery', 'api/order/confirm_delivery');
    Route::post('order/do_evaluate', 'api/order/do_evaluate');
    Route::post('order/evaluate', 'api/order/evaluate');
    Route::get('order/cancel', 'api/order/cancel');
    Route::get('order/service', 'api/order/service');
    Route::get('order/info', 'api/order/info');
    Route::get('bonus', 'api/bonus/index');
    Route::get('bonus/receive', 'api/bonus/receive');
    Route::get('ucenter/wallet/fund', 'api/ucenter/fund');
    Route::get('ucenter/wallet/cashlist', 'api/ucenter/cashlist');
    Route::post('ucenter/rechange', 'api/ucenter/rechange');
    Route::get('ucenter/rechange_price', 'api/ucenter/rechange_price');
    Route::get('ucenter/settlement', 'api/ucenter/settlement');
    Route::post('ucenter/settlement_save', 'api/ucenter/settlement_save');
    Route::post('ucenter/avatar', 'api/ucenter/avatar');
})->middleware(AllowOrigin::class)->middleware(VerifyToken::class);

Route::miss(function(){
    if(Request::isOptions()){
        return Response::create('ok')->code(200)->header([
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Allow-Origin'   => '*',
            'Access-Control-Allow-Headers'  => 'Auth-Token, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With',
            'Access-Control-Allow-Methods'  => 'GET,POST,PATCH,PUT,DELETE,OPTIONS',
            'Access-Control-Max-Age'        =>  '1728000'
        ]);
    }else{
        return Response::create()->code(404);
    }
});