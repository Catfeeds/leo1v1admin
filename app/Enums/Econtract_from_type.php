<?php
//自动生成枚举类  不要手工修改
//source  file: config_contract_from_type.php
namespace  App\Enums;

class Econtract_from_type extends \App\Core\Enum_base
{
	static public $field_name = "contract_from_type"  ;
	static public $name = "contract_from_type"  ;
	 static $desc_map= array(
		0 => "新签",
		1 => "转介绍",
		10 => "常规续费",
		11 => "扩课续费",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		10 => "",
		11 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 10,
		"" => 11,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 10=>  "",
		 11=>  "",
	);

	//新签
	const V_0=0;
	//转介绍
	const V_1=1;
	//常规续费
	const V_10=10;
	//扩课续费
	const V_11=11;

	//新签
	const S_0="";
	//转介绍
	const S_1="";
	//常规续费
	const S_10="";
	//扩课续费
	const S_11="";



};
