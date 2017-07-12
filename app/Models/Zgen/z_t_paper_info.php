<?php
namespace App\Models\Zgen;
class z_t_paper_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_tool.t_paper_info";


	/*int(10) */
	const C_paperid='paperid';

	/*varchar(128) */
	const C_paper_name='paper_name';

	/*int(10) */
	const C_paper_year='paper_year';

	/*int(10) */
	const C_paper_type='paper_type';

	/*varchar(50) */
	const C_paper_sub_type='paper_sub_type';

	/*int(10) */
	const C_subject='subject';

	/*varchar(1024) */
	const C_paper_url='paper_url';

	/*int(10) */
	const C_area_id='area_id';

	/*varchar(50) */
	const C_add_time='add_time';

	/*int(11) */
	const C_paper_down='paper_down';
	function get_paper_name($paperid ){
		return $this->field_get_value( $paperid , self::C_paper_name );
	}
	function get_paper_year($paperid ){
		return $this->field_get_value( $paperid , self::C_paper_year );
	}
	function get_paper_type($paperid ){
		return $this->field_get_value( $paperid , self::C_paper_type );
	}
	function get_paper_sub_type($paperid ){
		return $this->field_get_value( $paperid , self::C_paper_sub_type );
	}
	function get_subject($paperid ){
		return $this->field_get_value( $paperid , self::C_subject );
	}
	function get_paper_url($paperid ){
		return $this->field_get_value( $paperid , self::C_paper_url );
	}
	function get_area_id($paperid ){
		return $this->field_get_value( $paperid , self::C_area_id );
	}
	function get_add_time($paperid ){
		return $this->field_get_value( $paperid , self::C_add_time );
	}
	function get_paper_down($paperid ){
		return $this->field_get_value( $paperid , self::C_paper_down );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="paperid";
        $this->field_table_name="db_tool.t_paper_info";
  }
    public function field_get_list( $paperid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $paperid, $set_field_arr) {
        return parent::field_update_list( $paperid, $set_field_arr);
    }


    public function field_get_value(  $paperid, $field_name ) {
        return parent::field_get_value( $paperid, $field_name);
    }

    public function row_delete(  $paperid) {
        return parent::row_delete( $paperid);
    }

}

/*
  CREATE TABLE `t_paper_info` (
  `paperid` int(10) NOT NULL AUTO_INCREMENT COMMENT '试卷id',
  `paper_name` varchar(128) NOT NULL COMMENT '试卷名',
  `paper_year` int(10) DEFAULT NULL COMMENT '年份',
  `paper_type` int(10) NOT NULL COMMENT '试卷类别：1小升初，2中考',
  `paper_sub_type` varchar(50) DEFAULT NULL,
  `subject` int(10) NOT NULL COMMENT '试卷科目：语文，数学，英语，物理，化学，生物',
  `paper_url` varchar(1024) DEFAULT NULL COMMENT 'url',
  `area_id` int(10) NOT NULL COMMENT '区域id',
  `add_time` varchar(50) NOT NULL COMMENT 'ä¸Šä¼ æ—¥æœŸ',
  `paper_down` int(11) DEFAULT '0' COMMENT 'ä¸‹è½½é‡',
  PRIMARY KEY (`paperid`)
) ENGINE=InnoDB AUTO_INCREMENT=515 DEFAULT CHARSET=latin1
 */
