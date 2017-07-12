<?php
//自动生成枚举类  不要手工修改
//source  file: config_flow_type.php
namespace  App\Enums;

class Eflow_type extends \App\Enums\Enum_base
{
	static public $field_name = "flow_type"  ;
	static public $name = "类型"  ;
	 static $desc_map= array(
		1 => "请假",
		1001 => "学生退费",
		1002 => "助教-学生退费",
		2001 => "销售提交试听无需试卷",
		2002 => "特殊折扣申请",
		2003 => "SELLER课程有效申请",
		3001 => "助教-无效课程,学生不扣，付老师工资",
		3002 => "课时转赠",
		4001 => "老师离职申请确认",
	);
	 static $simple_desc_map= array(
		1 => "",
		1001 => "",
		1002 => "",
		2001 => "",
		2002 => "",
		2003 => "",
		3001 => "",
		3002 => "",
		4001 => "",
	);
	 static $s2v_map= array(
		"qingjia" => 1,
		"student_refund" => 1001,
		"ass_order_refund" => 1002,
		"seller_post_test_lesson_without_paper" => 2001,
		"seller_order_require" => 2002,
		"seller_recheck_lesson_sucess" => 2003,
		"ass_lesson_confirm_flag_4" => 3001,
		"order_exchange" => 3002,
		"confirm_teacher_quit" => 4001,
	);
	 static $v2s_map= array(
		 1=>  "qingjia",
		 1001=>  "student_refund",
		 1002=>  "ass_order_refund",
		 2001=>  "seller_post_test_lesson_without_paper",
		 2002=>  "seller_order_require",
		 2003=>  "seller_recheck_lesson_sucess",
		 3001=>  "ass_lesson_confirm_flag_4",
		 3002=>  "order_exchange",
		 4001=>  "confirm_teacher_quit",
	);

	//请假
	const V_1=1;
	//请假
	const V_QINGJIA=1;
	//学生退费
	const V_1001=1001;
	//学生退费
	const V_STUDENT_REFUND=1001;
	//助教-学生退费
	const V_1002=1002;
	//助教-学生退费
	const V_ASS_ORDER_REFUND=1002;
	//销售提交试听无需试卷
	const V_2001=2001;
	//销售提交试听无需试卷
	const V_SELLER_POST_TEST_LESSON_WITHOUT_PAPER=2001;
	//特殊折扣申请
	const V_2002=2002;
	//特殊折扣申请
	const V_SELLER_ORDER_REQUIRE=2002;
	//SELLER课程有效申请
	const V_2003=2003;
	//SELLER课程有效申请
	const V_SELLER_RECHECK_LESSON_SUCESS=2003;
	//助教-无效课程,学生不扣，付老师工资
	const V_3001=3001;
	//助教-无效课程,学生不扣，付老师工资
	const V_ASS_LESSON_CONFIRM_FLAG_4=3001;
	//课时转赠
	const V_3002=3002;
	//课时转赠
	const V_ORDER_EXCHANGE=3002;
	//老师离职申请确认
	const V_4001=4001;
	//老师离职申请确认
	const V_CONFIRM_TEACHER_QUIT=4001;

	//请假
	const S_1="qingjia";
	//请假
	const S_QINGJIA="qingjia";
	//学生退费
	const S_1001="student_refund";
	//学生退费
	const S_STUDENT_REFUND="student_refund";
	//助教-学生退费
	const S_1002="ass_order_refund";
	//助教-学生退费
	const S_ASS_ORDER_REFUND="ass_order_refund";
	//销售提交试听无需试卷
	const S_2001="seller_post_test_lesson_without_paper";
	//销售提交试听无需试卷
	const S_SELLER_POST_TEST_LESSON_WITHOUT_PAPER="seller_post_test_lesson_without_paper";
	//特殊折扣申请
	const S_2002="seller_order_require";
	//特殊折扣申请
	const S_SELLER_ORDER_REQUIRE="seller_order_require";
	//SELLER课程有效申请
	const S_2003="seller_recheck_lesson_sucess";
	//SELLER课程有效申请
	const S_SELLER_RECHECK_LESSON_SUCESS="seller_recheck_lesson_sucess";
	//助教-无效课程,学生不扣，付老师工资
	const S_3001="ass_lesson_confirm_flag_4";
	//助教-无效课程,学生不扣，付老师工资
	const S_ASS_LESSON_CONFIRM_FLAG_4="ass_lesson_confirm_flag_4";
	//课时转赠
	const S_3002="order_exchange";
	//课时转赠
	const S_ORDER_EXCHANGE="order_exchange";
	//老师离职申请确认
	const S_4001="confirm_teacher_quit";
	//老师离职申请确认
	const S_CONFIRM_TEACHER_QUIT="confirm_teacher_quit";

	static public function check_qingjia ($val){
		 return $val == 1;
	}
	static public function check_student_refund ($val){
		 return $val == 1001;
	}
	static public function check_ass_order_refund ($val){
		 return $val == 1002;
	}
	static public function check_seller_post_test_lesson_without_paper ($val){
		 return $val == 2001;
	}
	static public function check_seller_order_require ($val){
		 return $val == 2002;
	}
	static public function check_seller_recheck_lesson_sucess ($val){
		 return $val == 2003;
	}
	static public function check_ass_lesson_confirm_flag_4 ($val){
		 return $val == 3001;
	}
	static public function check_order_exchange ($val){
		 return $val == 3002;
	}
	static public function check_confirm_teacher_quit ($val){
		 return $val == 4001;
	}


};
