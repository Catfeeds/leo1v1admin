<?php
//自动生成枚举类  不要手工修改
//source  file: config_seller_new_count_type.php
namespace  App\Enums;

class Eseller_new_count_type extends \App\Core\Enum_base
{
	static public $field_name = "seller_new_count_type"  ;
	static public $name = "例子赠送类型"  ;
	 static $desc_map= array(
		1 => "后台管理员赠送",
		2 => "每日赠送",
		3 => "预约红榜",
		4 => "电话时长红榜",
		5 => "合同赠送",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
	);
	 static $s2v_map= array(
		"admin" => 1,
		"day" => 2,
		"top_require" => 3,
		"top_phone_time" => 4,
		"order_add" => 5,
	);
	 static $v2s_map= array(
		 1=>  "admin",
		 2=>  "day",
		 3=>  "top_require",
		 4=>  "top_phone_time",
		 5=>  "order_add",
	);

	//后台管理员赠送
	const V_1=1;
	//后台管理员赠送
	const V_ADMIN=1;
	//每日赠送
	const V_2=2;
	//每日赠送
	const V_DAY=2;
	//预约红榜
	const V_3=3;
	//预约红榜
	const V_TOP_REQUIRE=3;
	//电话时长红榜
	const V_4=4;
	//电话时长红榜
	const V_TOP_PHONE_TIME=4;
	//合同赠送
	const V_5=5;
	//合同赠送
	const V_ORDER_ADD=5;

	//后台管理员赠送
	const S_1="admin";
	//后台管理员赠送
	const S_ADMIN="admin";
	//每日赠送
	const S_2="day";
	//每日赠送
	const S_DAY="day";
	//预约红榜
	const S_3="top_require";
	//预约红榜
	const S_TOP_REQUIRE="top_require";
	//电话时长红榜
	const S_4="top_phone_time";
	//电话时长红榜
	const S_TOP_PHONE_TIME="top_phone_time";
	//合同赠送
	const S_5="order_add";
	//合同赠送
	const S_ORDER_ADD="order_add";

	static public function check_admin ($val){
		 return $val == 1;
	}
	static public function check_day ($val){
		 return $val == 2;
	}
	static public function check_top_require ($val){
		 return $val == 3;
	}
	static public function check_top_phone_time ($val){
		 return $val == 4;
	}
	static public function check_order_add ($val){
		 return $val == 5;
	}


};
