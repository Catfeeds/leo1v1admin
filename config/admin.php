<?php
return [
    'qiniu' => [
        "public" => [
            "url"    => env('QINIU_PUBLIC_URL','http://7u2f5q.com2.z0.glb.qiniucdn.com'),
            "bucket" => env('QINIU_PUBLIC_BUCKET',"ybprodpub"),
        ] ,
        "private_url" => [
            "url"    => env('QINIU_PRIVATE_URL','http://7tszue.com2.z0.glb.qiniucdn.com'),
            "bucket" => env('QINIU_PRIVATE_BUCKET',"ybprod"),
        ] ,
        "access_key" => "yPmhHAZNeHlKndKBLvhwV3fw4pzNBVvGNU5ne6Px",
        "secret_key" => "gPwzN2_b1lVJAr7Iw6W1PCRmUPZyrGF6QPbX1rxz",
    ],
    "ali_oss" => [
        "oss_access_id"  => '4Tf4qCUMJt2YlqTn',
        "oss_access_key" => '1o294A4XTBlstFChyuJwPOFNtY2mcg',
        "oss_endpoint"   => 'oss-cn-shanghai.aliyuncs.com',
        "public" =>  [
            "url" =>  "https://lessonpic.oss-cn-shanghai.aliyuncs.com",
            "bucket" =>'lessonpic',
        ],
    ],
    'api_url'     => env('API_URL', 'http://api.yb1v1.com/'),
    'monitor_url' => env('MONITOR_URL', 'http://monitor.yb1v1.com/'),
    'monitor_new_url' => env('MONITOR_NEW_URL', 'http://admin.yb1v1.com'),
    "lesson_confirm_start_time" => "2017-07-01",
    "teacher_ref_start_time"    => "2017-07-01",

    //新版优学优享开始时间
    "yxyx_new_start_time"    => "2017-08-01", //
    "test" => [
        "username"  =>   'jim',
        "password"  =>   env('TEST_PASSWORD', 'xcwen142857'),
    ],
    "taobao_shop" => [
        'appKey'     => '23277683',
        'secretKey'  => 'c90496e27b10d2fe5c6c78b98753b9b5',
        'sessionKey' => '61021139fabdff75a2ddc18a54d1adc9d878cd8c78d071d2338582886',
        'name'       => '理优教育',
    ],
    "wx" => [
        /* test
        "appid"     => "wx0e046235d4632c3b" ,
        "appsecret" => "6635bf93476620f103102d34aa16b3ae",
        */
        "appid"     => "wx636f1058abca1bc1" ,
        "appsecret" => "756ca8483d61fa9582d9cdedf202e73e",
    ],
    "teacher_wx" => [
        "appid"     => "wxa99d0de03f407627" ,
        "appsecret" => "61bbf741a09300f7f2fd0a861803f920",
        "url" => "http://wx-teacher.leo1v1.com",
    ],

    "yxyx_wx" => [
        "appid"     => env('YXYX_WX_APPID', "wxb4f28794ec117af0") ,
        "appsecret" => env('YXYX_WX_APPSECRET', "4a4bc7c543698b8ac499e5c72c22f242" )  ,
        "url" =>  env('YXYX_WX_URL',  "http://wx-yxyx.leo1v1.com" ) ,
    ],
    "teacher_wx_url" => [
        "normal_url"  => "http://wx-teacher.leo1v1.com/jump_page?url=comment_normal.html?lessonid=",
        "normal_list" => "http://wx-teacher.leo1v1.com/jump_page?url=comment_list.html?type=1",
        "trial_url"   => "http://wx-teacher.leo1v1.com/jump_page?url=comment_trial.html?lessonid=",
        "trial_list"  => "http://wx-teacher.leo1v1.com/jump_page?url=comment_list.html?type=0",
    ],
    "liyou_public_ip_list" => [
        "180.173.141.51",
        "101.199.108.118",
        "101.226.125.115",
        "118.190.65.193",
    ],
    "seller_test_lesson_user_month_limit" => [
        -1 => 8 ,
        0  => 8 ,
        1  => 24 ,
        2  => 16 ,
        3  => 8 ,

        100  => 24 ,
        101  => 24,
        102  => 24,
        200  => 24 ,
        201  => 24 ,
        202  => 24 ,


        300  => 16,
        301  => 16 ,


        400  => 8,
        401  => 8,
        500  => 8,
        600  => 8,
        1000  => 0,
        9000  => 0,

    ],
    "seller_new_user_day_count" => [
        -1 => 6,
        100  => 11 ,
        101  => 11 ,
        102  => 11 ,
        200  => 10 ,
        201  => 10 ,
        202  => 10 ,


        300  => 9,
        301  => 9,


        400  => 8,
        401  => 8,
        500  => 8,
        600  => 8,
        1000  => 0,
        9000  => 1000,

        1  => 10,
        2  => 7,
        3  => 6

    ],

    "seller_hold_test_lesson_user_count" => [
        -1 => 20,
        0  => 20,
        100  => 50,
        101  => 50,
        102  => 50,
        200  => 40,
        201  => 40,
        202  => 40,
        300  => 35,
        301  => 35,
        400  => 30,
        401  => 30,
        500  => 20,
        600  => 20,
        1000  => 20,
        9000  => 120000,
        1  => 20,
        2  => 20,
        3  => 20,
    ],
    "seller_hold_user_count" => [
        -1 => 120,
        0  => 120,
        100  => 200 ,
        101  => 200,
        102  => 200,
        200  => 180,
        201  => 180,
        202  => 180,
        300  => 170,
        301  => 170,
        400  => 150,
        401  => 150,
        500  => 120,
        600  => 120,
        1000  => 120,
        9000  => 120000,
        1  => 180,
        2  => 150,
        3  => 120
    ],
    /**
     * 金额类都需除以100
     * lesson_full_num      全勤奖计算的课节数
     * lesson_full_reward   全勤奖奖励金额
     * trial_train_reward   模拟试听通过奖励
     * trial_base_price     试听课工资 1 所有旧版 2 第三版及平台合作 3 公司全职
     * lesson_cost          老师标准扣款
     * lesson_miss_cost     老师旷课扣款
     */
    "teacher_money" => [
        "lesson_full_num"    => "20",
        // "lesson_full_reward" => "10000",
        "lesson_full_reward" => "0",
        "trial_train_reward" => "2000",
        "trial_base_price"   => [
            1=>"5000",
            2=>"3000",
            3=>"0",
        ],
        "lesson_cost"      => "500",
        "lesson_miss_cost" => "10000",
    ],
    // 短信签名
    "sms_sign_name" => [
        0 => "理优1对1",
        1 => "理优教育",
    ],
    "kaoqin_sn_list" => [
        "Q11163910103",
        "Q11163910017",
        "Q11163910084",
        "Q11163910023",
        "Q11163910015",
    ],
    "audio_server_list"=>[
        "118.190.65.189" => "120.26.58.183" , //q
        '121.43.230.95'=> "120.26.58.183" , //h
        "123.57.153.80"=> "120.26.58.183",//b
        "114.215.40.128"=> "120.26.58.183",//q2
        "114.215.98.161"=> "120.26.58.183",//q3
        "118.190.113.96"=> "120.26.58.183",//q5
        "118.190.135.205"=> "120.26.58.183",//q6
        "118.190.142.55"=> "120.26.58.183",//q7
    ],
    "xmpp_server_list"=>[
        "120.27.51.83" => "120.26.58.183" , //q
        '121.43.230.95'=> "120.26.58.183" , //h
    ],
    "month_spec_money" => env('MONTH_SPEC_MONEY',2000),
    /**
     * 招师代理工资比率 1 廖老师工作室 2 其他工作室
     */
    "teacher_ref_rate"=>[
        1 => "0.1",
        2 => [
            0 => "0.03",
            40 => "0.04",
            100 => "0.05",
            180 => "0.06",
            280 => "0.07",
            400 => "0.08",
            540 => "0.09",
        ],
    ],
];
