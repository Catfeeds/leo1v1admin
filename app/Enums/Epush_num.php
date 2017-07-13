<?php
//自动生成枚举类  不要手工修改
//source  file: config_push_num.php
namespace  App\Enums;

class Epush_num extends \App\Core\Enum_base
{
	static public $field_name = "push_num"  ;
	static public $name = "push_num"  ;
	 static $desc_map= array(
		0 => "普通推送",
		1 => "上课推送",
		2 => "pdf作业",
		3 => "题库作业",
		4 => "视频推送",
		5 => "师批改作业",
		6 => "订单支付",
		7 => "反馈报告",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		6 => "",
		7 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
		"" => 6,
		"" => 7,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
		 6=>  "",
		 7=>  "",
	);

	//普通推送
	const V_0=0;
	//上课推送
	const V_1=1;
	//pdf作业
	const V_2=2;
	//题库作业
	const V_3=3;
	//视频推送
	const V_4=4;
	//师批改作业
	const V_5=5;
	//订单支付
	const V_6=6;
	//反馈报告
	const V_7=7;

	//普通推送
	const S_0="";
	//上课推送
	const S_1="";
	//pdf作业
	const S_2="";
	//题库作业
	const S_3="";
	//视频推送
	const S_4="";
	//师批改作业
	const S_5="";
	//订单支付
	const S_6="";
	//反馈报告
	const S_7="";



};
