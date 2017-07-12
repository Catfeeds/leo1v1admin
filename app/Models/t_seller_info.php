<?php
namespace App\Models;
class t_seller_info extends \App\Models\Zgen\z_t_seller_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_sell_list_for_select($gender, $nick_phone,  $page_num)
	{
        $where_arr = array(
            array( "gender=%d", $gender, -1 ),
        );
        if ($nick_phone!=""){
            $where_arr[]=sprintf( "(nick like '%%%s%%' or  phone like '%%%s%%' )",
                                    $this->ensql($nick_phone),
                                    $this->ensql($nick_phone)
            );
        }
        
		$sql = sprintf("select sellerid as id , nick, phone  from %s  where %s",
					   self::DB_TABLE_NAME,
                       $this->where_str_gen( $where_arr)
        );
		return $this->main_get_list_by_page($sql,$page_num,TEA_PER_PAGE);
	}

 


}











