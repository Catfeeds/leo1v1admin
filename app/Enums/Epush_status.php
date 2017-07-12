<?php
//自动生成枚举类  不要手工修改
//source  file: config_push_status.php
namespace  App\Enums;

class Epush_status extends \App\Enums\Enum_base
{
	static public $field_name = "push_status"  ;
	static public $name = "push_status"  ;
	 static $desc_map= array(
		0 => "未推送",
		1 => "已推送",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
	);

	//未推送
	const V_0=0;
	//已推送
	const V_1=1;

	//未推送
	const S_0="";
	//已推送
	const S_1="";



};
