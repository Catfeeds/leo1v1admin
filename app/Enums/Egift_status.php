<?php
//自动生成枚举类  不要手工修改
//source  file: config_gift_status.php
namespace  App\Enums;

class Egift_status extends \App\Core\Enum_base
{
	static public $field_name = "gift_status"  ;
	static public $name = "gift_status"  ;
	 static $desc_map= array(
		0 => "待处理",
		1 => "已发货",
		2 => "已收货",
		3 => "已锁定",
		4 => "已取消",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
	);

	//待处理
	const V_0=0;
	//已发货
	const V_1=1;
	//已收货
	const V_2=2;
	//已锁定
	const V_3=3;
	//已取消
	const V_4=4;

	//待处理
	const S_0="";
	//已发货
	const S_1="";
	//已收货
	const S_2="";
	//已锁定
	const S_3="";
	//已取消
	const S_4="";



};
