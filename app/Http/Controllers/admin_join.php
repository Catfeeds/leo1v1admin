<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;


class admin_join extends Controller
{
    var $check_login_flag=false;
    function login() {
        return $this->pageView(__METHOD__);
    }

    public function send_phone_code(){
        $phone=$this->get_in_phone(); 

        if (strlen($phone)!=11) {
            return $this->output_err("手机格式不对");
        }

        $phone_index=session("phone_index");
        $phone_index+=1;
        $phone_code=\App\Helper\Common::gen_rand_code(4);
        session([
            "phone"       => $phone,
            "phone_code"  => $phone_code,
            "phone_index" => $phone_index,
        ]);

        /*
          模板名称: 通用验证
          模板ID: SMS_10671029
          *模板内容: 您的手机验证码为：${code} ，请尽快完成验证 编号为： ${index}
         */
        \App\Helper\Net::send_sms_taobao($phone,0, 10671029,[
                                             "code"  => $phone_code,
                                             "index" => $phone_index,
                                         ]);
        return $this->output_succ([
            "index" =>$phone_index,
            "code" => $phone_code ]);
    }
    public function check_phone_code() {
        $phone      = $this->get_in_str_val("phone");
        $phone_code = $this->get_in_str_val("phone_code");
        $ret        = false;
        if ($phone) {
            if (session("phone_code")==$phone_code && session("phone")==$phone) {
                $ret=true;
                //login succ
                session(["reg_phone" => $phone ] );
            }
        }
        return $this->output_succ(["check_flag" =>$ret]);
    }



    function index() {
        $reg_phone= session("reg_phone");
        $phone = $this->get_in_phone();
        
        if (!$reg_phone && !$phone) {
            header("Location: /admin_join/login");
        }else{
            if($phone){
                $arr["phone"] =  $phone;
                $reg_phone = $phone;
            }else{
                $arr["phone"] =  $reg_phone;
            }
            $res = $this->t_apply_reg->phone_exist($reg_phone);                                
            if($res['num'] > 0 )   {
                $ret = $this->t_apply_reg->apply_info($reg_phone);
                $arr['num'] = $res['num'];
                foreach($ret as $key=>$item){
                    if($key == "education_info" || $key == "work_info"){
                        $arr[$key] = json_decode($item,true);
                        if(isset($arr[$key])){
                            foreach ( $arr[$key] as &$v){
                                if( isset($v['start_time']) && !empty($v['start_time'] )) {
                                    $v['start_time'] = date('Y-m-d',$v['start_time']);
                                }
                                if(isset($v['end_time']) && !empty($v['end_time'] )) {
                                    $v['end_time'] = date('Y-m-d',$v['end_time']);
                                }
                            }
                        }
                    }elseif($key == "family_info"){
                        $arr[$key] = json_decode($item,true);
                    }elseif($key == "birth" || $key == "join_time"){
                        if(isset ($item) && !empty($item))  $arr[$key] = date('Y-m-d',$item);
                    }elseif($key == "ccb_card" || $key =="height" || $key =="postcodes" || $key=="emergency_contact_phone"){
                        if(empty($item)){
                            $arr[$key] = "";
                        }else{
                            $arr[$key] = $item;
                        }
                    }else{
                        $arr[$key] = $item;
                    }
                }
            }else{
                $add_time        = time();
                $this->t_apply_reg->row_insert([
                    'phone'          => $reg_phone,
                    'add_time'       => $add_time
                ]);
            }
            return $this->pageView(__METHOD__,null,[
                "reg_data"=> $arr,
            ]);
        }
    }

    function update_info()
    {
        
        $phone      = $this->get_in_str_val("phone");
        $name      = $this->get_in_str_val("name");
        $education      = $this->get_in_str_val("education");
        $residence      = $this->get_in_str_val("residence");
        $gender     = $this->get_in_int_val("gender");
        $birth      = strtotime($this->get_in_str_val("birth"));
        $english      = $this->get_in_str_val("english");
        $polity     = $this->get_in_str_val("polity");
        $carded      = $this->get_in_str_val("carded");
        $minor     = $this->get_in_str_val("minor");
        $native_place     = $this->get_in_str_val("native_place");
        $height      = $this->get_in_int_val("height");
        $birth_type      = $this->get_in_int_val("birth_type");
        $gra_school      = $this->get_in_str_val("gra_school");
        $gra_major     = $this->get_in_str_val("gra_major");
        $health_condition      = $this->get_in_str_val("health_condition");
        $postcodes      = $this->get_in_int_val("postcodes");
        $residence_type      = $this->get_in_int_val("residence_type");
        $is_insured      = $this->get_in_int_val("is_insured");
        $join_time      = strtotime($this->get_in_str_val("join_time"));
        $emergency_contact_nick     = $this->get_in_str_val("emergency_contact_nick");
        $emergency_contact_address      = $this->get_in_str_val("emergency_contact_address");
        $emergency_contact_phone      = $this->get_in_int_val("emergency_contact_phone");
        if(strlen($carded) != 18){
            return $this->output_err("身份证长度不对");
        }
        $ccb_card      = $this->get_in_int_val("ccb_card");
        if(!empty($ccb_card) && strlen($ccb_card) != 19 && strlen($ccb_card) != 16){
            return $this->output_err("建行卡号长度不对");
        }
        if(!empty($emergency_contact_phone) && strlen($emergency_contact_phone) != 11){
            return $this->output_err(" 请填写正确的手机号码");
        }


        $marry      = $this->get_in_str_val("marry");
        $child      = $this->get_in_str_val("child");
        $email      = $this->get_in_str_val("email");
        $post      = $this->get_in_str_val("post");
        $dept      = $this->get_in_str_val("dept");
        $address      = $this->get_in_str_val("address");
        $strong      = $this->get_in_str_val("strong");
        $interest      = $this->get_in_str_val("interest");
        $non_compete      = $this->get_in_int_val("non_compete");
        $is_labor     = $this->get_in_int_val("is_labor");
        $is_fre     = $this->get_in_int_val("is_fre");
        $fre_name      = $this->get_in_str_val("fre_name");
        $education_info1     = json_decode($this->get_in_str_val("education_info"),true);
        foreach($education_info1 as $key => &$item){
            $item['start_time'] = strtotime($item['start_time']);
            $item['end_time'] = strtotime($item['end_time']);          
        }
        $education_info     = json_encode($education_info1);
        $work_info1      =json_decode( $this->get_in_str_val("work_info"),true);
        foreach($work_info1 as $key => &$item){
            $item['start_time'] = strtotime($item['start_time']);
            $item['end_time'] = strtotime($item['end_time']);          
        }

        $work_info     = json_encode($work_info1);
        $family_info     = $this->get_in_str_val("family_info");
        $this->t_apply_reg->field_update_list($phone,[
            'name'           => $name,
            'gender'         => $gender,
            'birth'          => $birth,
            'work_info'      => $work_info,
            'email'          => $email,
            'education_info' => $education_info,
            'residence'    => $residence,
            'english'         => $english,
            'polity'         => $polity,
            'carded'         => $carded,
            'ccb_card'         => $ccb_card,
            'marry'         => $marry,
            'child'         => $child,
            'post'         => $post,
            'dept'         => $dept,
            'address'         => $address,
            'strong'         => $strong,
            'interest'         => $interest,
            'non_compete'         => $non_compete,
            'is_labor'         => $is_labor,
            'is_fre'         => $is_fre,
            'fre_name'         => $fre_name,
            'family_info'         => $family_info,
            'education'         => $education,
            'minor'               => $minor,
            'height'               => $height,
            'birth_type'               => $birth_type,
            'gra_major'               => $gra_major,
            'gra_school'               => $gra_school,
            'health_condition'               => $health_condition,
            'postcodes'               => $postcodes,
            'residence_type'               => $residence_type,
            'is_insured'               => $is_insured,
            'join_time'               => $join_time,
            'native_place'               => $native_place,
            'emergency_contact_nick'               => $emergency_contact_nick,
            'emergency_contact_phone'               => $emergency_contact_phone,
            'emergency_contact_address'               => $emergency_contact_address
        ]);
        return $this->output_succ();
    }
   
    function update_trial_info()
    {
        
        $phone      = $this->get_in_str_val("phone");
        
        $trial_start_time      = strtotime($this->get_in_str_val("trial_start_time"));
        $trial_end_time      = strtotime($this->get_in_str_val("trial_end_time"));
        $trial_dept      = $this->get_in_str_val("trial_dept");
        $trial_post     = $this->get_in_str_val("trial_post");

       
        $this->t_apply_reg->field_update_list($phone,[
            'trial_post'           => $trial_post,
            'trial_dept'         => $trial_dept,
            'trial_end_time'          => $trial_end_time,
            'trial_start_time'      => $trial_start_time
        ]);
        return $this->output_succ();
    }
   

    function add_info(){
        
        $phone      = $this->get_in_str_val("phone");
        if (strlen($phone)!=11) {
            return $this->output_err("手机格式不对");
        }
        $res = $this->t_apply_reg->phone_exist($phone);                                
        if($res['num'] > 0 )   {
            return $this->output_err("手机号码已存在!");
        }
        $name      = $this->get_in_str_val("name");
        $post      = $this->get_in_str_val("post");
        $dept      = $this->get_in_str_val("dept");
        $add_time =  time();
        $ret = $this->t_apply_reg->row_insert([
            'name'           => $name,
            'post'         => $post,
            'dept'         => $dept,
            'phone'         => $phone,
            'add_time'         => $add_time
        ]);               
        return $this->output_succ("保存成功!");
    }

    function apply_del(){
        $phone      = $this->get_in_str_val("phone");
        $this->t_apply_reg->apply_del($phone);
        return $this->output_succ();
    }
    
    function file_print() {
        $phone = $this->get_in_phone();
        
        $ret = $this->t_apply_reg->apply_info($phone);
        foreach($ret as $key=>$item){
            if($key == "education_info" || $key == "work_info"){
                $arr[$key] = json_decode($item,true);
                if(isset($arr[$key])){
                    foreach ( $arr[$key] as &$v){
                        if( isset($v['start_time']) && !empty($v['start_time'] )) {
                            $v['start_time'] = date('Y-m-d',$v['start_time']);
                        }
                        if(isset($v['end_time']) && !empty($v['end_time'] )) {
                            $v['end_time'] = date('Y-m-d',$v['end_time']);
                        }
                    }
                }
            }elseif($key == "family_info"){
                $arr[$key] = json_decode($item,true);
            }elseif($key == "birth"){
                if(isset ($item) && !empty($item))  $arr[$key] = date('Y-m-d',$item);
            }elseif($key == "ccb_card"){
                if(empty($item)){
                    $arr[$key] = "";
                }else{
                    $arr[$key] = $item;
                }
            }else{
                $arr[$key] = $item;
            }
        }
        $time = date('Y-m-d',time());
        return $this->pageView(__METHOD__,null,[
            "reg_data"=> $arr,
            "time"=> $time
        ]);
    }

    function file_print_new() {
        $phone = $this->get_in_phone();
        
        $ret = $this->t_apply_reg->apply_info($phone);
        foreach($ret as $key=>$item){
            if($key == "education_info" || $key == "work_info"){
                $arr[$key] = json_decode($item,true);
                if(isset($arr[$key])){
                    foreach ( $arr[$key] as &$v){
                        if( isset($v['start_time']) && !empty($v['start_time'] )) {
                            $v['start_time'] = date('Y-m-d',$v['start_time']);
                        }
                        if(isset($v['end_time']) && !empty($v['end_time'] )) {
                            $v['end_time'] = date('Y-m-d',$v['end_time']);
                        }
                        if(isset($v['start_time']) && !empty($v['start_time']) && isset($v['end_time']) && !empty($v['end_time'] )){
                            $v['time']  = $v['start_time']."至".$v['end_time'];
                        }
                        if( isset($v['voucher']) && !empty($v['voucher'] )) {
                            $voucher_str = explode("/",$v['voucher']);
                            $v['voucher_str'] = $voucher_str[0];
                        }

                    }
                }
            }elseif($key == "family_info"){
                $arr[$key] = json_decode($item,true);
            }elseif($key == "birth" || $key == "join_time"){
                if(isset ($item) && !empty($item))  $arr[$key] = date('Y-m-d',$item);
            }elseif($key == "ccb_card"  || $key =="height" || $key =="postcodes" || $key=="emergency_contact_phone"){
                if(empty($item)){
                    $arr[$key] = "";
                }else{
                    $arr[$key] = $item;
                }
            }else{
                if(isset($arr['trial_start_time']) && !empty($arr['trial_start_time']) && isset($arr['trial_end_time']) && !empty($arr['trial_end_time'])){
                    $start_time = date("Y-m-d",$arr["trial_start_time"]);
                    $end_time = date("Y-m-d",$arr["trial_end_time"]);
                    $arr["trial_time"] = substr($start_time,0,4)."年".substr($start_time,5,2)."月".substr($start_time,8,2)."日 至 ".substr($end_time,0,4)."年".substr($end_time,5,2)."月".substr($end_time,8,2)."日";
                }
                $arr[$key] = $item;
            }
        }
        $time = date('Y-m-d',time());
        return $this->pageView(__METHOD__,null,[
            "reg_data"=> $arr,
            "time"=> $time
        ]);
    }


}