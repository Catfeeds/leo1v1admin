<?php
//自动生成枚举类  不要手工修改
//source  file: config_education.php
namespace  App\Enums;

class Eeducation extends \App\Core\Enum_base
{
	static public $field_name = "education"  ;
	static public $name = "education"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "本科",
		2 => "硕士",
		3 => "博士",
		4 => "大专",
		5 => "本科在读",
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

	//未设置
	const V_0=0;
	//本科
	const V_1=1;
	//硕士
	const V_2=2;
	//博士
	const V_3=3;
	//大专
	const V_4=4;
	//本科在读
	const V_5=5;

	//未设置
	const S_0="";
	//本科
	const S_1="";
	//硕士
	const S_2="";
	//博士
	const S_3="";
	//大专
	const S_4="";
	//本科在读
	const S_5="";



};
