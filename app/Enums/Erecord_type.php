<?php
//自动生成枚举类  不要手工修改
//source  file: config_record_type.php
namespace  App\Enums;

class Erecord_type extends \App\Core\Enum_base
{
	static public $field_name = "record_type"  ;
	static public $name = "record_type"  ;
	 static $desc_map= array(
		1 => "反馈记录",
		2 => "拒接学生记录",
		3 => "排课限制记录",
		4 => "冻结排课记录",
		5 => "分配教务老师教务回访记录",
		6 => "信息变更记录",
		7 => "一周排课数变更记录",
		8 => "试听课未正常上课之反馈",
		9 => "模拟试听审核",
		10 => "老师面试评价",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		6 => "",
		7 => "",
		8 => "",
		9 => "",
		10 => "",
	);
	 static $s2v_map= array(
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
		"" => 6,
		"" => 7,
		"" => 8,
		"" => 9,
		"" => 10,
	);
	 static $v2s_map= array(
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
		 6=>  "",
		 7=>  "",
		 8=>  "",
		 9=>  "",
		 10=>  "",
	);

	//反馈记录
	const V_1=1;
	//拒接学生记录
	const V_2=2;
	//排课限制记录
	const V_3=3;
	//冻结排课记录
	const V_4=4;
	//分配教务老师教务回访记录
	const V_5=5;
	//信息变更记录
	const V_6=6;
	//一周排课数变更记录
	const V_7=7;
	//试听课未正常上课之反馈
	const V_8=8;
	//模拟试听审核
	const V_9=9;
	//老师面试评价
	const V_10=10;

	//反馈记录
	const S_1="";
	//拒接学生记录
	const S_2="";
	//排课限制记录
	const S_3="";
	//冻结排课记录
	const S_4="";
	//分配教务老师教务回访记录
	const S_5="";
	//信息变更记录
	const S_6="";
	//一周排课数变更记录
	const S_7="";
	//试听课未正常上课之反馈
	const S_8="";
	//模拟试听审核
	const S_9="";
	//老师面试评价
	const S_10="";



};
