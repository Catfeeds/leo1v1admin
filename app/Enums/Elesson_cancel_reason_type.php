<?php
//自动生成枚举类  不要手工修改
//source  file: config_lesson_cancel_reason_type.php
namespace  App\Enums;

class Elesson_cancel_reason_type extends \App\Enums\Enum_base
{
	static public $field_name = "lesson_cancel_reason_type"  ;
	static public $name = "取消类型"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "调课-家长调课",
		2 => "调课-老师调课",
		3 => "调课-设备原因",
		4 => "调课-网络原因",
		11 => "请假-学生请假",
		12 => "请假-老师请假",
		13 => "请假-设备原因",
		14 => "请假-网络原因",
		20 => "学生旷课",
		21 => "老师旷课",
		22 => "教学事故",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		11 => "",
		12 => "",
		13 => "",
		14 => "",
		20 => "",
		21 => "",
		22 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 11,
		"" => 12,
		"" => 13,
		"" => 14,
		"" => 20,
		"" => 21,
		"" => 22,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 11=>  "",
		 12=>  "",
		 13=>  "",
		 14=>  "",
		 20=>  "",
		 21=>  "",
		 22=>  "",
	);

	//未设置
	const V_0=0;
	//调课-家长调课
	const V_1=1;
	//调课-老师调课
	const V_2=2;
	//调课-设备原因
	const V_3=3;
	//调课-网络原因
	const V_4=4;
	//请假-学生请假
	const V_11=11;
	//请假-老师请假
	const V_12=12;
	//请假-设备原因
	const V_13=13;
	//请假-网络原因
	const V_14=14;
	//学生旷课
	const V_20=20;
	//老师旷课
	const V_21=21;
	//教学事故
	const V_22=22;

	//未设置
	const S_0="";
	//调课-家长调课
	const S_1="";
	//调课-老师调课
	const S_2="";
	//调课-设备原因
	const S_3="";
	//调课-网络原因
	const S_4="";
	//请假-学生请假
	const S_11="";
	//请假-老师请假
	const S_12="";
	//请假-设备原因
	const S_13="";
	//请假-网络原因
	const S_14="";
	//学生旷课
	const S_20="";
	//老师旷课
	const S_21="";
	//教学事故
	const S_22="";



};
