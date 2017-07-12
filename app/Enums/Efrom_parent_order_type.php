<?php
//自动生成枚举类  不要手工修改
//source  file: config_from_parent_order_type.php
namespace  App\Enums;

class Efrom_parent_order_type extends \App\Enums\Enum_base
{
	static public $field_name = "from_parent_order_type"  ;
	static public $name = "赠送分类"  ;
	 static $desc_map= array(
		0 => "课程包赠送",
		1 => "转介绍赠送",
		2 => "试听24小时内签单赠送",
		3 => "特批赠送",
		4 => "课程包特殊赠送",
		5 => "转赠",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
	);

	//课程包赠送
	const V_0=0;
	//转介绍赠送
	const V_1=1;
	//试听24小时内签单赠送
	const V_2=2;
	//特批赠送
	const V_3=3;
	//课程包特殊赠送
	const V_4=4;
	//转赠
	const V_5=5;

	//课程包赠送
	const S_0="";
	//转介绍赠送
	const S_1="";
	//试听24小时内签单赠送
	const S_2="";
	//特批赠送
	const S_3="";
	//课程包特殊赠送
	const S_4="";
	//转赠
	const S_5="";



};
