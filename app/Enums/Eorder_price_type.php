<?php
//自动生成枚举类  不要手工修改
//source  file: config_order_price_type.php
namespace  App\Enums;

class Eorder_price_type extends \App\Core\Enum_base
{
	static public $field_name = "order_price_type"  ;
	static public $name = "价格活动分类"  ;
	 static $desc_map= array(
		20170101 => "17年1月调价",
		20170701 => "17年7月调价",
	);
	 static $simple_desc_map= array(
		20170101 => "",
		20170701 => "",
	);
	 static $s2v_map= array(
		"" => 20170101,
		"" => 20170701,
	);
	 static $v2s_map= array(
		 20170101=>  "",
		 20170701=>  "",
	);

	//17年1月调价
	const V_20170101=20170101;
	//17年7月调价
	const V_20170701=20170701;

	//17年1月调价
	const S_20170101="";
	//17年7月调价
	const S_20170701="";



};
