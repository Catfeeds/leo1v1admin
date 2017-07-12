<?php
namespace App\Models\Zgen;
class z_t_user_lesson_account_lesson extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_user_lesson_account_lesson";


	/*int(10) unsigned */
	const C_lesson_account_id='lesson_account_id';

	/*int(10) unsigned */
	const C_lessonid='lessonid';

	/*int(10) unsigned */
	const C_lesson_num='lesson_num';

	/*int(10) unsigned */
	const C_price='price';

	/*int(10) unsigned */
	const C_real_price='real_price';

	/*char(255) */
	const C_reason='reason';
	function get_lesson_num($lesson_account_id, $lessonid ){
		return $this->field_get_value_2( $lesson_account_id, $lessonid  , self::C_lesson_num  );
	}
	function get_price($lesson_account_id, $lessonid ){
		return $this->field_get_value_2( $lesson_account_id, $lessonid  , self::C_price  );
	}
	function get_real_price($lesson_account_id, $lessonid ){
		return $this->field_get_value_2( $lesson_account_id, $lessonid  , self::C_real_price  );
	}
	function get_reason($lesson_account_id, $lessonid ){
		return $this->field_get_value_2( $lesson_account_id, $lessonid  , self::C_reason  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="lesson_account_id";
        $this->field_id2_name="lessonid";
        $this->field_table_name="db_weiyi.t_user_lesson_account_lesson";
  }

    public function field_get_value_2(  $lesson_account_id, $lessonid,$field_name ) {
        return parent::field_get_value_2(  $lesson_account_id, $lessonid,$field_name ) ;
    }

    public function field_get_list_2( $lesson_account_id,  $lessonid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $lesson_account_id, $lessonid,  $set_field_arr ) {
        return parent::field_update_list_2( $lesson_account_id, $lessonid,  $set_field_arr );
    }
    public function row_delete_2(  $lesson_account_id ,$lessonid ) {
        return parent::row_delete_2( $lesson_account_id ,$lessonid );
    }


}
/*
  CREATE TABLE `t_user_lesson_account_lesson` (
  `lesson_account_id` int(10) unsigned NOT NULL,
  `lessonid` int(10) unsigned NOT NULL,
  `lesson_num` int(10) unsigned NOT NULL,
  `price` int(10) unsigned NOT NULL,
  `real_price` int(10) unsigned NOT NULL COMMENT '最终价格',
  `reason` char(255) NOT NULL COMMENT '原因',
  PRIMARY KEY (`lesson_account_id`,`lessonid`),
  UNIQUE KEY `lesson_account_id` (`lesson_account_id`,`lesson_num`),
  KEY `lessonid` (`lessonid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
