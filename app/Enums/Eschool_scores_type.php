<?php
//自动生成枚举类  不要手工修改
//source  file: config_school_scores_type.php
namespace  App\Enums;

class Eschool_scores_type extends \App\Core\Enum_base
{
	static public $field_name = "school_scores_type"  ;
	static public $name = "school_scores_type"  ;
	 static $desc_map= array(
		1 => "零志愿",
		2 => "名额分配",
		3 => "普通高中",
		4 => "中本贯通",
		5 => "中职贯通",
		6 => "最低投档线",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		6 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
		"" => 6,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
		 6=>  "",
	);

	//零志愿
	const V_1=1;
	//名额分配
	const V_2=2;
	//普通高中
	const V_3=3;
	//中本贯通
	const V_4=4;
	//中职贯通
	const V_5=5;
	//最低投档线
	const V_6=6;

	//零志愿
	const S_1="";
	//名额分配
	const S_2="";
	//普通高中
	const S_3="";
	//中本贯通
	const S_4="";
	//中职贯通
	const S_5="";
	//最低投档线
	const S_6="";



};
