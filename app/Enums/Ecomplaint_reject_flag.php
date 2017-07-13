<?php
//自动生成枚举类  不要手工修改
//source  file: config_complaint_reject_flag.php
namespace  App\Enums;

class Ecomplaint_reject_flag extends \App\Core\Enum_base
{
	static public $field_name = "complaint_reject_flag"  ;
	static public $name = "complaint_reject_flag"  ;
	 static $desc_map= array(
		0 => "接受",
		1 => "驳回",
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

	//接受
	const V_0=0;
	//驳回
	const V_1=1;

	//接受
	const S_0="";
	//驳回
	const S_1="";



};
