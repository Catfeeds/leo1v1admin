<?php
//自动生成枚举类  不要手工修改
//source  file: config_group_seller_student_status.php
namespace  App\Enums;

class Egroup_seller_student_status extends \App\Core\Enum_base
{
	static public $field_name = "group_seller_student_status"  ;
	static public $name = "年"  ;
	 static $desc_map= array(
		1 => "所有例子",
		2 => "优先跟进",
		3 => "待排课",
		4 => "待通知",
		5 => "待监课",
		6 => "待催费",
		7 => "正式学员",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		6 => "",
		7 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
		"" => 6,
		"" => 7,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
		 6=>  "",
		 7=>  "",
	);

	//所有例子
	const V_1=1;
	//优先跟进
	const V_2=2;
	//待排课
	const V_3=3;
	//待通知
	const V_4=4;
	//待监课
	const V_5=5;
	//待催费
	const V_6=6;
	//正式学员
	const V_7=7;

	//所有例子
	const S_1="";
	//优先跟进
	const S_2="";
	//待排课
	const S_3="";
	//待通知
	const S_4="";
	//待监课
	const S_5="";
	//待催费
	const S_6="";
	//正式学员
	const S_7="";



};
