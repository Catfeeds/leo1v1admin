<?php
//自动生成枚举类  不要手工修改
//source  file: config_reward_type.php
namespace  App\Enums;

class Ereward_type extends \App\Core\Enum_base
{
	static public $field_name = "reward_type"  ;
	static public $name = "reward_type"  ;
	 static $desc_map= array(
		1 => "荣誉榜奖金",
		2 => "试听课奖金",
		3 => "90分钟课程补偿",
		4 => "工资补偿",
		5 => "试听培训奖金",
		6 => "伯乐奖",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		6 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
		"" => 6,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
		 6=>  "",
	);

	//荣誉榜奖金
	const V_1=1;
	//试听课奖金
	const V_2=2;
	//90分钟课程补偿
	const V_3=3;
	//工资补偿
	const V_4=4;
	//试听培训奖金
	const V_5=5;
	//伯乐奖
	const V_6=6;

	//荣誉榜奖金
	const S_1="";
	//试听课奖金
	const S_2="";
	//90分钟课程补偿
	const S_3="";
	//工资补偿
	const S_4="";
	//试听培训奖金
	const S_5="";
	//伯乐奖
	const S_6="";



};
