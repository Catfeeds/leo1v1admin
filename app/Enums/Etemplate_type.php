<?php
//自动生成枚举类  不要手工修改
//source  file: config_template_type.php
namespace  App\Enums;

class Etemplate_type extends \App\Enums\Enum_base
{
	static public $field_name = "template_type"  ;
	static public $name = "template_type"  ;
	 static $desc_map= array(
		1 => "推荐类",
		2 => "培训类",
		3 => "活动类",
		4 => "资料领取类",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
	);

	//推荐类
	const V_1=1;
	//培训类
	const V_2=2;
	//活动类
	const V_3=3;
	//资料领取类
	const V_4=4;

	//推荐类
	const S_1="";
	//培训类
	const S_2="";
	//活动类
	const S_3="";
	//资料领取类
	const S_4="";



};
