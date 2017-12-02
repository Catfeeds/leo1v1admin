<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use Illuminate\Support\Facades\Mail ;

use Qiniu\Auth;

use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

use App\Jobs\deal_pdf_to_image;

require_once  app_path("/Libs/Qiniu/functions.php");

class test_bacon extends Controller
{
    use CacheNick;

    public function __construct(){
        parent::__construct();
    }

    public function get_menu_list($power_map)  {
        $start    = 1000000;
        $menu     = \App\Helper\Config::get_menu();
        $stu_menu = \App\Helper\Config::get_stu_menu();
        $tea_menu = \App\Helper\Config::get_tea_menu();


        $list=$this->get_menu_power_list($power_map,$menu,$start );
        $sub_menu=[
            ["power_id"=>1, "name"=>"子栏-学生信息", "list"=> $stu_menu],
            ["power_id"=>2, "name"=>"子栏-老师信息", "list"=> $tea_menu],
        ];
        $sub_list=$this->get_menu_power_list ($power_map,$sub_menu,$start*2 );

        $class_list=$this->get_menu_power_list($power_map,\App\ClassMenu\menu::get_config()  ,$start*3 );

        return array_merge($list, $class_list ,$sub_list);
    }

    private function gen_class($level) {
        global $g_l_id;
        if(!$g_l_id) {
            $g_l_id=0;
        }
        $g_l_id++;
        return "n_{$level}_$g_l_id";

    }

    private function get_menu_power_list ($power_map, $menu ,$start) {
        $list=[];
        $gen_n=function($item,$pid,$k_class,$level,$class_level_fix )use( $power_map) {
            $n             = ["k1"=>"----","k2"=> $level>=2?"----":"","k3"=> $level>=3?"----":"" ];
            $n["k$level" ] = $item["name"] ;
            $n["folder" ]  = isset($item["list"] ) ;
            if ( $n["folder" ]) {
                $n["pid" ]= 0;
            }else{
                $n["pid" ]= $pid;
            }
            $n["k_class" ]       = $k_class;
            $n["class" ]         = "l_$level $k_class $class_level_fix " ;
            $n["level" ]         = $level ;
            $n["has_power_flag"] = isset($power_map[$pid])?"checked":"";
            $n["url"]            = @$item["url"] ;
            return $n;
        };

        foreach ($menu as $k1_item )  {
            $k1_pid=0;
            if ( isset($k1_item["power_id"])) {
                $k1_pid=$start+$k1_item["power_id"] *10000;
            }
            $k1_class= $this->gen_class(1);
            $n=$gen_n($k1_item,$k1_pid,$k1_class,1,"" );

            $list[]=$n;

            if (isset($k1_item["list"] )) {
                foreach ( $k1_item["list"]  as $k2_item) {
                    $k2_pid=0;
                    if ( isset($k2_item["power_id"])) {
                        $k2_pid=$k1_pid +$k2_item["power_id"] *100;
                    }
                    $k2_class= $this->gen_class(2);
                    $n=$gen_n($k2_item,$k2_pid,$k2_class,2 , "$k1_class" );
                    $list[]=$n;

                    if (isset($k2_item["list"] )) {
                        foreach ( $k2_item["list"]  as $k3_item) {
                            $k3_pid=0;
                            if ( isset($k3_item["power_id"])) {
                                $k3_pid=$k2_pid +$k3_item["power_id"] ;
                            }
                            $k3_class= $this->gen_class(3);
                            $n=$gen_n($k3_item,$k3_pid,$k3_class,3, "$k1_class $k2_class"  );
                            $list[]=$n;
                        }
                    }
                }
            }
        }
        return $list;
    }


    public function power_group_edit() {
        
        $sql = "select * from db_weiyi_admin.t_authority_group where groupid in (57,38,74,77,104,129,133,94,102)";
        // $group_arr = [
        //     ['groupid'=>57],
        //     ['groupid'=>38],
        //     ['groupid'=>74],
        //     ['groupid'=>77],
        //     ['groupid'=>104],
        //     ['groupid'=>129],
        //     ['groupid'=>133],
        //     ['groupid'=>94],
        //     ['groupid'=>102],
        // ];
        $group_arr = "( 57,38,74,77,104,129,133,94,102 )";
        $auth_arr = $this->t_authority_group->get_auth_group_more($group_arr);
        
        $auth_str = '';
        foreach( $auth_arr as $auth){
            $auth_str .= $auth['group_authority'];
            //dd($arr);
        }
       
        $arr = explode(',', $auth_str);
        $arr = array_unique($arr);
        
        foreach($arr as $k=>$v){
            $power_map[$v] = true;
        }
        
        $list=$this->get_menu_list($power_map );

        $n=["k1"=>"","k2"=>"","k3"=>"" ];
        $n["k1" ]= "其它";
        $n["pid" ]= 0;
        $k1_class= $this->gen_class(1);
        $n["k_class" ]= $k1_class;
        $n["class" ]=  "l_1 $k1_class " ;
        $n["level" ]=  "1" ;
        $n["folder" ]=  true;
        $n["has_power_flag" ]= "" ;
        $list[]=$n;

        foreach (E\Epower::$desc_map as $k=> $v) {
            $n=["k1"=>"----","k2"=>"","k3"=>"" ];
            $k2_pid=$k;
            $n["k2" ]= $v ;
            $n["pid" ]= $k2_pid;
            $k2_class= $this->gen_class(2);
            $n["k_class" ]= $k2_class;
            $n["class" ]= "l_2 $k1_class $k2_class";
            $n["level" ]=  "2" ;
            $n["folder" ]=  false;
            $n["has_power_flag" ]= isset($power_map["$k2_pid"])?"checked":"" ;
            $list[]=$n;
        }
        $ret = @\App\Helper\Utils::list_to_page_info($list)['list'];
        $power_menu = [];
        foreach($ret as $item){
            if( ( @$item['has_power_flag'] == 'checked' && @$item['url'] != '' ) || ( @$item['level'] == 1 ) ){
                $power_menu[] = $item;
            }
        }
        //dd($power_menu);
        $this-> download_xls($power_menu);
        //dd($ret);
      
    }
    public function download_xls ($ret)  {
        $xls_data= $ret;

        if(!is_array($xls_data)) {
            return $this->output_err("download error");
        }

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("jim ")
             ->setLastModifiedBy("jim")
             ->setSubject("jim subject")
             ->setDescription("jim Desc")
             ->setKeywords("jim key")
             ->setCategory("jim  category");

        //dd($xls_data);
     
        foreach( $xls_data as $index=> $item ) {
            $index_str = $index+1;
            $objPHPExcel->getActiveSheet()
                 ->setCellValue('A'.$index_str, @$item['k1'])
                 ->setCellValue('B'.$index_str, @$item['k2'])
                 ->setCellValue('C'.$index_str, @$item['k3'])
                 ->setCellValue('D'.$index_str, @$item['url']);
        }
        
        //$objPHPExcel->getActiveSheet()->setCellValue('A1','haode');

        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.time().'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    } 
    public function test_img(){
        $wx_openid = 'oAJiDwJsZROYopRIpIUmHD6GCIYE';
        $phone = 18898881852;
        $agent = $this->t_agent->get_agent_info_by_openid($wx_openid);
        $agent['wx_openid'] = 'oAJiDwJsZROYopRIpIUmHD6GCIYE';
        $agent['phone'] = 18898881852;
        $agent['id'] = 45;
        //$phone = uniqid();
        $bg_url       = "http://7u2f5q.com2.z0.glb.qiniucdn.com/4fa4f2970f6df4cf69bc37f0391b14751506672309999.png";
        $qr_code_url = "http://www.leo1v1.com/market-invite/index.html?p_phone=$phone&type=2";

        $request = ['fromusername'=>'tset'];

        // \App\Helper\Utils::wx_make_and_send_img($bg_url,$qr_code_url,$request,$agent,1);
        $task=new \App\Jobs\make_and_send_wx_img($wx_openid,$bg_url,$qr_code_url,$request,$agent);
        $task->handle();

    }
  
}
