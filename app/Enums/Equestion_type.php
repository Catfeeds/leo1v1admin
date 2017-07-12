<?php
//自动生成枚举类  不要手工修改
//source  file: config_question_type.php
namespace  App\Enums;

class Equestion_type extends \App\Enums\Enum_base
{
	static public $field_name = "question_type"  ;
	static public $name = "question_type"  ;
	 static $desc_map= array(
		1 => "选择",
		2 => "填空",
		3 => "问答",
		4 => "知识点",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
	);
	 static $s2v_map= array(
		"select" => 1,
		"line" => 2,
		"text" => 3,
		"without_a" => 4,
	);
	 static $v2s_map= array(
		 1=>  "select",
		 2=>  "line",
		 3=>  "text",
		 4=>  "without_a",
	);

	//选择
	const V_1=1;
	//选择
	const V_SELECT=1;
	//填空
	const V_2=2;
	//填空
	const V_LINE=2;
	//问答
	const V_3=3;
	//问答
	const V_TEXT=3;
	//知识点
	const V_4=4;
	//知识点
	const V_WITHOUT_A=4;

	//选择
	const S_1="select";
	//选择
	const S_SELECT="select";
	//填空
	const S_2="line";
	//填空
	const S_LINE="line";
	//问答
	const S_3="text";
	//问答
	const S_TEXT="text";
	//知识点
	const S_4="without_a";
	//知识点
	const S_WITHOUT_A="without_a";

	static public function check_select ($val){
		 return $val == 1;
	}
	static public function check_line ($val){
		 return $val == 2;
	}
	static public function check_text ($val){
		 return $val == 3;
	}
	static public function check_without_a ($val){
		 return $val == 4;
	}


};
