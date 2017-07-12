<?php
namespace App\Models\Zgen;
class z_t_seller_student_origin extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_seller_student_origin";


	/*int(11) */
	const C_userid='userid';

	/*varchar(64) */
	const C_origin='origin';

	/*int(11) */
	const C_add_time='add_time';

	/*int(11) */
	const C_subject='subject';
	function get_add_time($userid, $origin ){
		return $this->field_get_value_2( $userid, $origin  , self::C_add_time  );
	}
	function get_subject($userid, $origin ){
		return $this->field_get_value_2( $userid, $origin  , self::C_subject  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="userid";
        $this->field_id2_name="origin";
        $this->field_table_name="db_weiyi.t_seller_student_origin";
  }

    public function field_get_value_2(  $userid, $origin,$field_name ) {
        return parent::field_get_value_2(  $userid, $origin,$field_name ) ;
    }

    public function field_get_list_2( $userid,  $origin,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $userid, $origin,  $set_field_arr ) {
        return parent::field_update_list_2( $userid, $origin,  $set_field_arr );
    }
    public function row_delete_2(  $userid ,$origin ) {
        return parent::row_delete_2( $userid ,$origin );
    }


}
/*
  CREATE TABLE `t_seller_student_origin` (
  `userid` int(11) NOT NULL,
  `origin` varchar(64) COLLATE latin1_bin NOT NULL COMMENT '渠道',
  `add_time` int(11) NOT NULL,
  `subject` int(11) NOT NULL COMMENT '科目',
  PRIMARY KEY (`userid`,`origin`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
