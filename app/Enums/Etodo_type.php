<?php
//自动生成枚举类  不要手工修改
//source  file: config_todo_type.php
namespace  App\Enums;

class Etodo_type extends \App\Enums\Enum_base
{
	static public $field_name = "todo_type"  ;
	static public $name = "类型"  ;
	 static $desc_map= array(
		1001 => "再次回访",
		1002 => "课前通知",
		1003 => "课后回访",
	);
	 static $simple_desc_map= array(
		1001 => "",
		1002 => "",
		1003 => "",
	);
	 static $s2v_map= array(
		"seller_next_call" => 1001,
		"seller_before_lesson_call" => 1002,
		"seller_after_lesson_call" => 1003,
	);
	 static $v2s_map= array(
		 1001=>  "seller_next_call",
		 1002=>  "seller_before_lesson_call",
		 1003=>  "seller_after_lesson_call",
	);

	//再次回访
	const V_1001=1001;
	//再次回访
	const V_SELLER_NEXT_CALL=1001;
	//课前通知
	const V_1002=1002;
	//课前通知
	const V_SELLER_BEFORE_LESSON_CALL=1002;
	//课后回访
	const V_1003=1003;
	//课后回访
	const V_SELLER_AFTER_LESSON_CALL=1003;

	//再次回访
	const S_1001="seller_next_call";
	//再次回访
	const S_SELLER_NEXT_CALL="seller_next_call";
	//课前通知
	const S_1002="seller_before_lesson_call";
	//课前通知
	const S_SELLER_BEFORE_LESSON_CALL="seller_before_lesson_call";
	//课后回访
	const S_1003="seller_after_lesson_call";
	//课后回访
	const S_SELLER_AFTER_LESSON_CALL="seller_after_lesson_call";

	static public function check_seller_next_call ($val){
		 return $val == 1001;
	}
	static public function check_seller_before_lesson_call ($val){
		 return $val == 1002;
	}
	static public function check_seller_after_lesson_call ($val){
		 return $val == 1003;
	}


};
