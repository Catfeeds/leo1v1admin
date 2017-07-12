<?php
//自动生成枚举类  不要手工修改
//source  file: config_ass_test_lesson_type.php
namespace  App\Enums;

class Eass_test_lesson_type extends \App\Enums\Enum_base
{
	static public $field_name = "ass_test_lesson_type"  ;
	static public $name = "助教试听分类"  ;
	 static $desc_map= array(
		1 => "扩课",
		2 => "换老师",
		3 => "未听报",
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

	//扩课
	const V_1=1;
	//换老师
	const V_2=2;
	//未听报
	const V_3=3;

	//扩课
	const S_1="";
	//换老师
	const S_2="";
	//未听报
	const S_3="";



};
