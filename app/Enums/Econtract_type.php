<?php
//自动生成枚举类  不要手工修改
//source  file: config_contract_type.php
namespace  App\Enums;

class Econtract_type extends \App\Core\Enum_base
{
	static public $field_name = "contract_type"  ;
	static public $name = "contract_type"  ;
	 static $desc_map= array(
		0 => "常规",
		1 => "赠送",
		2 => "试听",
		3 => "续费",
		1001 => "普通公开课(A)",
		1002 => "普通公开课(B)",
		1003 => "高级公开课",
		1100 => "培训课程",
		2001 => "公开答疑",
		3001 => "小班课",
		4001 => "机器人课程",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		1001 => "",
		1002 => "",
		1003 => "",
		1100 => "",
		2001 => "",
		3001 => "",
		4001 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"open_class" => 1001,
		"" => 1002,
		"" => 1003,
		"" => 1100,
		"" => 2001,
		"small_class" => 3001,
		"copy_open_class" => 4001,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 1001=>  "open_class",
		 1002=>  "",
		 1003=>  "",
		 1100=>  "",
		 2001=>  "",
		 3001=>  "small_class",
		 4001=>  "copy_open_class",
	);

	//常规
	const V_0=0;
	//赠送
	const V_1=1;
	//试听
	const V_2=2;
	//续费
	const V_3=3;
	//普通公开课(A)
	const V_1001=1001;
	//普通公开课(A)
	const V_OPEN_CLASS=1001;
	//普通公开课(B)
	const V_1002=1002;
	//高级公开课
	const V_1003=1003;
	//培训课程
	const V_1100=1100;
	//公开答疑
	const V_2001=2001;
	//小班课
	const V_3001=3001;
	//小班课
	const V_SMALL_CLASS=3001;
	//机器人课程
	const V_4001=4001;
	//机器人课程
	const V_COPY_OPEN_CLASS=4001;

	//常规
	const S_0="";
	//赠送
	const S_1="";
	//试听
	const S_2="";
	//续费
	const S_3="";
	//普通公开课(A)
	const S_1001="open_class";
	//普通公开课(A)
	const S_OPEN_CLASS="open_class";
	//普通公开课(B)
	const S_1002="";
	//高级公开课
	const S_1003="";
	//培训课程
	const S_1100="";
	//公开答疑
	const S_2001="";
	//小班课
	const S_3001="small_class";
	//小班课
	const S_SMALL_CLASS="small_class";
	//机器人课程
	const S_4001="copy_open_class";
	//机器人课程
	const S_COPY_OPEN_CLASS="copy_open_class";

	static public function check_open_class ($val){
		 return $val == 1001;
	}
	static public function check_small_class ($val){
		 return $val == 3001;
	}
	static public function check_copy_open_class ($val){
		 return $val == 4001;
	}


};
