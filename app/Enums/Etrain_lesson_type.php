<?php
//自动生成枚举类  不要手工修改
//source  file: config_train_lesson_type.php
namespace  App\Enums;

class Etrain_lesson_type extends \App\Enums\Enum_base
{
	static public $field_name = "train_lesson_type"  ;
	static public $name = "train_lesson_type"  ;
	 static $desc_map= array(
		1 => "新入职培训",
		2 => "科目培训",
		3 => "试听反馈培训",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
	);

	//新入职培训
	const V_1=1;
	//科目培训
	const V_2=2;
	//试听反馈培训
	const V_3=3;

	//新入职培训
	const S_1="";
	//科目培训
	const S_2="";
	//试听反馈培训
	const S_3="";



};
