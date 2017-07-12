<?php
//自动生成枚举类  不要手工修改
//source  file: config_revisit_origin.php
namespace  App\Enums;

class Erevisit_origin extends \App\Enums\Enum_base
{
	static public $field_name = "revisit_origin"  ;
	static public $name = "revisit_origin"  ;
	 static $desc_map= array(
		1 => "微信",
		2 => "电话",
		3 => "其他",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
	);

	//微信
	const V_1=1;
	//电话
	const V_2=2;
	//其他
	const V_3=3;

	//微信
	const S_1="";
	//电话
	const S_2="";
	//其他
	const S_3="";



};
