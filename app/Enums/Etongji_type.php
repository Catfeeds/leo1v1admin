<?php
//自动生成枚举类  不要手工修改
//source  file: config_tongji_type.php
namespace  App\Enums;

class Etongji_type extends \App\Enums\Enum_base
{
	static public $field_name = "tongji_type"  ;
	static public $name = "分类"  ;
	 static $desc_map= array(
		1 => "销售月度邀约",
		2 => "销售月度-试听成功数",
		3 => "销售月度-试听成功率",
		4 => "销售月度签单数",
		5 => "销售月度签单率",
		6 => "销售月度签单金额",
		7 => "销售月度分配资源数",
		8 => "销售月度排课数",
		9 => "销售月度签单人数",
		10 => "销售月度试听课取消率",
		11 => "销售月度试听课数",
		12 => "销售月度试听课取消数",
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
	);
	 static $s2v_map= array(
		"seller_month_require_count" => 1,
		"seller_month_succ_test_lesson_count" => 2,
		"seller_month_succ_test_lesson_percent" => 3,
		"seller_month_order_count" => 4,
		"seller_month_order_percent" => 5,
		"seller_month_order_money" => 6,
		"seller_month_assign_count" => 7,
		"seller_month_lesson_plan" => 8,
		"seller_month_order_person_count" => 9,
		"seller_month_fail_lesson_percent" => 10,
		"seller_month_all_lesson_count" => 11,
		"seller_month_fail_lesson_count" => 12,
	);
	 static $v2s_map= array(
		 1=>  "seller_month_require_count",
		 2=>  "seller_month_succ_test_lesson_count",
		 3=>  "seller_month_succ_test_lesson_percent",
		 4=>  "seller_month_order_count",
		 5=>  "seller_month_order_percent",
		 6=>  "seller_month_order_money",
		 7=>  "seller_month_assign_count",
		 8=>  "seller_month_lesson_plan",
		 9=>  "seller_month_order_person_count",
		 10=>  "seller_month_fail_lesson_percent",
		 11=>  "seller_month_all_lesson_count",
		 12=>  "seller_month_fail_lesson_count",
	);

	//销售月度邀约
	const V_1=1;
	//销售月度邀约
	const V_SELLER_MONTH_REQUIRE_COUNT=1;
	//销售月度-试听成功数
	const V_2=2;
	//销售月度-试听成功数
	const V_SELLER_MONTH_SUCC_TEST_LESSON_COUNT=2;
	//销售月度-试听成功率
	const V_3=3;
	//销售月度-试听成功率
	const V_SELLER_MONTH_SUCC_TEST_LESSON_PERCENT=3;
	//销售月度签单数
	const V_4=4;
	//销售月度签单数
	const V_SELLER_MONTH_ORDER_COUNT=4;
	//销售月度签单率
	const V_5=5;
	//销售月度签单率
	const V_SELLER_MONTH_ORDER_PERCENT=5;
	//销售月度签单金额
	const V_6=6;
	//销售月度签单金额
	const V_SELLER_MONTH_ORDER_MONEY=6;
	//销售月度分配资源数
	const V_7=7;
	//销售月度分配资源数
	const V_SELLER_MONTH_ASSIGN_COUNT=7;
	//销售月度排课数
	const V_8=8;
	//销售月度排课数
	const V_SELLER_MONTH_LESSON_PLAN=8;
	//销售月度签单人数
	const V_9=9;
	//销售月度签单人数
	const V_SELLER_MONTH_ORDER_PERSON_COUNT=9;
	//销售月度试听课取消率
	const V_10=10;
	//销售月度试听课取消率
	const V_SELLER_MONTH_FAIL_LESSON_PERCENT=10;
	//销售月度试听课数
	const V_11=11;
	//销售月度试听课数
	const V_SELLER_MONTH_ALL_LESSON_COUNT=11;
	//销售月度试听课取消数
	const V_12=12;
	//销售月度试听课取消数
	const V_SELLER_MONTH_FAIL_LESSON_COUNT=12;

	//销售月度邀约
	const S_1="seller_month_require_count";
	//销售月度邀约
	const S_SELLER_MONTH_REQUIRE_COUNT="seller_month_require_count";
	//销售月度-试听成功数
	const S_2="seller_month_succ_test_lesson_count";
	//销售月度-试听成功数
	const S_SELLER_MONTH_SUCC_TEST_LESSON_COUNT="seller_month_succ_test_lesson_count";
	//销售月度-试听成功率
	const S_3="seller_month_succ_test_lesson_percent";
	//销售月度-试听成功率
	const S_SELLER_MONTH_SUCC_TEST_LESSON_PERCENT="seller_month_succ_test_lesson_percent";
	//销售月度签单数
	const S_4="seller_month_order_count";
	//销售月度签单数
	const S_SELLER_MONTH_ORDER_COUNT="seller_month_order_count";
	//销售月度签单率
	const S_5="seller_month_order_percent";
	//销售月度签单率
	const S_SELLER_MONTH_ORDER_PERCENT="seller_month_order_percent";
	//销售月度签单金额
	const S_6="seller_month_order_money";
	//销售月度签单金额
	const S_SELLER_MONTH_ORDER_MONEY="seller_month_order_money";
	//销售月度分配资源数
	const S_7="seller_month_assign_count";
	//销售月度分配资源数
	const S_SELLER_MONTH_ASSIGN_COUNT="seller_month_assign_count";
	//销售月度排课数
	const S_8="seller_month_lesson_plan";
	//销售月度排课数
	const S_SELLER_MONTH_LESSON_PLAN="seller_month_lesson_plan";
	//销售月度签单人数
	const S_9="seller_month_order_person_count";
	//销售月度签单人数
	const S_SELLER_MONTH_ORDER_PERSON_COUNT="seller_month_order_person_count";
	//销售月度试听课取消率
	const S_10="seller_month_fail_lesson_percent";
	//销售月度试听课取消率
	const S_SELLER_MONTH_FAIL_LESSON_PERCENT="seller_month_fail_lesson_percent";
	//销售月度试听课数
	const S_11="seller_month_all_lesson_count";
	//销售月度试听课数
	const S_SELLER_MONTH_ALL_LESSON_COUNT="seller_month_all_lesson_count";
	//销售月度试听课取消数
	const S_12="seller_month_fail_lesson_count";
	//销售月度试听课取消数
	const S_SELLER_MONTH_FAIL_LESSON_COUNT="seller_month_fail_lesson_count";

	static public function check_seller_month_require_count ($val){
		 return $val == 1;
	}
	static public function check_seller_month_succ_test_lesson_count ($val){
		 return $val == 2;
	}
	static public function check_seller_month_succ_test_lesson_percent ($val){
		 return $val == 3;
	}
	static public function check_seller_month_order_count ($val){
		 return $val == 4;
	}
	static public function check_seller_month_order_percent ($val){
		 return $val == 5;
	}
	static public function check_seller_month_order_money ($val){
		 return $val == 6;
	}
	static public function check_seller_month_assign_count ($val){
		 return $val == 7;
	}
	static public function check_seller_month_lesson_plan ($val){
		 return $val == 8;
	}
	static public function check_seller_month_order_person_count ($val){
		 return $val == 9;
	}
	static public function check_seller_month_fail_lesson_percent ($val){
		 return $val == 10;
	}
	static public function check_seller_month_all_lesson_count ($val){
		 return $val == 11;
	}
	static public function check_seller_month_fail_lesson_count ($val){
		 return $val == 12;
	}


};
