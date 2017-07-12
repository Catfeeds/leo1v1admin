<?php
namespace App\Config;
class tea_menu{
    static  public  function get_config()  {
        return [
            [ "power_id"=>1, "name"=>"老师信息", "icon" => "fa-dashboard", "url"=> "/teacher_info/index?teacherid={teacherid}"],
            [ "power_id"=>7, "name"=>"评估考核", "icon" => "fa-dashboard", "url"=> "/teacher_info/teacher_assess?teacherid={teacherid}"],
            [ "power_id"=>6, "name"=>"课程列表", "icon" => "fa-dashboard", "url"=> "/teacher_info/get_lesson_list?teacherid={teacherid}"],
            [ "power_id"=>2, "name"=>"当期课表", "icon" => "fa-dashboard", "url"=> "/teacher_info/lesson_list?teacherid={teacherid}"],
            [ "power_id"=>3, "name"=>"空闲时间", "icon" => "fa-dashboard", "url"=> "/teacher_info/free_time?teacherid={teacherid}"],
            [ "power_id"=>4, "name"=>"常规课表", "icon" => "fa-dashboard", "url"=> "/teacher_info/common_time_new?teacherid={teacherid}"],
            [ "power_id"=>5, "name"=>"特长", "icon" => "fa-dashboard", "url"=> "/teacher_info/avoid?teacherid={teacherid}"],
        ];
    }

}