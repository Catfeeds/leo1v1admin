<?php
//自动生成枚举类  不要手工修改
//source  file: config_accept_flag.php
namespace  App\Enums;

class Eaccept_flag extends \App\Core\Enum_base
{
	static public $field_name = "accept_flag"  ;
	static public $name = "accept_flag"  ;
	 static $desc_map= array(
		0 => "待处理",
		1 => "同意",
		2 => "驳回",
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

	//待处理
	const V_0=0;
	//同意
	const V_1=1;
	//驳回
	const V_2=2;

	//待处理
	const S_0="";
	//同意
	const S_1="";
	//驳回
	const S_2="";



};
