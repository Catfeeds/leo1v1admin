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
        $reduce_flag = 0;
        // $time = time(null);
        $time = 1517499000;
        $ret_time = $this->task->t_month_def_type->get_all_list();
        $firstday = date("Y-m-01");
        $lastday = date("Y-m-d",strtotime("$firstday +1 month -1 day"));
        list($start_time_this,$end_time_this)= [strtotime($firstday),strtotime($lastday)];
        foreach($ret_time as $item){//本月
            if(strtotime(date('Y-m-d',$time)) == $item['start_time']){//月头标志
                $reduce_flag = 1;
            }
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
            $account = $item['account'];
            $face_pic = $item['face_pic']!=''?$item['face_pic']:'http://7u2f5q.com2.z0.glb.qiniucdn.com/fdc4c3830ce59d611028f24fced65f321504755368876.png';
            $this_level = $item['seller_level'];
            $num = isset($item['num'])?$item['num']:0;
            $level_goal = isset($item['level_goal'])?$item['level_goal']:0;
            $seller_level_goal = isset($item['seller_level_goal'])?$item['seller_level_goal']:0;
            $become_member_time = $item['create_time'];
            $no_update_seller_level_flag = $item['no_update_seller_level_flag'];
            $ret_next = $this->task->t_seller_level_goal->get_next_level_by_num($num+1);
            $next_goal = isset($ret_next['level_goal'])?$ret_next['level_goal']:$level_goal;
            $seller_next_goal = isset($ret_next['seller_level_goal'])?$ret_next['seller_level_goal']:$seller_level_goal;
            $update_flag = 0;
            if($reduce_flag == 1){//月头
                $month_level = $this_level;
                //降级
                if($no_update_seller_level_flag == 0){//参与
                    //统计上个月
                    $price = $this->task->t_order_info->get_seller_price($start_time_last,$end_time_last,$adminid);
                    $price = $price/100;
                    if($price<$seller_level_goal){//降级
                        foreach($ret_level_goal as $item){
                            if($price >= $item['seller_level_goal']){
                                $next_level = $item['seller_level'];
                                $level_face = $item['level_face'];
                            }
                        }
                        $update_flag = 1;
                    }
                    //入职小于2月,不降级
                    if(time(null)-$become_member_time<60*3600*24){
                        $update_flag = 0;
                    }
                }
                $update_flag = 0;
                //定级
                if($no_update_seller_level_flag == 0){//参与
                    $price_very_last = $this->task->t_order_info->get_1v1_order_seller_month_money_new($account,$start_time_very_last,$end_time_very_last);
                    $price_very_last = isset($price_very_last)?$price_very_last/100:0;
                    foreach($ret_level_goal as $item){
                        if($price_very_last >= $item['level_goal']){
                            $month_level = $item['seller_level'];
                        }
                    }
                    //入职小于2月,定级>D
                    $mix_time = strtotime(date('Y-m-1',$become_member_time));
                    $mid_time = strtotime(date('Y-m-15',$become_member_time));
                    if($become_member_time>$mix_time && $become_member_time<$mid_time){
                        $max_time = strtotime(date("Y-m-d",strtotime(date('Y-m-1',$become_member_time)." +2 month")));
                    }else{
                        $max_time = strtotime(date("Y-m-d",strtotime(date('Y-m-1',$become_member_time)." +3 month")));
                    }
                    $month_date = strtotime(date('Y-m-1',strtotime(date('Y-m-d',$time))-1));
                    if($month_date<$max_time && $month_level>E\Eseller_level::V_500){
                        $month_level = E\Eseller_level::V_500;
                    }
                    $row = $this->task->t_seller_level_month->get_row_by_adminid_month_date($adminid,$month_date);
                    if(!$row){
                        $this->task->t_seller_level_month->row_insert([
                            'adminid' => $adminid,
                            'month_date' => $month_date,
                            'seller_level' => $month_level,
                            'money' => $price_very_last*100,
                            'create_time' => $time,
                        ]);
                    }
                }
            }else{//月中
                //统计本月
                $price = $this->task->t_order_info->get_seller_price($start_time_this,$end_time_this,$adminid);
                $price = $price/100;
                if($price>$seller_next_goal){//升级
                    foreach($ret_level_goal as $item){
                        if($price >= $item['seller_level_goal']){
                            $next_level = $item['seller_level'];
                            $level_face = $item['level_face'];
                        }
                        if($this_level== 700 && in_array($next_level,[500,600])){
                            $next_level = 401;
                        }
                    }
                    $update_flag = 1;
                }
            }
            if($update_flag == 1){//修改等级
                $face_pic_str = substr($face_pic,-12,5);
                $ex_str = $next_level.$face_pic_str;
                $level_face_pic = $this->get_top_img($adminid,$face_pic,$level_face,$ex_str);
                $this->task->t_manager_info->field_update_list($adminid,[
                    'seller_level'=>$next_level,
                    'level_face_pic'=>$level_face_pic,
                ]);
                $this->task->t_seller_edit_log->row_insert([
                    "uid"         => $adminid,
                    "type"        => 2,
                    "old"         => $this_level,
                    "new"         => $next_level,
                    "create_time" => $time,
                ],false,false,true );
                // echo $account.':'.$this_level."=>".$next_level.','.date('Y-m-d H:i:s',$time)."\n";
                // $this->task->t_manager_info->send_wx_todo_msg_by_adminid($adminid,"咨询师等级修改","咨询师等级修改",$account."从".E\Eseller_level::get_desc($this_level)."级修改为".E\Eseller_level::get_desc($next_level)."级","");
                $this->task->t_manager_info->send_wx_todo_msg_by_adminid(831,"咨询师等级修改","咨询师等级修改",$account."从".E\Eseller_level::get_desc($this_level)."级修改为".E\Eseller_level::get_desc($next_level)."级","");
            }
        }
    }

    //处理等级头像
    public function get_top_img($adminid,$face_pic,$level_face,$ex_str){
        $datapath = $face_pic;
        $datapath_new = $level_face;
        $datapath_type = @end(explode(".",$datapath));
        $datapath_type_new = @end(explode(".",$datapath_new));
        $image_1 = $this->yuan_img($datapath);
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
        $tmp_url = "/tmp/".$adminid."_".$ex_str."_gd.png";
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

    /**
     *  blog:http://www.zhaokeli.com
     * 处理成圆图片,如果图片不是正方形就取最小边的圆半径,从左边开始剪切成圆形
     * @param  string $imgpath [description]
     * @return [type]          [description]
     */
    function yuan_img($imgpath = './tx.jpg') {
        $ext     = pathinfo($imgpath);
        $src_img = null;
        switch ($ext['extension']) {
        case 'jpg':
            $src_img = imagecreatefromjpeg($imgpath);
            break;
        case 'jpeg':
            $src_img = imagecreatefromjpeg($imgpath);
            break;
        case 'png':
            $src_img = imagecreatefrompng($imgpath);
            break;
        }
        $wh  = getimagesize($imgpath);
        $w   = $wh[0];
        $h   = $wh[1];
        $w   = min($w, $h);
        $h   = $w;
        $img = imagecreatetruecolor($w, $h);
        //这一句一定要有
        imagesavealpha($img, true);
        //拾取一个完全透明的颜色,最后一个参数127为全透明
        $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
        imagefill($img, 0, 0, $bg);
        $r   = $w / 2-20; //圆半径
        $y_x = $r; //圆心X坐标
        $y_y = $r; //圆心Y坐标
        // dd($r,$y_x,$y_y);
        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgbColor = imagecolorat($src_img, $x, $y);
                if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                    imagesetpixel($img, $x+14, $y+14, $rgbColor);
                }
            }
        }
        return $img;
    }

}