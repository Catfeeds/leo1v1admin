<?php
//自动生成枚举类  不要手工修改
//source  file: config_check_money_flag.php
namespace  App\Enums;

class Echeck_money_flag extends \App\Enums\Enum_base
{
	static public $field_name = "check_money_flag"  ;
	static public $name = "check_money_flag"  ;
	 static $desc_map= array(
		0 => "未确认",
		1 => "已付款",
		2 => "未付款",
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

	//未确认
	const V_0=0;
	//已付款
	const V_1=1;
	//未付款
	const V_2=2;

	//未确认
	const S_0="";
	//已付款
	const S_1="";
	//未付款
	const S_2="";



};
