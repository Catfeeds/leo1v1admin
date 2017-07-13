<?php
//自动生成枚举类  不要手工修改
//source  file: config_lesson_deduct.php
namespace  App\Enums;

class Elesson_deduct extends \App\Core\Enum_base
{
	static public $field_name = "lesson_deduct"  ;
	static public $name = "lesson_deduct"  ;
	 static $desc_map= array(
		1 => "老师上课迟到",
		2 => "学生提交作业超时未评价",
		3 => "老师未提前4小时更换上课时间",
		4 => "老师超时评价学生",
		5 => "老师课前未上传学生讲义",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
	);
	 static $s2v_map= array(
		"deduct_come_late" => 1,
		"deduct_check_homework" => 2,
		"deduct_change_class" => 3,
		"deduct_rate_student" => 4,
		"deduct_upload_cw" => 5,
	);
	 static $v2s_map= array(
		 1=>  "deduct_come_late",
		 2=>  "deduct_check_homework",
		 3=>  "deduct_change_class",
		 4=>  "deduct_rate_student",
		 5=>  "deduct_upload_cw",
	);

	//老师上课迟到
	const V_1=1;
	//老师上课迟到
	const V_DEDUCT_COME_LATE=1;
	//学生提交作业超时未评价
	const V_2=2;
	//学生提交作业超时未评价
	const V_DEDUCT_CHECK_HOMEWORK=2;
	//老师未提前4小时更换上课时间
	const V_3=3;
	//老师未提前4小时更换上课时间
	const V_DEDUCT_CHANGE_CLASS=3;
	//老师超时评价学生
	const V_4=4;
	//老师超时评价学生
	const V_DEDUCT_RATE_STUDENT=4;
	//老师课前未上传学生讲义
	const V_5=5;
	//老师课前未上传学生讲义
	const V_DEDUCT_UPLOAD_CW=5;

	//老师上课迟到
	const S_1="deduct_come_late";
	//老师上课迟到
	const S_DEDUCT_COME_LATE="deduct_come_late";
	//学生提交作业超时未评价
	const S_2="deduct_check_homework";
	//学生提交作业超时未评价
	const S_DEDUCT_CHECK_HOMEWORK="deduct_check_homework";
	//老师未提前4小时更换上课时间
	const S_3="deduct_change_class";
	//老师未提前4小时更换上课时间
	const S_DEDUCT_CHANGE_CLASS="deduct_change_class";
	//老师超时评价学生
	const S_4="deduct_rate_student";
	//老师超时评价学生
	const S_DEDUCT_RATE_STUDENT="deduct_rate_student";
	//老师课前未上传学生讲义
	const S_5="deduct_upload_cw";
	//老师课前未上传学生讲义
	const S_DEDUCT_UPLOAD_CW="deduct_upload_cw";

	static public function check_deduct_come_late ($val){
		 return $val == 1;
	}
	static public function check_deduct_check_homework ($val){
		 return $val == 2;
	}
	static public function check_deduct_change_class ($val){
		 return $val == 3;
	}
	static public function check_deduct_rate_student ($val){
		 return $val == 4;
	}
	static public function check_deduct_upload_cw ($val){
		 return $val == 5;
	}


};
