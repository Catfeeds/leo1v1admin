<?php
//自动生成枚举类  不要手工修改
//source  file: config_teacher_type.php
namespace  App\Enums;

class Eteacher_type extends \App\Enums\Enum_base
{
	static public $field_name = "teacher_type"  ;
	static public $name = "teacher_type"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "全职老师",
		2 => "兼职老师",
		3 => "公司全职老师",
		21 => "平台总代理",
		22 => "平台助理",
		31 => "公众号渠道",
		32 => "微信号代理老师",
		41 => "企业招聘",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		21 => "",
		22 => "",
		31 => "",
		32 => "",
		41 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 21,
		"" => 22,
		"" => 31,
		"" => 32,
		"" => 41,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 21=>  "",
		 22=>  "",
		 31=>  "",
		 32=>  "",
		 41=>  "",
	);

	//未设置
	const V_0=0;
	//全职老师
	const V_1=1;
	//兼职老师
	const V_2=2;
	//公司全职老师
	const V_3=3;
	//平台总代理
	const V_21=21;
	//平台助理
	const V_22=22;
	//公众号渠道
	const V_31=31;
	//微信号代理老师
	const V_32=32;
	//企业招聘
	const V_41=41;

	//未设置
	const S_0="";
	//全职老师
	const S_1="";
	//兼职老师
	const S_2="";
	//公司全职老师
	const S_3="";
	//平台总代理
	const S_21="";
	//平台助理
	const S_22="";
	//公众号渠道
	const S_31="";
	//微信号代理老师
	const S_32="";
	//企业招聘
	const S_41="";



};
