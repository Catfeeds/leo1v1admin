<?php
//自动生成枚举类  不要手工修改
//source  file: config_todo_status.php
namespace  App\Enums;

class Etodo_status extends \App\Core\Enum_base
{
	static public $field_name = "todo_status"  ;
	static public $name = "状态"  ;
	 static $desc_map= array(
		0 => "未开始",
		1 => "进行中",
		2 => "完成",
		3 => "未完成",
	);
	 static $simple_desc_map= array(
		0 => "black",
		1 => "blue",
		2 => "green",
		3 => "red",
	);
	 static $s2v_map= array(
		"not_start" => 0,
		"todo" => 1,
		"done" => 2,
		"not_done" => 3,
	);
	 static $v2s_map= array(
		 0=>  "not_start",
		 1=>  "todo",
		 2=>  "done",
		 3=>  "not_done",
	);

	//未开始
	const V_0=0;
	//未开始
	const V_NOT_START=0;
	//进行中
	const V_1=1;
	//进行中
	const V_TODO=1;
	//完成
	const V_2=2;
	//完成
	const V_DONE=2;
	//未完成
	const V_3=3;
	//未完成
	const V_NOT_DONE=3;

	//未开始
	const S_0="not_start";
	//未开始
	const S_NOT_START="not_start";
	//进行中
	const S_1="todo";
	//进行中
	const S_TODO="todo";
	//完成
	const S_2="done";
	//完成
	const S_DONE="done";
	//未完成
	const S_3="not_done";
	//未完成
	const S_NOT_DONE="not_done";

	static public function check_not_start ($val){
		 return $val == 0;
	}
	static public function check_todo ($val){
		 return $val == 1;
	}
	static public function check_done ($val){
		 return $val == 2;
	}
	static public function check_not_done ($val){
		 return $val == 3;
	}


};
