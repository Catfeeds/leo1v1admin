<?php
namespace App\Models\Zgen;
class z_t_gift_info  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_gift_info";


	/*int(10) unsigned */
	const C_giftid='giftid';

	/*tinyint(4) */
	const C_gift_type='gift_type';

	/*varchar(100) */
	const C_gift_name='gift_name';

	/*varchar(1024) */
	const C_gift_intro='gift_intro';

	/*int(10) unsigned */
	const C_primary_praise='primary_praise';

	/*int(10) unsigned */
	const C_current_praise='current_praise';

	/*varchar(100) */
	const C_gift_pic='gift_pic';

	/*int(10) unsigned */
	const C_primary_num='primary_num';

	/*int(10) unsigned */
	const C_current_num='current_num';

	/*int(10) unsigned */
	const C_gift_flag='gift_flag';

	/*int(10) unsigned */
	const C_valid_start='valid_start';

	/*int(10) unsigned */
	const C_valid_end='valid_end';

	/*int(10) unsigned */
	const C_per_num='per_num';

	/*tinyint(4) */
	const C_gift_status='gift_status';

	/*timestamp */
	const C_last_modified_time='last_modified_time';

	/*tinyint(4) */
	const C_del_flag='del_flag';

	/*varchar(1024) */
	const C_gift_desc='gift_desc';
	function get_gift_type($giftid ){
		return $this->field_get_value( $giftid , self::C_gift_type );
	}
	function get_gift_name($giftid ){
		return $this->field_get_value( $giftid , self::C_gift_name );
	}
	function get_gift_intro($giftid ){
		return $this->field_get_value( $giftid , self::C_gift_intro );
	}
	function get_primary_praise($giftid ){
		return $this->field_get_value( $giftid , self::C_primary_praise );
	}
	function get_current_praise($giftid ){
		return $this->field_get_value( $giftid , self::C_current_praise );
	}
	function get_gift_pic($giftid ){
		return $this->field_get_value( $giftid , self::C_gift_pic );
	}
	function get_primary_num($giftid ){
		return $this->field_get_value( $giftid , self::C_primary_num );
	}
	function get_current_num($giftid ){
		return $this->field_get_value( $giftid , self::C_current_num );
	}
	function get_gift_flag($giftid ){
		return $this->field_get_value( $giftid , self::C_gift_flag );
	}
	function get_valid_start($giftid ){
		return $this->field_get_value( $giftid , self::C_valid_start );
	}
	function get_valid_end($giftid ){
		return $this->field_get_value( $giftid , self::C_valid_end );
	}
	function get_per_num($giftid ){
		return $this->field_get_value( $giftid , self::C_per_num );
	}
	function get_gift_status($giftid ){
		return $this->field_get_value( $giftid , self::C_gift_status );
	}
	function get_last_modified_time($giftid ){
		return $this->field_get_value( $giftid , self::C_last_modified_time );
	}
	function get_del_flag($giftid ){
		return $this->field_get_value( $giftid , self::C_del_flag );
	}
	function get_gift_desc($giftid ){
		return $this->field_get_value( $giftid , self::C_gift_desc );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="giftid";
        $this->field_table_name="db_weiyi.t_gift_info";
  }
    public function field_get_list( $giftid, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $giftid, $set_field_arr) {
        return parent::field_update_list( $giftid, $set_field_arr);
    }


    public function field_get_value(  $giftid, $field_name ) {
        return parent::field_get_value( $giftid, $field_name);
    }

    public function row_delete(  $giftid) {
        return parent::row_delete( $giftid);
    }

}

/*
  CREATE TABLE `t_gift_info` (
  `giftid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '礼品id',
  `gift_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1实物, 2虚拟物品(phone),3虚拟物品(qq)',
  `gift_name` varchar(100) NOT NULL DEFAULT '' COMMENT '礼品名称',
  `gift_intro` varchar(1024) NOT NULL DEFAULT '' COMMENT '礼品简介',
  `primary_praise` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '原始需要赞的个数',
  `current_praise` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '现在需要赞的个数',
  `gift_pic` varchar(100) NOT NULL DEFAULT '' COMMENT '礼品图片',
  `primary_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '原始数量',
  `current_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '可兑换数量',
  `gift_flag` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '礼品属性 1普通 2最热 3最多 4最新',
  `valid_start` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '有效期开始',
  `valid_end` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '有效期结束',
  `per_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每个人可兑换数量',
  `gift_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0未上架, 1可兑换,2已下架',
  `last_modified_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '最后修改时间',
  `del_flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 not deleted 1 deleted',
  `gift_desc` varchar(1024) NOT NULL DEFAULT '' COMMENT '图片描述',
  PRIMARY KEY (`giftid`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8
 */
