<?php
return [
    "admin_url" => "https://admin.leo1v1.com/",

    "admin_domain_url_config" => [
        \App\Enums\Eadmin_domain_type::V_ADMIN_1V1 => env( "ADMIN_1V1_URL", "http://admin.leo1v1.com"),
        \App\Enums\Eadmin_domain_type::V_ADMIN_CLASS => env( "ADMIN_CLASS_URL", "http://admin.class.leo1v1.com"),
        \App\Enums\Eadmin_domain_type::V_ADMIN_1V1_VUE => env( "ADMIN_1V1_VUE_URL", "http://localhost:9528/#"),
        \App\Enums\Eadmin_domain_type::V_ADMIN_CLASS_VUE => env( "ADMIN_CLASS_VUE_URL", "http://localhost:9528/#"),
    ],

    "qiniu" => [
        "public" => [
            // "url"    => env("QINIU_PUBLIC_URL","http://7u2f5q.com2.z0.glb.qiniucdn.com"),
            "url"    => env("QINIU_PUBLIC_URL","https://ybprodpub.leo1v1.com"),
            "bucket" => env("QINIU_PUBLIC_BUCKET","ybprodpub"),
        ] ,
        "private_url" => [
            "url"    => env("QINIU_PRIVATE_URL","http://7tszue.com2.z0.glb.qiniucdn.com"),
            "bucket" => env("QINIU_PRIVATE_BUCKET","ybprod"),
        ] ,
        "access_key" => "yPmhHAZNeHlKndKBLvhwV3fw4pzNBVvGNU5ne6Px",
        "secret_key" => "gPwzN2_b1lVJAr7Iw6W1PCRmUPZyrGF6QPbX1rxz",
    ],
    "ali_oss" => [
        "oss_access_id"  => "4Tf4qCUMJt2YlqTn",
        "oss_access_key" => "1o294A4XTBlstFChyuJwPOFNtY2mcg",
        "oss_endpoint"   => "oss-cn-shanghai.aliyuncs.com",
        "public" =>  [
            "url" =>  "https://lessonpic.oss-cn-shanghai.aliyuncs.com",
            "bucket" =>"lessonpic",
        ],
    ],
    "api_url"                   => env("API_URL", "http://api.leo1v1.com/"),
    "monitor_url"               => env("MONITOR_URL", "http://monitor.yb1v1.com/"),
    "monitor_new_url"           => env("MONITOR_NEW_URL", "http://admin.leo1v1.com"),
    //课时审查时间节点
    "lesson_confirm_start_time" => "2017-10-01",
    //老师伯乐奖累计计算时间
    "teacher_ref_start_time"    => "2017-07-01",
    //新版优学优享开始时间
    "yxyx_new_start_time"    => "2017-09-01", //
    "test" => [
        "username"  =>   "jim",
        "password"  =>   env("TEST_PASSWORD", "xcwen142857"),
    ],
    "taobao_shop" => [
        "appKey"     => "23277683",
        "secretKey"  => "c90496e27b10d2fe5c6c78b98753b9b5",
        "sessionKey" => "61021139fabdff75a2ddc18a54d1adc9d878cd8c78d071d2338582886",
        "name"       => "理优教育",
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
        "url"       => "http://wx-teacher.leo1v1.com",
    ],

    "yxyx_wx" => [
        "appid"     => env("YXYX_WX_APPID", "wxb4f28794ec117af0") ,
        "appsecret" => env("YXYX_WX_APPSECRET", "4a4bc7c543698b8ac499e5c72c22f242" )  ,
        "url"       => env("YXYX_WX_URL",  "http://wx-yxyx.leo1v1.com" ) ,
        "test_url"       => env("YXYX_TEST_WX_URL",  "http://wx-yxyx-web.leo1v1.com/wx-yxyx-new-second" ) ,
    ],
    "teacher_wx_url" => [
        "normal_url"  => "http://wx-teacher.leo1v1.com/jump_page?url=comment_normal.html?lessonid=",
        "normal_list" => "http://wx-teacher.leo1v1.com/wx_teacher_web/comment_list?type=1",
        // "normal_list" => "http://wx-teacher.leo1v1.com/jump_page?url=comment_list.html?type=1",
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
        101  => 24 ,
        102  => 24 ,
        200  => 24 ,
        201  => 24 ,
        202  => 24 ,


        300  => 16,
        301  => 16,


        400  => 8,
        401  => 8,
        500  => 8,
        600  => 8,
        700  => 8,
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
        500  => 6,
        600  => 6,
        700  => 6,
        1000  => 0,
        9000  => 1000,
        1  => 10,
        2  => 7,
        3  => 6
    ],
    "seller_hold_test_lesson_user_count" => [
        -1 => 20,
        0  => 20,
        100  => 35,
        101  => 35,
        102  => 35,
        200  => 35,
        201  => 35,
        202  => 35,
        300  => 35,
        301  => 35,
        400  => 35,
        401  => 35,
        500  => 35,
        600  => 35,
        700  => 35,
        1000  => 18,
        9000  => 120000,
        1  => 20,
        2  => 20,
        3  => 20,
    ],
    "seller_hold_user_count" => [
        -1 => 120,
        0  => 120,
        100  => 250 ,
        101  => 250,
        102  => 250,
        200  => 235,
        201  => 235,
        202  => 235,
        300  => 225,
        301  => 225,
        400  => 215,
        401  => 215,
        500  => 198,
        600  => 198,
        700  => 220,
        1000  => 88,
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
     * trial_base_price     试听课工资 1 所有旧版 2 第三版及平台合作 3 公司全职 4 第四版
     * lesson_cost          老师标准扣款
     * lesson_miss_cost     老师旷课扣款
     */
    "teacher_money" => [
        "lesson_full_num"    => "20",
        "lesson_full_reward" => "10000",
        "lesson_full_reward" => "0",
        "trial_train_reward" => "2000",
        "trial_base_price"   => [
            1=>"5000",
            2=>"3000",
            3=>"0",
            4=>"3000",
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
    "audio_server_list"=>\App\Helper\Common::env_obj(
        "AUDIO_SERVER_LIST",
        [
            /*
            "121.43.230.95", //h
            "123.57.153.80",//b
            "118.190.164.27",//q_27
            "118.190.65.189" , //q
            */

        ]),
    "xmpp_server_list"=>[
        "118.190.65.189",//q
        "121.43.230.95",//h
        "123.57.153.80",//b
        "118.190.164.27",//q_27
    ],
    "month_spec_money" => env("MONTH_SPEC_MONEY",2000),
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
    // 企业微信
    "company_wx"=>[
        "url"    => "https://qyapi.weixin.qq.com",
        "CorpID" => "wwe9748dcadfba90f7",
        "Secret" => "tIBgkcowDdb8cRiR0ft5md8wKsBPoPDp2e77T3GJSEk",
        "Secret2" => "26laMHHmbs0Pc7oxgCWpz6rS_J42QuApEpf2pFGlpRs" // 审批
    ],

    //day_system_assign_count 用于系统分配的例子 配额
    "day_system_assign_count"=> 64,

    "login_auto_key"=>"xcwen@jim142857kk001!\0\0\0",

    "admin_list"=> ["jim","jack","adrian", "tom","james", "boby", "sam","abner","ricky","顾培根"],
];
