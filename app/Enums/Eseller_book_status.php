<?php
//自动生成枚举类  不要手工修改
//source  file: config_seller_book_status.php
namespace  App\Enums;

class Eseller_book_status extends \App\Enums\Enum_base
{
	static public $field_name = "seller_book_status"  ;
	static public $name = "seller_book_status"  ;
	 static $desc_map= array(
		0 => "未回访",
		1 => "无效资源",
		2 => "未接通",
		3 => "有效-意向A档",
		4 => "有效-意向B档",
		5 => "有效-意向C档",
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

	//未回访
	const V_0=0;
	//无效资源
	const V_1=1;
	//未接通
	const V_2=2;
	//有效-意向A档
	const V_3=3;
	//有效-意向B档
	const V_4=4;
	//有效-意向C档
	const V_5=5;

	//未回访
	const S_0="";
	//无效资源
	const S_1="";
	//未接通
	const S_2="";
	//有效-意向A档
	const S_3="";
	//有效-意向B档
	const S_4="";
	//有效-意向C档
	const S_5="";



};
