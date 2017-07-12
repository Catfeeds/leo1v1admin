<?php
//自动生成枚举类  不要手工修改
//source  file: config_send_gift.php
namespace  App\Enums;

class Esend_gift extends \App\Enums\Enum_base
{
	static public $field_name = "send_gift"  ;
	static public $name = "send_gift"  ;
	 static $desc_map= array(
		0 => "未兑换",
		1 => "已兑换",
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

	//未兑换
	const V_0=0;
	//已兑换
	const V_1=1;

	//未兑换
	const S_0="";
	//已兑换
	const S_1="";



};
