<?php
//自动生成枚举类  不要手工修改
//source  file: config_lecture_appointment_status.php
namespace  App\Enums;

class Electure_appointment_status extends \App\Enums\Enum_base
{
	static public $field_name = "lecture_appointment_status"  ;
	static public $name = "lecture_appointment_status"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "电话已通知",
		2 => "短信已通知",
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

	//未设置
	const V_0=0;
	//电话已通知
	const V_1=1;
	//短信已通知
	const V_2=2;

	//未设置
	const S_0="";
	//电话已通知
	const S_1="";
	//短信已通知
	const S_2="";



};
