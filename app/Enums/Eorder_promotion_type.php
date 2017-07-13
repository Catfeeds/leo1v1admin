<?php
//自动生成枚举类  不要手工修改
//source  file: config_order_promotion_type.php
namespace  App\Enums;

class Eorder_promotion_type extends \App\Core\Enum_base
{
	static public $field_name = "order_promotion_type"  ;
	static public $name = "促销分类"  ;
	 static $desc_map= array(
		0 => "无",
		1 => "赠送课时",
		2 => "打折",
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
	//赠送课时
	const V_1=1;
	//打折
	const V_2=2;

	//无
	const S_0="";
	//赠送课时
	const S_1="";
	//打折
	const S_2="";



};
