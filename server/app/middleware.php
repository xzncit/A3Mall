<?php
// 全局中间件定义文件
return [
    // 全局请求缓存
    // \think\middleware\CheckRequestCache::basic,
    // 多语言加载
    // \think\middleware\LoadLangPack::basic,
    // Session初始化
    // \think\middleware\SessionInit::basic,
    // \think\middleware\AllowCrossDomain::class
    'think\middleware\SessionInit'
];
