<?php
//自动生成枚举类  不要手工修改
//source  file: config_user_lesson_account_reason.php
namespace  App\Enums;

class Euser_lesson_account_reason extends \App\Enums\Enum_base
{
	static public $field_name = "user_lesson_account_reason"  ;
	static public $name = "user_lesson_account_reason"  ;
	 static $desc_map= array(
		1000 => "新增",
		1001 => "充值",
		1002 => "课程退费",
		1003 => "账户间移动",
		1004 => "修改价格",
		2001 => "课时消耗",
		2002 => "账户间移动",
		3001 => "重设1v1价格",
	);
	 static $simple_desc_map= array(
		1000 => "",
		1001 => "",
		1002 => "",
		1003 => "",
		1004 => "",
		2001 => "",
		2002 => "",
		3001 => "",
	);
	 static $s2v_map= array(
		"add_money_for_init" => 1000,
		"add_money_for_user_add" => 1001,
		"add_money_for_cancel_lesson" => 1002,
		"add_money_for_move" => 1003,
		"change_money_for_change_price" => 1004,
		"reduce_money_for_add_lesson" => 2001,
		"reduce_money_for_move" => 2002,
		"other_reset_1v1_price" => 3001,
	);
	 static $v2s_map= array(
		 1000=>  "add_money_for_init",
		 1001=>  "add_money_for_user_add",
		 1002=>  "add_money_for_cancel_lesson",
		 1003=>  "add_money_for_move",
		 1004=>  "change_money_for_change_price",
		 2001=>  "reduce_money_for_add_lesson",
		 2002=>  "reduce_money_for_move",
		 3001=>  "other_reset_1v1_price",
	);

	//新增
	const V_1000=1000;
	//新增
	const V_ADD_MONEY_FOR_INIT=1000;
	//充值
	const V_1001=1001;
	//充值
	const V_ADD_MONEY_FOR_USER_ADD=1001;
	//课程退费
	const V_1002=1002;
	//课程退费
	const V_ADD_MONEY_FOR_CANCEL_LESSON=1002;
	//账户间移动
	const V_1003=1003;
	//账户间移动
	const V_ADD_MONEY_FOR_MOVE=1003;
	//修改价格
	const V_1004=1004;
	//修改价格
	const V_CHANGE_MONEY_FOR_CHANGE_PRICE=1004;
	//课时消耗
	const V_2001=2001;
	//课时消耗
	const V_REDUCE_MONEY_FOR_ADD_LESSON=2001;
	//账户间移动
	const V_2002=2002;
	//账户间移动
	const V_REDUCE_MONEY_FOR_MOVE=2002;
	//重设1v1价格
	const V_3001=3001;
	//重设1v1价格
	const V_OTHER_RESET_1V1_PRICE=3001;

	//新增
	const S_1000="add_money_for_init";
	//新增
	const S_ADD_MONEY_FOR_INIT="add_money_for_init";
	//充值
	const S_1001="add_money_for_user_add";
	//充值
	const S_ADD_MONEY_FOR_USER_ADD="add_money_for_user_add";
	//课程退费
	const S_1002="add_money_for_cancel_lesson";
	//课程退费
	const S_ADD_MONEY_FOR_CANCEL_LESSON="add_money_for_cancel_lesson";
	//账户间移动
	const S_1003="add_money_for_move";
	//账户间移动
	const S_ADD_MONEY_FOR_MOVE="add_money_for_move";
	//修改价格
	const S_1004="change_money_for_change_price";
	//修改价格
	const S_CHANGE_MONEY_FOR_CHANGE_PRICE="change_money_for_change_price";
	//课时消耗
	const S_2001="reduce_money_for_add_lesson";
	//课时消耗
	const S_REDUCE_MONEY_FOR_ADD_LESSON="reduce_money_for_add_lesson";
	//账户间移动
	const S_2002="reduce_money_for_move";
	//账户间移动
	const S_REDUCE_MONEY_FOR_MOVE="reduce_money_for_move";
	//重设1v1价格
	const S_3001="other_reset_1v1_price";
	//重设1v1价格
	const S_OTHER_RESET_1V1_PRICE="other_reset_1v1_price";

	static public function check_add_money_for_init ($val){
		 return $val == 1000;
	}
	static public function check_add_money_for_user_add ($val){
		 return $val == 1001;
	}
	static public function check_add_money_for_cancel_lesson ($val){
		 return $val == 1002;
	}
	static public function check_add_money_for_move ($val){
		 return $val == 1003;
	}
	static public function check_change_money_for_change_price ($val){
		 return $val == 1004;
	}
	static public function check_reduce_money_for_add_lesson ($val){
		 return $val == 2001;
	}
	static public function check_reduce_money_for_move ($val){
		 return $val == 2002;
	}
	static public function check_other_reset_1v1_price ($val){
		 return $val == 3001;
	}


};
