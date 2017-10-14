<?php
namespace App\Console\Commands;
use \App\Enums as E;
class update_seller_level extends cmd_base
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_seller_level';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';




    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time = time(null);
        $ret_time = $this->task->t_month_def_type->get_all_list();
        $firstday = date("Y-m-01");
        $lastday = date("Y-m-d",strtotime("$firstday +1 month -1 day"));
        list($start_time_this,$end_time_this)= [strtotime($firstday),strtotime($lastday)];
        foreach($ret_time as $item){//本月
            if($time>=$item['start_time'] && $time<$item['end_time']){
                $start_time_this = $item['start_time'];
                $end_time_this = $item['end_time'];
            }
        }
        $timestamp = strtotime(date("Y-m-01"));
        $firstday_last  = date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
        $lastday_last   = date('Y-m-d',strtotime("$firstday_last +1 month -1 day"));
        list($start_time_last,$end_time_last)= [strtotime($firstday_last),strtotime($lastday_last)];
        foreach($ret_time as $item){//上月
            if($start_time_this-1>=$item['start_time'] && $start_time_this-1<$item['end_time']){
                $start_time_last = $item['start_time'];
                $end_time_last = $item['end_time'];
            }
        }
        $timestamp_very_last=strtotime(date("Y-m-01"));
        $firstday_very_last=date('Y-m-01',strtotime(date('Y',$timestamp_very_last).'-'.(date('m',$timestamp_very_last)-2).'-01'));
        $lastday_very_last=date('Y-m-d',strtotime("$firstday_very_last +1 month -1 day"));
        list($start_time_very_last,$end_time_very_last)= [strtotime($firstday_very_last),strtotime($lastday_very_last)];
        foreach($ret_time as $item){//上上月
            if($start_time_last-1>=$item['start_time'] && $start_time_last-1<$item['end_time']){
                $start_time_very_last = $item['start_time'];
                $end_time_very_last = $item['end_time'];
            }
        }
        $account_role = E\Eaccount_role::V_2;
        $seller_list = $this->task->t_manager_info->get_seller_list_new_two($account_role);
        $ret_level_goal = $this->task->t_seller_level_goal->get_all_list_new();
        foreach($seller_list as $item){
            $adminid = $item['uid'];
            $face_pic = $item['face_pic'];
            if($face_pic == ''){
                $face_pic = 'http://7u2f5q.com2.z0.glb.qiniucdn.com/fdc4c3830ce59d611028f24fced65f321504755368876.png';
            }
            $account = $this->task->t_manager_info->get_account_by_uid($adminid);
            $this_level = $item['seller_level'];
            $become_member_time = $item['create_time'];
            $ret_this = $this->task->t_seller_level_goal->field_get_list($item['seller_level'],'*');
            $num = isset($ret_this['num'])?$ret_this['num']:0;
            $level_goal = isset($ret_this['level_goal'])?$ret_this['level_goal']:0;
            $next_goal = $level_goal;
            $next_num = $num + 1;
            $ret_next = $this->task->t_seller_level_goal->get_next_level_by_num($next_num);
            if($ret_next){
                $next_goal = $ret_next['level_goal'];
            }
            //统计本月
            $price = $this->task->t_order_info->get_seller_price($start_time_this,$end_time_this,$adminid);
            $price = $price/100;
            if($price>$next_goal){
                foreach($ret_level_goal as $item){
                    if($price >= $item['level_goal']){
                        $next_level = $item['seller_level'];
                        $level_face = $item['level_face'];
                    }
                }
                $level_face_pic = $this->get_top_img($adminid,$face_pic,$level_face);
                $this->task->t_manager_info->field_update_list($adminid,[
                    'seller_level'=>$next_level,
                    'level_face_pic'=>$level_face_pic,
                ]);

                $this->task->t_seller_edit_log->row_insert([
                    "uid"         => $adminid,
                    "type"        => 2,
                    "old"         => $this_level,
                    "new"         => $next_level,
                    "create_time" => time(NULL),
                ],false,false,true );
                $this->task->t_manager_info->send_wx_todo_msg_by_adminid($adminid,"咨询师等级升级","咨询师等级升级",$account."从".E\Eseller_level::get_desc($this_level)."级升级为".E\Eseller_level::get_desc($next_level)."级","");
                $this->task->t_manager_info->send_wx_todo_msg_by_adminid(898,"咨询师等级升级","咨询师等级升级",$account."从".E\Eseller_level::get_desc($this_level)."级升级为".E\Eseller_level::get_desc($next_level)."级","");
                $this->task->t_manager_info->send_wx_todo_msg_by_adminid(412,"咨询师等级升级","咨询师等级升级",$account."从".E\Eseller_level::get_desc($this_level)."级升级为".E\Eseller_level::get_desc($next_level)."级","");
            }
            //统计上个月

            //统计上上个月

        }
    }

    //处理等级头像
    public function get_top_img($adminid,$face_pic,$level_face){
        $datapath = $face_pic;
        $datapath_new = $level_face;
        $datapath_type = @end(explode(".",$datapath));
        $datapath_type_new = @end(explode(".",$datapath_new));
        if($datapath_type == 'jpg' || $datapath_type == 'jpeg'){
            $image_1 = imagecreatefromjpeg($datapath);
        }elseif($datapath_type == 'png'){
            $image_1 = imagecreatefrompng($datapath);
        }elseif($datapath_type == 'gif'){
            $image_1 = imagecreatefromgif($datapath);
        }elseif($datapath_type == 'wbmp'){
            $image_1 = imagecreatefromwbmp($datapath);
        }else{
            $image_1 = imagecreatefromstring($datapath);
        }
        if($datapath_type_new == 'jpg' || $datapath_type_new == 'jpeg'){
            $image_2 = imagecreatefromjpeg($datapath_new);
        }elseif($datapath_type_new == 'png'){
            $image_2 = imagecreatefrompng($datapath_new);
        }elseif($datapath_type_new == 'gif'){
            $image_2 = imagecreatefromgif($datapath_new);
        }elseif($datapath_type_new == 'wbmp'){
            $image_2 = imagecreatefromwbmp($datapath_new);
        }else{
            $image_2 = imagecreatefromstring($datapath_new);
        }
        $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));
        // $color = imagecolorallocate($image_3,255,255,255);
        $color = imagecolorallocatealpha($image_3,255,255,255,1);
        imagefill($image_3, 0, 0, $color);
        imageColorTransparent($image_3, $color);

        imagecopyresampled($image_3,$image_2,0,0,0,0,imagesx($image_3),imagesy($image_3),imagesx($image_2),imagesy($image_2));
        imagecopymerge($image_1,$image_3,0,0,0,0,imagesx($image_3),imagesx($image_3),100);
        $tmp_url = "/tmp/".$adminid."_gk.png";
        imagepng($image_1,$tmp_url);
        $file_name = \App\Helper\Utils::qiniu_upload($tmp_url);
        $level_face_url = '';
        if($file_name!=''){
            $cmd_rm = "rm /tmp/".$adminid."*.png";
            \App\Helper\Utils::exec_cmd($cmd_rm);
            $domain = config('admin')['qiniu']['public']['url'];
            $level_face_url = $domain.'/'.$file_name;
        }
        return $level_face_url;
    }

}