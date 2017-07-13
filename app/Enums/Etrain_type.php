<?php
//自动生成枚举类  不要手工修改
//source  file: config_train_type.php
namespace  App\Enums;

class Etrain_type extends \App\Core\Enum_base
{
	static public $field_name = "train_type"  ;
	static public $name = "train_type"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "新入职培训",
		2 => "科目培训",
		3 => "试听反馈培训",
		4 => "模拟试听",
		5 => "面试试讲",
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
	//新入职培训
	const V_1=1;
	//科目培训
	const V_2=2;
	//试听反馈培训
	const V_3=3;
	//模拟试听
	const V_4=4;
	//面试试讲
	const V_5=5;

	//未设置
	const S_0="";
	//新入职培训
	const S_1="";
	//科目培训
	const S_2="";
	//试听反馈培训
	const S_3="";
	//模拟试听
	const S_4="";
	//面试试讲
	const S_5="";



};
