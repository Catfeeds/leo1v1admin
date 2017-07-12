<?php
//自动生成枚举类  不要手工修改
//source  file: config_gift_type.php
namespace  App\Enums;

class Egift_type extends \App\Enums\Enum_base
{
	static public $field_name = "gift_type"  ;
	static public $name = "gift_type"  ;
	 static $desc_map= array(
		0 => "系统礼包",
		1 => "实物",
		2 => "虚拟物品(phone)",
		3 => "虚拟物品(qq)",
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

	//系统礼包
	const V_0=0;
	//实物
	const V_1=1;
	//虚拟物品(phone)
	const V_2=2;
	//虚拟物品(qq)
	const V_3=3;

	//系统礼包
	const S_0="";
	//实物
	const S_1="";
	//虚拟物品(phone)
	const S_2="";
	//虚拟物品(qq)
	const S_3="";



};
