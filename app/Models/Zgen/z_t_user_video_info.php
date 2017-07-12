<?php
namespace App\Models\Zgen;
class z_t_user_video_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_user_video_info";


	/*int(11) */
	const C_userid='userid';

	/*int(11) */
	const C_lessonid='lessonid';

	/*int(11) */
	const C_time='time';
	function get_lessonid($userid ){
		return $this->field_get_value( $userid , self::C_lessonid );
	}
	function get_time($userid ){
		return $this->field_get_value( $userid , self::C_time );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="userid";
        $this->field_table_name="db_weiyi_admin.t_user_video_info";
  }
    public function field_get_list( $userid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $userid, $set_field_arr) {
        return parent::field_update_list( $userid, $set_field_arr);
    }


    public function field_get_value(  $userid, $field_name ) {
        return parent::field_get_value( $userid, $field_name);
    }

    public function row_delete(  $userid) {
        return parent::row_delete( $userid);
    }

}

/*
  CREATE TABLE `t_user_video_info` (
  `userid` int(11) NOT NULL,
  `lessonid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`lessonid`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
