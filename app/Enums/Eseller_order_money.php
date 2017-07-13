<?php
//自动生成枚举类  不要手工修改
//source  file: config_seller_order_money.php
namespace  App\Enums;

class Eseller_order_money extends \App\Core\Enum_base
{
	static public $field_name = "seller_order_money"  ;
	static public $name = "销售绩效提成"  ;
	 static $desc_map= array(
		201702 => "201702方案",
		201703 => "201703方案",
		201705 => "201705方案",
	);
	 static $simple_desc_map= array(
		201702 => "",
		201703 => "",
		201705 => "",
	);
	 static $s2v_map= array(
		"" => 201702,
		"" => 201703,
		"" => 201705,
	);
	 static $v2s_map= array(
		 201702=>  "",
		 201703=>  "",
		 201705=>  "",
	);

	//201702方案
	const V_201702=201702;
	//201703方案
	const V_201703=201703;
	//201705方案
	const V_201705=201705;

	//201702方案
	const S_201702="";
	//201703方案
	const S_201703="";
	//201705方案
	const S_201705="";



};
