<?php
//自动生成枚举类  不要手工修改
//source  file: config_is_warning_flag.php
namespace  App\Enums;

class Eis_warning_flag extends \App\Core\Enum_base
{
	static public $field_name = "is_warning_flag"  ;
	static public $name = "is_warning_flag"  ;
	 static $desc_map= array(
		0 => "无",
		1 => "预警中",
		2 => "已解决",
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
	//预警中
	const V_1=1;
	//已解决
	const V_2=2;

	//无
	const S_0="";
	//预警中
	const S_1="";
	//已解决
	const S_2="";



};
