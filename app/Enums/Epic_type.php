<?php
//自动生成枚举类  不要手工修改
//source  file: config_pic_type.php
namespace  App\Enums;

class Epic_type extends \App\Enums\Enum_base
{
	static public $field_name = "pic_type"  ;
	static public $name = "pic_type"  ;
	 static $desc_map= array(
		1 => "家长端图片",
		2 => "学生端图片",
		3 => "网页图片",
		99 => "其他图片",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		99 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 99,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 99=>  "",
	);

	//家长端图片
	const V_1=1;
	//学生端图片
	const V_2=2;
	//网页图片
	const V_3=3;
	//其他图片
	const V_99=99;

	//家长端图片
	const S_1="";
	//学生端图片
	const S_2="";
	//网页图片
	const S_3="";
	//其他图片
	const S_99="";



};
