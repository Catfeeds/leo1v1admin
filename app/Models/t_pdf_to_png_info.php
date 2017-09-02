<?php
namespace App\Models;
use \App\Enums as E;
class t_pdf_to_png_info extends \App\Models\Zgen\z_t_pdf_to_png_info
{
	public function __construct()
	{
		parent::__construct();
	}


    public function get_pdf_list_for_doing (){
        $sql = $this->gen_sql_new(" select id, pdf_url, lessonid from %s tp where id_do_flag=0"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

}











