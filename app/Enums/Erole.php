<?php
//自动生成枚举类  不要手工修改
//source  file: config_role.php
namespace  App\Enums;

class Erole extends \App\Enums\Enum_base
{
	static public $field_name = "role"  ;
	static public $name = "role"  ;
	 static $desc_map= array(
		1 => "学生",
		2 => "老师",
		3 => "助教",
		4 => "家长",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
	);
	 static $s2v_map= array(
		"student" => 1,
		"teacher" => 2,
		"assistent" => 3,
		"parent" => 4,
	);
	 static $v2s_map= array(
		 1=>  "student",
		 2=>  "teacher",
		 3=>  "assistent",
		 4=>  "parent",
	);

	//学生
	const V_1=1;
	//学生
	const V_STUDENT=1;
	//老师
	const V_2=2;
	//老师
	const V_TEACHER=2;
	//助教
	const V_3=3;
	//助教
	const V_ASSISTENT=3;
	//家长
	const V_4=4;
	//家长
	const V_PARENT=4;

	//学生
	const S_1="student";
	//学生
	const S_STUDENT="student";
	//老师
	const S_2="teacher";
	//老师
	const S_TEACHER="teacher";
	//助教
	const S_3="assistent";
	//助教
	const S_ASSISTENT="assistent";
	//家长
	const S_4="parent";
	//家长
	const S_PARENT="parent";

	static public function check_student ($val){
		 return $val == 1;
	}
	static public function check_teacher ($val){
		 return $val == 2;
	}
	static public function check_assistent ($val){
		 return $val == 3;
	}
	static public function check_parent ($val){
		 return $val == 4;
	}


};
