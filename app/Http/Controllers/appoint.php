<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;
use Illuminate\Support\Facades\Cookie ;
use \App\Helper\Config;


class appoint extends Controller
{

    public function index2()
    {
        $package_type = $this->get_in_str_val('package_type',-1);
        $page_num     = $this->get_in_page_num();

        $ret_info = $this->t_appointment_info->get_appoint_course_list($package_type,$page_num);
        foreach($ret_info['list'] as &$item){
            $item['subject']      = E\Esubject::get_desc ($item['subject' ]) ;
            $grade_str        = explode(',',$item['grade' ]) ;
            foreach($grade_str as &$val){
                $val=E\Ebook_grade::get_desc ($val);
            }
            $item['grade']            = implode(',',$grade_str);
            $item['package_type_str'] = E\Epackage_type::get_desc($item['package_type']);
            $item['package_tags']     = str_replace('|','   ',$item['package_tags']);
            $item['package_deadline'] = $item['package_deadline']>0?Utils::unixtime2date( $item['package_deadline']):"不限制";
        }
        $tea_list     = $this->t_teacher_info->get_teacher_simple_list();
        
        return $this->pageView(__METHOD__,$ret_info,['tea_list'=>$tea_list],[
            'qiniu_upload_domain_url'=>Config::get_qiniu_public_url()."/"
        ]);
    }

    public function get_appoint_course()
    {
        $page_num = $this->get_in_page_num();

        $ret_course = $this->t_appointment_info->get_appoint_course($page_num);
        if ($ret_course === false) {
           return outputJson(array('ret' => SYSTEM_ERR, 'info' => '系统错误'));
        }

        foreach($ret_course['list'] as &$item) {
            $item['packageid']        = $item['packageid'];
            $item['package_name']     = $item['package_name'];
            $item['grade']            = $item['grade'];
            $item['subject']          = Esubject::get_desc( $course_record['subject']);
            $item['lesson_total']     = $item['lesson_total'];
            $item['effect_start']     = $item['effect_start'];
            $item['effect_end']       = $item['effect_end'];
            $item['price']            = $item['price']/100;
            $item['package_type']     = Epackage_type::get_desc( $course_record['package_type']);
            $item['package_tags']     = $item['package_tags'];
            $item['tag_type']         = $item['tag_type'];
            $item['user_total']       = $item['user_total'];
            $item['package_deadline'] = $item['package_deadline'] >0?Utils::unixtime2date( $item['package_deadline']):"不限制";
            
        }

        return outputjson_success(array('course_list' => $course_list));
    }

    public function get_package_simple_info()
    {
        $packageid = $this->get_in_int_val('packageid', 0);

        $ret_get = $this->t_appointment_info->get_package_simple_info($packageid);
        if ($ret_get === false) {
            return outputjson_error(Eerror::V_SYSTEM_ERR);
        }

        $package_tags = array();
        foreach(explode(',', $ret_get['package_tags']) as $tag) {
            $package_tags[] = explode('|', $tag);
        }

        $package_grade = array();
        foreach(explode(',', $ret_get['grade']) as $grade) {
            if (!empty($grade)) {
                $package_grade[] = $grade;
            }
        }
        $package_info = array(
            'packageid'      => $ret_get['packageid'],
            'package_name'   => $ret_get['package_name'],
            'package_target' => $ret_get['package_target'],
            'package_intro'  => $ret_get['package_intro'],
            'suit_student'   => $ret_get['suit_student'],
            'subject'        => $ret_get['subject'],
            'lesson_total'   => $ret_get['lesson_total'],
            'grade'          => $package_grade,
            'package_tags'   =>  $package_tags,
        );

       return outputjson_success(array('package_info' => $package_info));
    }

    public function get_package_pic()
    {
        $packageid   = $this->get_in_int_val('packageid', 0);
        if (!$packageid ) {
            return outputJson(array('ret' => E\Eerror::V_ILLEGAL_PARAMS, 'info' => '请检查所传的参数'));
        }

        $ret_pic = $this->t_appointment_info->get_package_pic($packageid);
        if ($ret_pic === false) {
           return outputjson_error(E\Eerror::V_SYSTEM_ERR);
        }

        return outputjson_success(array('package_pic' => $ret_pic));
                 
    }

    public function set_package_pic()
    {
        $packageid   = $this->get_in_int_val('packageid', 0);
        $package_pic = $this->get_in_str_val('package_pic', '');
        if (!$packageid || !$package_pic) {
           return outputJson(array('ret' => E\Eerror::V_ILLEGAL_PARAMS, 'info' => '请检查所传的参数'));
        }

        $ret_set = $this->t_appointment_info->set_package_pic($packageid,$package_pic);
        if ($ret_set === false) {
           return outputjson_error(E\Eerror::V_SYSTEM_ERR);
        }

        return outputjson_success();
    }

    
}
