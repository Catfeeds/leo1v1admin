<?php
//自动生成枚举类  不要手工修改
//source  file: config_complaint_state.php
namespace  App\Enums;

class Ecomplaint_state extends \App\Enums\Enum_base
{
	static public $field_name = "complaint_state"  ;
	static public $name = "complaint_state"  ;
	 static $desc_map= array(
		0 => "未处理",
		1 => "已处理",
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

	//未处理
	const V_0=0;
	//已处理
	const V_1=1;

	//未处理
	const S_0="";
	//已处理
	const S_1="";



};
