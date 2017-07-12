<?php
//自动生成枚举类  不要手工修改
//source  file: config_lesson_work_status.php
namespace  App\Enums;

class Elesson_work_status extends \App\Enums\Enum_base
{
	static public $field_name = "lesson_work_status"  ;
	static public $name = "lesson_work_status"  ;
	 static $desc_map= array(
		0 => "老师未上传",
		1 => "学生未上传",
		2 => "未批改",
		3 => "已批改",
		4 => "教研已评价",
		5 => "助教已评价",
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

	//老师未上传
	const V_0=0;
	//学生未上传
	const V_1=1;
	//未批改
	const V_2=2;
	//已批改
	const V_3=3;
	//教研已评价
	const V_4=4;
	//助教已评价
	const V_5=5;

	//老师未上传
	const S_0="";
	//学生未上传
	const S_1="";
	//未批改
	const S_2="";
	//已批改
	const S_3="";
	//教研已评价
	const S_4="";
	//助教已评价
	const S_5="";



};
