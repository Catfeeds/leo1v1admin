<?php
//自动生成枚举类  不要手工修改
//source  file: config_confirm_flag.php
namespace  App\Enums;

class Econfirm_flag extends \App\Enums\Enum_base
{
	static public $field_name = "confirm_flag"  ;
	static public $name = "confirm_flag"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "有效课程",
		2 => "无效课程-课程取消",
		3 => "无效课程-需给老师工资-课时照扣",
		4 => "无效课程-需给老师工资-课时不扣",
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
	//有效课程
	const V_1=1;
	//无效课程-课程取消
	const V_2=2;
	//无效课程-需给老师工资-课时照扣
	const V_3=3;
	//无效课程-需给老师工资-课时不扣
	const V_4=4;

	//未设置
	const S_0="";
	//有效课程
	const S_1="";
	//无效课程-课程取消
	const S_2="";
	//无效课程-需给老师工资-课时照扣
	const S_3="";
	//无效课程-需给老师工资-课时不扣
	const S_4="";



};
