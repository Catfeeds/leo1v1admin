<?php
//自动生成枚举类  不要手工修改
//source  file: config_click_status.php
namespace  App\Enums;

class Eclick_status extends \App\Enums\Enum_base
{
	static public $field_name = "click_status"  ;
	static public $name = "click_status"  ;
	 static $desc_map= array(
		1 => "可点击",
		0 => "不可点击",
	);
	 static $simple_desc_map= array(
		1 => "",
		0 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 0,
	);
	 static $v2s_map= array(
		 1=>  "",
		 0=>  "",
	);

	//可点击
	const V_1=1;
	//不可点击
	const V_0=0;

	//可点击
	const S_1="";
	//不可点击
	const S_0="";



};
