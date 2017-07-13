<?php
//自动生成枚举类  不要手工修改
//source  file: config_tmk_student_status.php
namespace  App\Enums;

class Etmk_student_status extends \App\Core\Enum_base
{
	static public $field_name = "tmk_student_status"  ;
	static public $name = "tmk_student_status"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "待定",
		2 => "无效",
		3 => "有效",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
	);

	//未设置
	const V_0=0;
	//待定
	const V_1=1;
	//无效
	const V_2=2;
	//有效
	const V_3=3;

	//未设置
	const S_0="";
	//待定
	const S_1="";
	//无效
	const S_2="";
	//有效
	const S_3="";



};
