<?php
namespace App\Models\Zgen;
class z_t_teacher_assess extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_teacher_assess";


	/*int(11) */
	const C_teacherid='teacherid';

	/*int(11) */
	const C_assess_time='assess_time';

	/*int(11) */
	const C_assess_adminid='assess_adminid';

	/*varchar(255) */
	const C_content='content';

	/*int(11) */
	const C_assess_res='assess_res';

	/*varchar(255) */
	const C_advise_reason='advise_reason';
	function get_assess_adminid($teacherid, $assess_time ){
		return $this->field_get_value_2( $teacherid, $assess_time  , self::C_assess_adminid  );
	}
	function get_content($teacherid, $assess_time ){
		return $this->field_get_value_2( $teacherid, $assess_time  , self::C_content  );
	}
	function get_assess_res($teacherid, $assess_time ){
		return $this->field_get_value_2( $teacherid, $assess_time  , self::C_assess_res  );
	}
	function get_advise_reason($teacherid, $assess_time ){
		return $this->field_get_value_2( $teacherid, $assess_time  , self::C_advise_reason  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="teacherid";
        $this->field_id2_name="assess_time";
        $this->field_table_name="db_weiyi.t_teacher_assess";
  }

    public function field_get_value_2(  $teacherid, $assess_time,$field_name ) {
        return parent::field_get_value_2(  $teacherid, $assess_time,$field_name ) ;
    }

    public function field_get_list_2( $teacherid,  $assess_time,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $teacherid, $assess_time,  $set_field_arr ) {
        return parent::field_update_list_2( $teacherid, $assess_time,  $set_field_arr );
    }
    public function row_delete_2(  $teacherid ,$assess_time ) {
        return parent::row_delete_2( $teacherid ,$assess_time );
    }


}
/*
  CREATE TABLE `t_teacher_assess` (
  `teacherid` int(11) NOT NULL,
  `assess_time` int(11) NOT NULL,
  `assess_adminid` int(11) NOT NULL,
  `content` varchar(255) COLLATE latin1_bin NOT NULL,
  `assess_res` int(11) NOT NULL,
  `advise_reason` varchar(255) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`teacherid`,`assess_time`),
  KEY `t_teacher_assess_assess_adminid_index` (`assess_adminid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
