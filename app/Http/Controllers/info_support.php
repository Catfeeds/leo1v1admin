<?php

namespace App\Http\Controllers;
use \App\Http\Controllers\Controller;
use \App\Enums as E;
use \App\Helper\Config;

class info_support extends Controller
{
    use CacheNick;
    var $check_login_flag=true;

    function __construct( ) {
        parent::__construct();
    }

    public function get_books()
    {
        $subject      = $this->get_in_int_val('subject', 1);       
        $province = $this->get_in_int_val('province', -1);
        $city       = $this->get_in_int_val('city', -1);
        $page_info     = $this->get_in_page_info();
        $page_num = $page_info['page_num'];

        $province_range = "";
        if(!$province){
            $province_all = $this->t_info_resource_book->get_province_range();
            if($province_all){
                $page_start = $page_num*5 - 1;
                $page_end = $page_num*5 + 4;
               
                $province_range_str = "";
                for($i = $page_start;$i <= $page_end;$i++){
                    if($province_all[$i]['province']){
                        $province_range_str .= $province_all[$i]['province'].',';
                    }
                }
                if($province_range_str){
                    $province_range = "(".substr($province_range_str,0,-1).")";
                }
            }
            
        }

        $ret_info = $this->t_info_resource_book->get_books($subject,$province,$city,$province_range,$page_info);

        $city_have = 0;
        $list = [];
        $i = 0;
        foreach($ret_info['list'] as $k => $item) {
            $list[$k] = $item;
           
            if($item['city'] != $city_have){
                $city_have = $item['city'];
                $list[$i] = $item;
                $list[$i]['subjec_str'] = E\Esubject::get_desc($item['subject']);        
                //$list[$i]['grade_str'] = E\Egrade::get_desc($item['grade']);  
                $list[$i]['book_arr'][$item['grade']][$item['book']] = E\Eregion_version::get_desc($item['book']);                
                $i ++;

            }else{
                $list[$i]['book_arr'][$item['grade']][$item['book']] = E\Eregion_version::get_desc($item['book']);
            }
        }

        return $this->pageView(__METHOD__, $ret_info,[
            '_publish_version'    => 20180303171440,
        ]);
    }

    private function upload_books($upfile){
        //获取数组里面的值
        $name=$upfile["name"];//上传文件的文件名 
        $type=$upfile["type"];//上传文件的类型 
        $size=$upfile["size"];//上传文件的大小 
        $tmp_name=$upfile["tmp_name"];//上传文件的临时存放路径   
 
        $new_root = dirname(dirname(dirname(dirname(__FILE__))))."/public";
        $file_name = $new_root."/".$name;
        move_uploaded_file($tmp_name,$file_name);//将上传到服务器临时文件夹的文件重新移动到新位置

        $error=$upfile["error"];//上传后系统返回的值 
        if($error==0){ 
            echo "文件上传成功啦！";
        }else{
            echo "上传失败";
        }
        //文件名为文件路径和文件名的拼接字符串
        $objReader = \PHPExcel_IOFactory::createReader('Excel5');//创建读取实例
        /*
         * log()//方法参数
         * $file_name excal文件的保存路径
         */
        $objPHPExcel = $objReader->load($file_name,$encode='utf-8');//加载文件
        $sheet = $objPHPExcel->getSheet(0);//取得sheet(0)表
        $highestRow = $sheet->getHighestRow(); // 取得总行数
        $highestColumn = $sheet->getHighestColumn(); // 取得总列数
        echo $highestRow;
        echo $highestColumn;
    }
}