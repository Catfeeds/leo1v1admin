///<reference path="../common.d.ts" />
///<reference path="../g_args.d.ts/stu_manage-todo_list.d.ts" />

$(function(){
    function load_data(){

        $.reload_self_page ( {
      sid:	$('#id_sid').val()
        });
    }

   //添加 
    $('#id_add_login_new').click(function(){

        //var opt_data = $(this).get_opt_data();
        var $userid     = $("<input type='number' max=999999 min=0/>");
        var $login      = $("<input/>");
        var $nick       = $("<input/>");
        var $ip         = $("<input/>");
        var $role       = $("<input type='number' max=127 min=0 /><span>小于127</span>");
        var $login_type = $("<input type='number' max=127 min=0 /><span>小于127</span>");
        var $flag       = $("<input type='number' max=127 min=0/><span>小于127</span>");

       $login.datetimepicker({
            lang:'ch',
            timepicker:false,
            format:'Y-m-d'
        });


        var arr=[
            ["学生ID",  $userid],
            ["登录时间",  $login],
            ["昵称",  $nick],
            ["IP",  $ip],
            ["地址",  $role],
            ["登录方式",$login_type],
            ["方式",  $flag],
        ];

        //Enum_map.append_option_list("grade",$grade, true);
        //Enum_map.append_option_list("subject",$subject, true);

        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax_t("/ajax_deal2/login_log_add",{
                    "userid"     : $userid.val(),
                    "login"      : $login.val(),
                    "nick"       : $nick.val(),
                    "ip"         : $ip.val(),
                    "role"       : $role.val(),
                    "login_type" : $login_type.val(),
                    "flag"       : $flag.val()
                },function(result){
                        if(result.ret==-1){
                            BootstrapDialog.alert(result.info);
                        }else{
                            //window.location.reload();
                            load_data();
                        }
                    });

             }
        });

    });
    //删除信息
    $(".opt-del").on("click",function(){

        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm("要删除["+ opt_data.id + "]的吗",function(val){
            
            if (val){
                $.do_ajax( "/ajax_deal2/login_log_del", {
                    "id":opt_data.id
                }) ;
            }
            

        });

    });
    
   //测试修改login_log 
   $(".opt-set-login-log").on("click",function(){
        var opt_data = $(this).get_opt_data();
       
        var id_userid       = $("<input type='number' max=999999 min=0/>");
        var id_login_time   = $("<input/>");
        var id_nick         = $("<input/>");
        var id_ip           = $("<input/>");
        var id_role         = $("<input type='number' max=127 min=0/><span>小于127</span>");
        var id_login_type   = $("<input type='number' max=127 min=0/><span>小于127</span>");
        var id_dymanic_flag = $("<input type='number' max=127 min=0/><span>小于127</span>");

       
        id_userid.val(opt_data.userid);
        id_login_time.val(opt_data.login_time);
        id_nick.val(opt_data.nick);
        id_ip.val(opt_data.ip);
        id_role.val(opt_data.role);
        id_login_type.val(opt_data.login_type);
        id_dymanic_flag.val(opt_data.dymanic_flag);
              
        id_login_time.datetimepicker({
                lang:'ch',
              timepicker:false,
              format:'Y-m-d'
              });


        var arr=[
            ["学生ID",  id_userid],
            ["登录时间",  id_login_time],
            ["昵称",  id_nick],
            ["IP",  id_ip],
            ["地址",  id_role],
            ["登录方式",id_login_type],
            ["方式",  id_dymanic_flag],
        ];
       
        $.show_key_value_table("学生登录修改", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/ajax_deal2/set_login_log",{
                    'id'           : opt_data.id,
                    'userid'       : id_userid.val(),
                    "login_time"   : id_login_time.val(),
                    "nick"         : id_nick.val(),
                    "ip"           : id_ip.val(),
                    "role"         : id_role.val(),
                    "login_type"   : id_login_type.val(),
                    "dymanic_flag" : id_dymanic_flag.val()
                },function(result){
                        if(result.ret==-1){
                            BootstrapDialog.alert(result.info);
                        }else{
                            //window.location.reload();
                            load_data();
                        }
                    });
       
                }
           });
        });
    




    $('.opt-change').set_input_change_event(load_data);

});
