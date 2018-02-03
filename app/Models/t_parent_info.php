<?php
namespace App\Models;
use \App\Enums as E;
class t_parent_info extends \App\Models\Zgen\z_t_parent_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_parent_info($parentid,$phone,$nick,$page_num)
    {
        $where_arr = [
            ["parentid=%u",$parentid,-1]
        ];
        if ($nick) {
            $where_arr[]= " (nick like '%" . $nick. "%') "   ;
        }

        if ($phone) {
            $where_arr[]=  "phone like '%".$phone."%'";
        }


        $sql = $this->gen_sql("select * from %s where %s ",
                              self::DB_TABLE_NAME,
                              [$this->where_str_gen($where_arr)]
        );

        return $this->main_get_list_by_page($sql,$page_num,10);
    }
    public function get_parentid_by_wx_openid($wx_openid) {
        $sql=$this->gen_sql_new(
            "select parentid from %s where wx_openid='%s' ",
            self::DB_TABLE_NAME, $wx_openid
        );
        return $this->main_get_value($sql);
    }

    public function get_parent_sim_info($parentid){
        $sql = $this->gen_sql("select * from %s where parentid = %u ",
                             self::DB_TABLE_NAME,
                             $parentid
        );
        return $this->main_get_row($sql);
    }

    public function update_parent_info($parentid,$nick,$gender,$phone,$last_time,$has_login,$wx_openid){
        if (!$wx_openid ) {
            $wx_openid=NULL;
        }
        return $this->field_update_list($parentid,[
            "nick"      => $nick,
            "gender"    => $gender,
            "has_login" => $has_login,
            "phone"     => $phone,
            "wx_openid" => $wx_openid,
        ]);
    }

    public function get_parentid_by_phone($phone){
        $sql=$this->gen_sql("select parentid,nick as parent_name from %s where phone=%u"
                            ,self::DB_TABLE_NAME
                            ,$phone
        );
        return $this->main_get_row($sql);
    }

    /**
     *@author sam
     *@function 查询parentid
     */
    public function get_parentid_by_phone_b1($phone){
        $sql=$this->gen_sql_new("select parentid from %s where phone='%s'"
                                ,self::DB_TABLE_NAME
                                ,$phone
        );
        return $this->main_get_value($sql);
    }

    public function get_parent_email_list($parentid){
        $sql=$this->gen_sql("select email from %s where parentid=%u"
                            ,self::DB_TABLE_NAME
                            ,$parentid
        );
        return $this->main_get_list($sql);

    }

    public function get_list_for_select($id,$gender,$nick_phone,$page_num)
    {
        $where_arr = array(
            array( "gender=%d", $gender, -1 ),
            array( "parentid=%d", $id, -1 ),
        );
        $where_arr = $this->parent_search_info_sql($nick_phone,'',$where_arr);

        $sql = $this->gen_sql_new("select parentid as id , nick, phone,gender "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function update_parent_phone($phone,$old_phone){
        $sql =$this->gen_sql("update %s set phone=%u where phone = %u ",
                             self::DB_TABLE_NAME,
                             $phone,
                             $old_phone
        );
        return $this->main_update($sql);
    }


    public function wx_binding_from_qrcode( $wx_openid,  $phone  ) {
        $db_wx_openid = '';
        $parent_item=$this->get_parentid_by_phone( $phone );
        $parentid= $parent_item["parentid"];
        if($parentid){
            $db_wx_openid=$this->get_wx_openid($parentid);
        }

        if ($db_wx_openid && ($db_wx_openid !=  $wx_openid   )) {
            $content = " 家长账号[$phone] 已经被别人绑定了 ";
        }else{
            $openid2= $this->get_parentid_by_wx_openid($wx_openid);
            if ($openid2) {
                $db_phone=$this->get_phone($openid2);
                $content = "你已经绑定家长账号[$db_phone]了,不能重新绑定到[$phone]";
                /*
                $this->field_update_list($openid2,[
                    "wx_openid" => NULL,
                ]);
                */
            }else{
                $this->field_update_list($parentid,[
                    "wx_openid" => $wx_openid,
                ] );
                $content = "绑定家长账号[$phone]成功";
            }
        }

        return $content;
    }


    public function send_wx_todo_msg($parentid, $from_user, $header_msg,$msg="",$url="",$desc="点击进入操作"){
        $template_id = "9MXYC2KhG9bsIVl16cJgXFVsI35hIqffpSlSJFYckRU";

        $ret         = $this->send_template_msg($parentid,$template_id,[
            "first"    => $header_msg,
            "keyword1" => $from_user,
            "keyword2" => $msg,
            "keyword3" => date("Y-m-d H:i:s"),
            "remark"   => $desc,
        ],$url);

        return $ret;
    }

    public function send_template_msg ($parentid,  $template_id, $data ,$url="http://admin.leo1v1.com" ) {
        if (substr($url,0,7 )!="http://") {
            $url="http://admin.leo1v1.com/".trim($url,"/ \t");
        }
        $openid= $this->get_wx_openid($parentid);
        $wx     = new \App\Helper\Wx();
        if ($openid) {
            $ret = $wx->send_template_msg($openid,$template_id,$data ,$url);
        }else{
            return false;
        }
        return $ret;
    }

    public function get_phone_by_userid($userid){
        $sql = $this->gen_sql_new("select phone from %s where parentid=$userid",
                                  self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function get_wx_openid_by_parentid($parentid) {
        $sql=$this->gen_sql_new(
            "select wx_openid from %s where parentid='%s' ",
            self::DB_TABLE_NAME, $parentid
        );
        return $this->main_get_value($sql);
    }

    public function get_parent_wx_openid($lessonid){
        $sql = $this->gen_sql_new(" select p.wx_openid from %s p ".
                                  " left join %s pc on p.parentid = pc.parentid".
                                  " left join %s l on pc.userid = l.userid ".
                                  " where l.lessonid = %d order by pc.last_modified_time desc",
                                  self::DB_TABLE_NAME,
                                  t_parent_child::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $lessonid

        );
        return $this->main_get_value($sql);
    }

    public function register($phone, $passwd, $reg_channel , $ip,$nick){
        $parentid = $this->t_phone_to_user->get_userid_by_phone($phone,E\Erole::V_PARENT);
        if($parentid>0){
            return $parentid;
        }
        $parentid= $this->t_user_info->user_reg($passwd,$reg_channel,ip2long($ip));
        if(!$parentid){
            return false;
        }

        $ret = $this->t_phone_to_user->add($phone,E\Erole::V_PARENT,$parentid);
        if(!$ret){
            return false;
        }

        $rets = $this->add_parent($parentid,$phone,$nick);
        if(!$rets){
            return false;
        }
        return $parentid;
    }

    public function add_parent($userid,$phone,$nick){
        return $this->row_insert([
            "parentid"           => $userid,
            "phone"              => $phone,
            "nick"               => $nick,
            "last_modified_time" => time(),
        ]);
    }

    public function get_xx(){
        $sql = $this->gen_sql_new(" select uid,parentid from %s p "
                                  ."left join %s m on m.wx_openid=p.wx_openid where m.wx_openid is not null"
                                  ,self::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_openid_list(){
        $where_arr = [
            "wx_openid!=''",
            "wx_openid!='0'",
            "wx_openid is not null"
        ];

        $sql = $this->gen_sql_new("select wx_openid, parentid from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_parent_opend_list(){
        $where_arr = [
            " p.wx_openid is not null",
            " p.wx_openid != ''"
        ];
        $sql = $this->gen_sql_new("  select wx_openid,parentid from %s p"
                                  ." where %s order by parentid desc "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function getParentNum(){
        $where_arr = [
            "o.price>0",
            "contract_status in (1,2)",
            "p.wx_openid is not null",
            "p.wx_openid != ''"
        ];
        $sql = $this->gen_sql_new("  select p.wx_openid,p.parentid from %s p "
                                  ." left join %s pc on pc.parentid=p.parentid"
                                  ." left join %s o on o.userid=pc.userid"
                                  ." where %s group by p.parentid"
                                  ,self::DB_TABLE_NAME
                                  ,t_parent_child::DB_TABLE_NAME
                                  ,t_order_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function getNeedSendInfo(){
        $where_arr = [
            "s.is_test_user=0",
            "l.lesson_status=2",
            "l.lesson_del_flag=0",
            "l.lesson_type=2",
            "s.grade in (101,102,103)",
            "p.wx_openid is not null",
            "p.wx_openid != ''",
        ];

        $sql = $this->gen_sql_new("  select p.wx_openid,p.nick from %s p "
                                  ." left join %s s on p.parentid=s.parentid"
                                  ." left join %s l on l.userid=s.userid"
                                  ." where %s group by p.parentid"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_stu(){
        $where_arr = [
            "s.is_test_user=0",
            "s.grade in (101,102,103)",
            "p.wx_openid is not null",
            "p.wx_openid != ''",
        ];

        $sql = $this->gen_sql_new("  select p.parentid, p.wx_openid,p.nick,s.userid from %s p "
                                  ." left join %s s on p.parentid=s.parentid"
                                  ." where %s group by p.parentid"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function getWxOpenidByStuId($userid){
        $sql = $this->gen_sql_new("  select wx_openid from %s p "
                                  ." left join %s s on s.parentid=p.parentid"
                                  ." where s.userid=$userid"
                                  ,self::DB_TABLE_NAME
                                  ,t_student_info::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }
}
