<?php
//自动生成枚举类  不要手工修改
//source  file: config_test_lesson_cancel_flag.php
namespace  App\Enums;

class Etest_lesson_cancel_flag extends \App\Enums\Enum_base
{
	static public $field_name = "test_lesson_cancel_flag"  ;
	static public $name = "课前取消"  ;
	 static $desc_map= array(
		0 => "无",
		1 => "取消",
		2 => "换时间",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
	);

	//无
	const V_0=0;
	//取消
	const V_1=1;
	//换时间
	const V_2=2;

	//无
	const S_0="";
	//取消
	const S_1="";
	//换时间
	const S_2="";



};
