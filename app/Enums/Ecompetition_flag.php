<?php
//自动生成枚举类  不要手工修改
//source  file: config_competition_flag.php
namespace  App\Enums;

class Ecompetition_flag extends \App\Core\Enum_base
{
	static public $field_name = "competition_flag"  ;
	static public $name = "competition_flag"  ;
	 static $desc_map= array(
		0 => "常规课",
		1 => "奥赛课",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
	);

	//常规课
	const V_0=0;
	//奥赛课
	const V_1=1;

	//常规课
	const S_0="";
	//奥赛课
	const S_1="";



};
