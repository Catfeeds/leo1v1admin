<?php
//自动生成枚举类  不要手工修改
//source  file: config_school_score_change_flag.php
namespace  App\Enums;

class Eschool_score_change_flag extends \App\Enums\Enum_base
{
	static public $field_name = "school_score_change_flag"  ;
	static public $name = "school_score_change_flag"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "提升",
		2 => "不明显",
		3 => "成绩倒退",
		4 => "家长无法确定",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
	);

	//未设置
	const V_0=0;
	//提升
	const V_1=1;
	//不明显
	const V_2=2;
	//成绩倒退
	const V_3=3;
	//家长无法确定
	const V_4=4;

	//未设置
	const S_0="";
	//提升
	const S_1="";
	//不明显
	const S_2="";
	//成绩倒退
	const S_3="";
	//家长无法确定
	const S_4="";



};
