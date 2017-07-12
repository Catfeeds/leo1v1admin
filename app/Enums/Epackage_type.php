<?php
//自动生成枚举类  不要手工修改
//source  file: config_package_type.php
namespace  App\Enums;

class Epackage_type extends \App\Enums\Enum_base
{
	static public $field_name = "package_type"  ;
	static public $name = "package_type"  ;
	 static $desc_map= array(
		1 => "1v1试听课",
		2 => "1v1定制课",
		3 => "1v1自选课",
		1001 => "普通公开课",
		2001 => "普通答疑课",
		3001 => "普通小班课",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		1001 => "",
		2001 => "",
		3001 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 1001,
		"" => 2001,
		"" => 3001,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 1001=>  "",
		 2001=>  "",
		 3001=>  "",
	);

	//1v1试听课
	const V_1=1;
	//1v1定制课
	const V_2=2;
	//1v1自选课
	const V_3=3;
	//普通公开课
	const V_1001=1001;
	//普通答疑课
	const V_2001=2001;
	//普通小班课
	const V_3001=3001;

	//1v1试听课
	const S_1="";
	//1v1定制课
	const S_2="";
	//1v1自选课
	const S_3="";
	//普通公开课
	const S_1001="";
	//普通答疑课
	const S_2001="";
	//普通小班课
	const S_3001="";



};
