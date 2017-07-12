<?php
//自动生成枚举类  不要手工修改
//source  file: config_pic_time_type.php
namespace  App\Enums;

class Epic_time_type extends \App\Enums\Enum_base
{
	static public $field_name = "pic_time_type"  ;
	static public $name = "pic_time_type"  ;
	 static $desc_map= array(
		1 => "永久",
		2 => "一个月",
		3 => "三个月",
		4 => "半年",
		5 => "一年",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
	);

	//永久
	const V_1=1;
	//一个月
	const V_2=2;
	//三个月
	const V_3=3;
	//半年
	const V_4=4;
	//一年
	const V_5=5;

	//永久
	const S_1="";
	//一个月
	const S_2="";
	//三个月
	const S_3="";
	//半年
	const S_4="";
	//一年
	const S_5="";



};
