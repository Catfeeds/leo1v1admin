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

    public function get_power(){
        $file = $this->get_in_str_val('file');
        $data = $this->get_in_str_val('data');
        if($data){
            $this->download_power($data,$file);
        }
        //$file = $file.'.blade.php';
        //$file = $file.'.html';
        return view("test_bacon.".$file);  

    }

    public function download_power($data,$file) {
        //$data = $this->get_in_str_val('data');
        $xls_data= json_decode($data,true);
        //dd($xls_data);
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
            switch($item['level'])
            {
            case 1:
                $hang = 'A'.$index_str;
                break;
            case 2:
                $hang = 'B'.$index_str;
                break;
            case 3:
                $hang = 'C'.$index_str;
                break;
            case 4:
                $hang = 'D'.$index_str;
                break;
            case 5:
                $hang = 'E'.$index_str;
                break;
            case 6:
                $hang = 'F'.$index_str;
                break;
            case 7:
                $hang = 'G'.$index_str;
                break;
            case 8:
                $hang = 'H'.$index_str;
                break;
            case 9:
                $hang = 'I'.$index_str;
                break;
            case 10:
                $hang = 'J'.$index_str;
                break;

            default:
                $hang = 'A'.$index_str;
                break;

            };
            $objPHPExcel->getActiveSheet()->setCellValue($hang, @$item['name']);
        }

        //$objPHPExcel->getActiveSheet()->setCellValue('A1','haode');

        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$file.'.xls"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    }


    public function test_lang(){

        $data = [];
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $checkUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output);
        //打印获得的数据
        dd($output);

    }

    public function test_ajax_more(){
        return $this->Pageview(__METHOD__); 
    }

    public function test_select(){
        return $this->Pageview(__METHOD__); 
    }


    public function get_books(){
        $ret  = \App\Helper\Utils::list_to_page_info([]);
        $data = [];
        $re_book = E\Eregion_version::$desc_map;
        foreach( $re_book as $k => $v){
            $data[] = [
                'resource_id' => $k,
                'resource_type' => $v
            ];
        }

        $ret['list'] = $data;
        return $this->output_ajax_table($ret);

    }
}
