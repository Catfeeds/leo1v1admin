<?php
//自动生成枚举类  不要手工修改
//source  file: config_seller_work_status.php
namespace  App\Enums;

class Eseller_work_status extends \App\Enums\Enum_base
{
	static public $field_name = "seller_work_status"  ;
	static public $name = "seller_work_status"  ;
	 static $desc_map= array(
		0 => "休息",
		1 => "上班",
		2 => "请假",
		3 => "加班",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
	);

	//休息
	const V_0=0;
	//上班
	const V_1=1;
	//请假
	const V_2=2;
	//加班
	const V_3=3;

	//休息
	const S_0="";
	//上班
	const S_1="";
	//请假
	const S_2="";
	//加班
	const S_3="";



};
