<?php
//自动生成枚举类  不要手工修改
//source  file: config_relation_ship.php
namespace  App\Enums;

class Erelation_ship extends \App\Core\Enum_base
{
	static public $field_name = "relation_ship"  ;
	static public $name = "relation_ship"  ;
	 static $desc_map= array(
		1 => "父亲",
		2 => "母亲",
		3 => "爷爷",
		4 => "奶奶",
		5 => "外公",
		6 => "外婆",
		7 => "其他",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		6 => "",
		7 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
		"" => 6,
		"" => 7,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
		 6=>  "",
		 7=>  "",
	);

	//父亲
	const V_1=1;
	//母亲
	const V_2=2;
	//爷爷
	const V_3=3;
	//奶奶
	const V_4=4;
	//外公
	const V_5=5;
	//外婆
	const V_6=6;
	//其他
	const V_7=7;

	//父亲
	const S_1="";
	//母亲
	const S_2="";
	//爷爷
	const S_3="";
	//奶奶
	const S_4="";
	//外公
	const S_5="";
	//外婆
	const S_6="";
	//其他
	const S_7="";



};
