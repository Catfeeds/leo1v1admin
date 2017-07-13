<?php
//自动生成枚举类  不要手工修改
//source  file: config_env.php
namespace  App\Enums;

class Eenv extends \App\Core\Enum_base
{
	static public $field_name = "env"  ;
	static public $name = "env"  ;
	 static $desc_map= array(
		1 => "开发",
		3 => "外网测试",
		4 => "外网正式",
		10 => "phpunit 运行时",
	);
	 static $simple_desc_map= array(
		1 => "",
		3 => "",
		4 => "",
		10 => "",
	);
	 static $s2v_map= array(
		"local" => 1,
		"test" => 3,
		"release" => 4,
		"testing" => 10,
	);
	 static $v2s_map= array(
		 1=>  "local",
		 3=>  "test",
		 4=>  "release",
		 10=>  "testing",
	);

	//开发
	const V_1=1;
	//开发
	const V_LOCAL=1;
	//外网测试
	const V_3=3;
	//外网测试
	const V_TEST=3;
	//外网正式
	const V_4=4;
	//外网正式
	const V_RELEASE=4;
	//phpunit 运行时
	const V_10=10;
	//phpunit 运行时
	const V_TESTING=10;

	//开发
	const S_1="local";
	//开发
	const S_LOCAL="local";
	//外网测试
	const S_3="test";
	//外网测试
	const S_TEST="test";
	//外网正式
	const S_4="release";
	//外网正式
	const S_RELEASE="release";
	//phpunit 运行时
	const S_10="testing";
	//phpunit 运行时
	const S_TESTING="testing";

	static public function check_local ($val){
		 return $val == 1;
	}
	static public function check_test ($val){
		 return $val == 3;
	}
	static public function check_release ($val){
		 return $val == 4;
	}
	static public function check_testing ($val){
		 return $val == 10;
	}


};
