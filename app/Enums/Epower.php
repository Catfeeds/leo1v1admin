<?php
//自动生成枚举类  不要手工修改
//source  file: config_power.php
namespace  App\Enums;

class Epower extends \App\Core\Enum_base
{
	static public $field_name = "power"  ;
	static public $name = "power"  ;
	 static $desc_map= array(
		1 => "录入回访",
		2 => "分配老师",
		3 => "分配助教",
		4 => "学员排课",
		5 => "更改礼品状态",
		6 => "新增合同",
		7 => "合同退费",
		8 => "更改合同状态",
		9 => "更改退费状态",
		10 => "录入备注信息",
		11 => "提交分析报告",
		12 => "修改学生信息",
		13 => "设置课程内容",
		14 => "试听排课",
		15 => "测评分析",
		16 => "强制更改金额",
		20 => "查看合同金额",
		21 => "查看统计金额",
		201 => "教师管理",
		202 => "助教管理",
		203 => "查看员工绩效",
		204 => "xx",
		205 => "助教统计",
		301 => "课堂监控",
		401 => "预约管理",
		402 => "约课管理",
		410 => "预约管理-分配用户给指定销售",
		501 => "用户管理",
		502 => "角色管理",
		503 => "权限管理",
		504 => "商城管理",
		505 => "工具箱－查学校",
		506 => "消息发送统计",
		601 => "题库编辑",
		602 => "题库审核",
		603 => "题库统计",
		604 => "再审",
		801 => "预约统计",
		1021 => "财务审核",
		999 => "测试功能",
		1000 => "增加试听课",
		1001 => "提交交接单",
		1002 => "常规课表编辑",
		1003 => "销售-赠送新例子配额",
		1004 => "增加转介绍例子",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		6 => "",
		7 => "",
		8 => "",
		9 => "",
		10 => "",
		11 => "",
		12 => "",
		13 => "",
		14 => "",
		15 => "",
		16 => "",
		20 => "",
		21 => "",
		201 => "",
		202 => "",
		203 => "",
		204 => "",
		205 => "",
		301 => "",
		401 => "",
		402 => "",
		410 => "",
		501 => "",
		502 => "",
		503 => "",
		504 => "",
		505 => "",
		506 => "",
		601 => "",
		602 => "",
		603 => "",
		604 => "",
		801 => "",
		1021 => "",
		999 => "",
		1000 => "",
		1001 => "",
		1002 => "",
		1003 => "",
		1004 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
		"add_contract" => 6,
		"" => 7,
		"" => 8,
		"" => 9,
		"" => 10,
		"" => 11,
		"" => 12,
		"" => 13,
		"" => 14,
		"" => 15,
		"" => 16,
		"show_money" => 20,
		"tongji_show_money" => 21,
		"" => 201,
		"" => 202,
		"" => 203,
		"" => 204,
		"" => 205,
		"lesson_monitor" => 301,
		"" => 401,
		"" => 402,
		"user_book_assign" => 410,
		"" => 501,
		"" => 502,
		"" => 503,
		"" => 504,
		"" => 505,
		"" => 506,
		"question_edit" => 601,
		"question_check" => 602,
		"question_tongji" => 603,
		"question_check2" => 604,
		"yuyue_tongji" => 801,
		"money_check" => 1021,
		"test" => 999,
		"add_test_lesson" => 1000,
		"post_stu_init_info" => 1001,
		"otp_common_config_new" => 1002,
		"seller_add_new_student" => 1003,
		"" => 1004,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
		 6=>  "add_contract",
		 7=>  "",
		 8=>  "",
		 9=>  "",
		 10=>  "",
		 11=>  "",
		 12=>  "",
		 13=>  "",
		 14=>  "",
		 15=>  "",
		 16=>  "",
		 20=>  "show_money",
		 21=>  "tongji_show_money",
		 201=>  "",
		 202=>  "",
		 203=>  "",
		 204=>  "",
		 205=>  "",
		 301=>  "lesson_monitor",
		 401=>  "",
		 402=>  "",
		 410=>  "user_book_assign",
		 501=>  "",
		 502=>  "",
		 503=>  "",
		 504=>  "",
		 505=>  "",
		 506=>  "",
		 601=>  "question_edit",
		 602=>  "question_check",
		 603=>  "question_tongji",
		 604=>  "question_check2",
		 801=>  "yuyue_tongji",
		 1021=>  "money_check",
		 999=>  "test",
		 1000=>  "add_test_lesson",
		 1001=>  "post_stu_init_info",
		 1002=>  "otp_common_config_new",
		 1003=>  "seller_add_new_student",
		 1004=>  "",
	);

	//录入回访
	const V_1=1;
	//分配老师
	const V_2=2;
	//分配助教
	const V_3=3;
	//学员排课
	const V_4=4;
	//更改礼品状态
	const V_5=5;
	//新增合同
	const V_6=6;
	//新增合同
	const V_ADD_CONTRACT=6;
	//合同退费
	const V_7=7;
	//更改合同状态
	const V_8=8;
	//更改退费状态
	const V_9=9;
	//录入备注信息
	const V_10=10;
	//提交分析报告
	const V_11=11;
	//修改学生信息
	const V_12=12;
	//设置课程内容
	const V_13=13;
	//试听排课
	const V_14=14;
	//测评分析
	const V_15=15;
	//强制更改金额
	const V_16=16;
	//查看合同金额
	const V_20=20;
	//查看合同金额
	const V_SHOW_MONEY=20;
	//查看统计金额
	const V_21=21;
	//查看统计金额
	const V_TONGJI_SHOW_MONEY=21;
	//教师管理
	const V_201=201;
	//助教管理
	const V_202=202;
	//查看员工绩效
	const V_203=203;
	//xx
	const V_204=204;
	//助教统计
	const V_205=205;
	//课堂监控
	const V_301=301;
	//课堂监控
	const V_LESSON_MONITOR=301;
	//预约管理
	const V_401=401;
	//约课管理
	const V_402=402;
	//预约管理-分配用户给指定销售
	const V_410=410;
	//预约管理-分配用户给指定销售
	const V_USER_BOOK_ASSIGN=410;
	//用户管理
	const V_501=501;
	//角色管理
	const V_502=502;
	//权限管理
	const V_503=503;
	//商城管理
	const V_504=504;
	//工具箱－查学校
	const V_505=505;
	//消息发送统计
	const V_506=506;
	//题库编辑
	const V_601=601;
	//题库编辑
	const V_QUESTION_EDIT=601;
	//题库审核
	const V_602=602;
	//题库审核
	const V_QUESTION_CHECK=602;
	//题库统计
	const V_603=603;
	//题库统计
	const V_QUESTION_TONGJI=603;
	//再审
	const V_604=604;
	//再审
	const V_QUESTION_CHECK2=604;
	//预约统计
	const V_801=801;
	//预约统计
	const V_YUYUE_TONGJI=801;
	//财务审核
	const V_1021=1021;
	//财务审核
	const V_MONEY_CHECK=1021;
	//测试功能
	const V_999=999;
	//测试功能
	const V_TEST=999;
	//增加试听课
	const V_1000=1000;
	//增加试听课
	const V_ADD_TEST_LESSON=1000;
	//提交交接单
	const V_1001=1001;
	//提交交接单
	const V_POST_STU_INIT_INFO=1001;
	//常规课表编辑
	const V_1002=1002;
	//常规课表编辑
	const V_OTP_COMMON_CONFIG_NEW=1002;
	//销售-赠送新例子配额
	const V_1003=1003;
	//销售-赠送新例子配额
	const V_SELLER_ADD_NEW_STUDENT=1003;
	//增加转介绍例子
	const V_1004=1004;

	//录入回访
	const S_1="";
	//分配老师
	const S_2="";
	//分配助教
	const S_3="";
	//学员排课
	const S_4="";
	//更改礼品状态
	const S_5="";
	//新增合同
	const S_6="add_contract";
	//新增合同
	const S_ADD_CONTRACT="add_contract";
	//合同退费
	const S_7="";
	//更改合同状态
	const S_8="";
	//更改退费状态
	const S_9="";
	//录入备注信息
	const S_10="";
	//提交分析报告
	const S_11="";
	//修改学生信息
	const S_12="";
	//设置课程内容
	const S_13="";
	//试听排课
	const S_14="";
	//测评分析
	const S_15="";
	//强制更改金额
	const S_16="";
	//查看合同金额
	const S_20="show_money";
	//查看合同金额
	const S_SHOW_MONEY="show_money";
	//查看统计金额
	const S_21="tongji_show_money";
	//查看统计金额
	const S_TONGJI_SHOW_MONEY="tongji_show_money";
	//教师管理
	const S_201="";
	//助教管理
	const S_202="";
	//查看员工绩效
	const S_203="";
	//xx
	const S_204="";
	//助教统计
	const S_205="";
	//课堂监控
	const S_301="lesson_monitor";
	//课堂监控
	const S_LESSON_MONITOR="lesson_monitor";
	//预约管理
	const S_401="";
	//约课管理
	const S_402="";
	//预约管理-分配用户给指定销售
	const S_410="user_book_assign";
	//预约管理-分配用户给指定销售
	const S_USER_BOOK_ASSIGN="user_book_assign";
	//用户管理
	const S_501="";
	//角色管理
	const S_502="";
	//权限管理
	const S_503="";
	//商城管理
	const S_504="";
	//工具箱－查学校
	const S_505="";
	//消息发送统计
	const S_506="";
	//题库编辑
	const S_601="question_edit";
	//题库编辑
	const S_QUESTION_EDIT="question_edit";
	//题库审核
	const S_602="question_check";
	//题库审核
	const S_QUESTION_CHECK="question_check";
	//题库统计
	const S_603="question_tongji";
	//题库统计
	const S_QUESTION_TONGJI="question_tongji";
	//再审
	const S_604="question_check2";
	//再审
	const S_QUESTION_CHECK2="question_check2";
	//预约统计
	const S_801="yuyue_tongji";
	//预约统计
	const S_YUYUE_TONGJI="yuyue_tongji";
	//财务审核
	const S_1021="money_check";
	//财务审核
	const S_MONEY_CHECK="money_check";
	//测试功能
	const S_999="test";
	//测试功能
	const S_TEST="test";
	//增加试听课
	const S_1000="add_test_lesson";
	//增加试听课
	const S_ADD_TEST_LESSON="add_test_lesson";
	//提交交接单
	const S_1001="post_stu_init_info";
	//提交交接单
	const S_POST_STU_INIT_INFO="post_stu_init_info";
	//常规课表编辑
	const S_1002="otp_common_config_new";
	//常规课表编辑
	const S_OTP_COMMON_CONFIG_NEW="otp_common_config_new";
	//销售-赠送新例子配额
	const S_1003="seller_add_new_student";
	//销售-赠送新例子配额
	const S_SELLER_ADD_NEW_STUDENT="seller_add_new_student";
	//增加转介绍例子
	const S_1004="";

	static public function check_add_contract ($val){
		 return $val == 6;
	}
	static public function check_show_money ($val){
		 return $val == 20;
	}
	static public function check_tongji_show_money ($val){
		 return $val == 21;
	}
	static public function check_lesson_monitor ($val){
		 return $val == 301;
	}
	static public function check_user_book_assign ($val){
		 return $val == 410;
	}
	static public function check_question_edit ($val){
		 return $val == 601;
	}
	static public function check_question_check ($val){
		 return $val == 602;
	}
	static public function check_question_tongji ($val){
		 return $val == 603;
	}
	static public function check_question_check2 ($val){
		 return $val == 604;
	}
	static public function check_yuyue_tongji ($val){
		 return $val == 801;
	}
	static public function check_money_check ($val){
		 return $val == 1021;
	}
	static public function check_test ($val){
		 return $val == 999;
	}
	static public function check_add_test_lesson ($val){
		 return $val == 1000;
	}
	static public function check_post_stu_init_info ($val){
		 return $val == 1001;
	}
	static public function check_otp_common_config_new ($val){
		 return $val == 1002;
	}
	static public function check_seller_add_new_student ($val){
		 return $val == 1003;
	}


};
