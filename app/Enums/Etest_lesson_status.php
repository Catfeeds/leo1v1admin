<?php
//自动生成枚举类  不要手工修改
//source  file: config_test_lesson_status.php
namespace  App\Enums;

class Etest_lesson_status extends \App\Core\Enum_base
{
	static public $field_name = "test_lesson_status"  ;
	static public $name = "test_lesson_status"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "正常完成",
		2 => "课程取消",
		3 => "课程取消-换时间",
		4 => "学生未到",
		5 => "老师未到",
		6 => "设备,系统异常",
		7 => "其他",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		6 => "",
		7 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
		"" => 6,
		"" => 7,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
		 6=>  "",
		 7=>  "",
	);

	//未设置
	const V_0=0;
	//正常完成
	const V_1=1;
	//课程取消
	const V_2=2;
	//课程取消-换时间
	const V_3=3;
	//学生未到
	const V_4=4;
	//老师未到
	const V_5=5;
	//设备,系统异常
	const V_6=6;
	//其他
	const V_7=7;

	//未设置
	const S_0="";
	//正常完成
	const S_1="";
	//课程取消
	const S_2="";
	//课程取消-换时间
	const S_3="";
	//学生未到
	const S_4="";
	//老师未到
	const S_5="";
	//设备,系统异常
	const S_6="";
	//其他
	const S_7="";



};
