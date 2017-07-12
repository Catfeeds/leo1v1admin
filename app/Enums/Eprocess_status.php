<?php
//自动生成枚举类  不要手工修改
//source  file: config_process_status.php
namespace  App\Enums;

class Eprocess_status extends \App\Enums\Enum_base
{
	static public $field_name = "process_status"  ;
	static public $name = "process_status"  ;
	 static $desc_map= array(
		0 => "a,b",
		1 => "a,b",
		2 => "a,c,d",
		3 => "a,f,g",
		4 => "a,b,g",
		5 => "a,e",
		6 => "a,b",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		6 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
		"" => 6,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
		 6=>  "",
	);

	//a,b
	const V_0=0;
	//a,b
	const V_1=1;
	//a,c,d
	const V_2=2;
	//a,f,g
	const V_3=3;
	//a,b,g
	const V_4=4;
	//a,e
	const V_5=5;
	//a,b
	const V_6=6;

	//a,b
	const S_0="";
	//a,b
	const S_1="";
	//a,c,d
	const S_2="";
	//a,f,g
	const S_3="";
	//a,b,g
	const S_4="";
	//a,e
	const S_5="";
	//a,b
	const S_6="";



};
