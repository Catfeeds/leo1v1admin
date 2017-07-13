<?php
//自动生成枚举类  不要手工修改
//source  file: config_trial_type.php
namespace  App\Enums;

class Etrial_type extends \App\Core\Enum_base
{
	static public $field_name = "trial_type"  ;
	static public $name = "trial_type"  ;
	 static $desc_map= array(
		0 => "无",
		1 => "1v1试听课",
		2 => "1v1定制课",
		3 => "1v1自选课",
		4 => "小班课购买",
		5 => "公开课报名",
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

	//无
	const V_0=0;
	//1v1试听课
	const V_1=1;
	//1v1定制课
	const V_2=2;
	//1v1自选课
	const V_3=3;
	//小班课购买
	const V_4=4;
	//公开课报名
	const V_5=5;

	//无
	const S_0="";
	//1v1试听课
	const S_1="";
	//1v1定制课
	const S_2="";
	//1v1自选课
	const S_3="";
	//小班课购买
	const S_4="";
	//公开课报名
	const S_5="";



};
