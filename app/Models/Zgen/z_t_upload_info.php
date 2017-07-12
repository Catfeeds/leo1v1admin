<?php
namespace App\Models\Zgen;
class z_t_upload_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi_admin.t_upload_info";


	/*int(11) */
	const C_postid='postid';

	/*int(11) */
	const C_upload_adminid='upload_adminid';

	/*int(11) */
	const C_upload_time='upload_time';

	/*varchar(255) */
	const C_upload_desc='upload_desc';

	/*int(11) */
	const C_post_flag='post_flag';
	function get_upload_adminid($postid ){
		return $this->field_get_value( $postid , self::C_upload_adminid );
	}
	function get_upload_time($postid ){
		return $this->field_get_value( $postid , self::C_upload_time );
	}
	function get_upload_desc($postid ){
		return $this->field_get_value( $postid , self::C_upload_desc );
	}
	function get_post_flag($postid ){
		return $this->field_get_value( $postid , self::C_post_flag );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="postid";
        $this->field_table_name="db_weiyi_admin.t_upload_info";
  }
    public function field_get_list( $postid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $postid, $set_field_arr) {
        return parent::field_update_list( $postid, $set_field_arr);
    }


    public function field_get_value(  $postid, $field_name ) {
        return parent::field_get_value( $postid, $field_name);
    }

    public function row_delete(  $postid) {
        return parent::row_delete( $postid);
    }

}

/*
  CREATE TABLE `t_upload_info` (
  `postid` int(11) NOT NULL AUTO_INCREMENT COMMENT '批次',
  `upload_adminid` int(11) NOT NULL COMMENT '上传者',
  `upload_time` int(11) NOT NULL COMMENT '上传时间',
  `upload_desc` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '备注',
  `post_flag` int(11) NOT NULL COMMENT '提交标志',
  PRIMARY KEY (`postid`),
  KEY `db_weiyi_admin_t_upload_info_upload_adminid_upload_time_index` (`upload_adminid`,`upload_time`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
