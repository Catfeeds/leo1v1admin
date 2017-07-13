<?php
//自动生成枚举类  不要手工修改
//source  file: config_teacher_money_type.php
namespace  App\Enums;

class Eteacher_money_type extends \App\Core\Enum_base
{
	static public $field_name = "teacher_money_type"  ;
	static public $name = "teacher_money_type"  ;
	 static $desc_map= array(
		0 => "在职教师",
		1 => "高校生",
		2 => "外聘",
		3 => "固定工资",
		4 => "第三版规则",
		5 => "平台合作",
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

	//在职教师
	const V_0=0;
	//高校生
	const V_1=1;
	//外聘
	const V_2=2;
	//固定工资
	const V_3=3;
	//第三版规则
	const V_4=4;
	//平台合作
	const V_5=5;

	//在职教师
	const S_0="";
	//高校生
	const S_1="";
	//外聘
	const S_2="";
	//固定工资
	const S_3="";
	//第三版规则
	const S_4="";
	//平台合作
	const S_5="";



};
