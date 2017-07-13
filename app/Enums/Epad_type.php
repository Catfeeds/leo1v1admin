<?php
//自动生成枚举类  不要手工修改
//source  file: config_pad_type.php
namespace  App\Enums;

class Epad_type extends \App\Core\Enum_base
{
	static public $field_name = "pad_type"  ;
	static public $name = "Pad"  ;
	 static $desc_map= array(
		0 => "电脑",
		1 => "ipad",
		2 => "安卓平板",
		3 => "其它pad",
		4 => "ipad+PC",
		5 => "安卓+PC",
		10 => "无设备",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		10 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
		"" => 10,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
		 10=>  "",
	);

	//电脑
	const V_0=0;
	//ipad
	const V_1=1;
	//安卓平板
	const V_2=2;
	//其它pad
	const V_3=3;
	//ipad+PC
	const V_4=4;
	//安卓+PC
	const V_5=5;
	//无设备
	const V_10=10;

	//电脑
	const S_0="";
	//ipad
	const S_1="";
	//安卓平板
	const S_2="";
	//其它pad
	const S_3="";
	//ipad+PC
	const S_4="";
	//安卓+PC
	const S_5="";
	//无设备
	const S_10="";



};
