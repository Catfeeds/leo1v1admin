<?php
//自动生成枚举类  不要手工修改
//source  file: config_tq_called_flag.php
namespace  App\Enums;

class Etq_called_flag extends \App\Core\Enum_base
{
	static public $field_name = "tq_called_flag"  ;
	static public $name = "TQ"  ;
	 static $desc_map= array(
		0 => "未联系",
		1 => "未拨通",
		2 => "已拨通",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
	);

	//未联系
	const V_0=0;
	//未拨通
	const V_1=1;
	//已拨通
	const V_2=2;

	//未联系
	const S_0="";
	//未拨通
	const S_1="";
	//已拨通
	const S_2="";



};
