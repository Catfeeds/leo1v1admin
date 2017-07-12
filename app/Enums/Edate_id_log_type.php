<?php
//自动生成枚举类  不要手工修改
//source  file: config_date_id_log_type.php
namespace  App\Enums;

class Edate_id_log_type extends \App\Enums\Enum_base
{
	static public $field_name = "date_id_log_type"  ;
	static public $name = "分类"  ;
	 static $desc_map= array(
		1001 => "销售手工分配个人",
		1002 => "销售抢新生个数",
		1003 => "销售历史抢单数",
		2001 => "学员人数",
	);
	 static $simple_desc_map= array(
		1001 => "",
		1002 => "",
		1003 => "",
		2001 => "",
	);
	 static $s2v_map= array(
		"seller_assigned_count" => 1001,
		"seller_get_new_count" => 1002,
		"seller_get_history_count" => 1003,
		"valid_user_count" => 2001,
	);
	 static $v2s_map= array(
		 1001=>  "seller_assigned_count",
		 1002=>  "seller_get_new_count",
		 1003=>  "seller_get_history_count",
		 2001=>  "valid_user_count",
	);

	//销售手工分配个人
	const V_1001=1001;
	//销售手工分配个人
	const V_SELLER_ASSIGNED_COUNT=1001;
	//销售抢新生个数
	const V_1002=1002;
	//销售抢新生个数
	const V_SELLER_GET_NEW_COUNT=1002;
	//销售历史抢单数
	const V_1003=1003;
	//销售历史抢单数
	const V_SELLER_GET_HISTORY_COUNT=1003;
	//学员人数
	const V_2001=2001;
	//学员人数
	const V_VALID_USER_COUNT=2001;

	//销售手工分配个人
	const S_1001="seller_assigned_count";
	//销售手工分配个人
	const S_SELLER_ASSIGNED_COUNT="seller_assigned_count";
	//销售抢新生个数
	const S_1002="seller_get_new_count";
	//销售抢新生个数
	const S_SELLER_GET_NEW_COUNT="seller_get_new_count";
	//销售历史抢单数
	const S_1003="seller_get_history_count";
	//销售历史抢单数
	const S_SELLER_GET_HISTORY_COUNT="seller_get_history_count";
	//学员人数
	const S_2001="valid_user_count";
	//学员人数
	const S_VALID_USER_COUNT="valid_user_count";

	static public function check_seller_assigned_count ($val){
		 return $val == 1001;
	}
	static public function check_seller_get_new_count ($val){
		 return $val == 1002;
	}
	static public function check_seller_get_history_count ($val){
		 return $val == 1003;
	}
	static public function check_valid_user_count ($val){
		 return $val == 2001;
	}


};
