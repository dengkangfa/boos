<?php

use Faker\Generator as Faker;

$faker = \Faker\Factory::create('zh_CN');

$factory->define(App\Models\Company::class, function ($faker) {
    $identicon = new \Identicon\Identicon();
    $industry_str = [
            100001 => '电子商务',
            100002 => '游戏',
            100003 => '媒体',
            100004 => '广告营销',
            100005 => '数据服务',
            100006 => '医疗健康',
            100007 => '生活服务',
            100008 => 'O2O',
            100009 => '旅游',
            100010 => '分类信息',
            100011 => '音乐/视频/阅读',
            100012 => '在线教育',
            100013 => '社交网络',
            100014 => '人力资源服务',
            100015 => '企业服务',
            100016 => '信息安全',
            100017 => '新零售',
            100018 => '智能硬件',
            100019 => '移动互联网',
            100020 => '互联网',
            100021 => '计算机软件',
            100022 => '计算机硬件',
            100023 => '计算机服务',
            100024 => '通信/网络设备',
            100025 => '运营商/增值服务',
            100026 => '电子/半导体/集成电路'
    ];
    $industry_code = rand(100001, 100026);

    $people = rand(0, 5);

    return [
        'name' => $faker->name,
        'abbreviation' => $faker->name,
        'pic' =>  $identicon->getImageDataUri('dkf', 80),
        'industry_code' => $industry_code,
        'industry_str' => $industry_str[$industry_code],
        'people' => $people
    ];
});
