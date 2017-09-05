/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/channel_manage-admin_channel_manage.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }
    $(".common-table" ).table_admin_level_3_init();
    $("#id_add_channel").on("click",function(){
        var id_channel_name=$("<input/>");
        var  arr=[
            ["名称" ,  id_channel_name]
        ];
        
        $.show_key_value_table("新增渠道", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/channel_manage/add_channel",{
                    "channel_name" :id_channel_name.val(),
                });
            }
        });
        
    });


    $(".opt-assign-channel").on("click",function(){
        var opt_data = $(this).get_opt_data();       
        var main_type    = opt_data.main_type;        
        var up_group_id =opt_data.up_group_id ;

        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/channel_manage/get_teacher_type_ref",
            "args_ex" : {
            },

            select_primary_field   : "group_id",
            select_display         : "package_name",
            select_no_select_value : 0,
            select_no_select_title : "[未设置]",
            'field_list' :[
                {
                    title:"group_id",
                    field_name:"group_id"
                },{
                    title:"类型",
                    field_name:"group_name"
                }
            ] ,
            filter_list:[
            ],
            "auto_close" : true,
            "onChange"   : function( val) {
                var group_id = val ;
                var me=this;
                if (group_id>=0) {
                    $.do_ajax("/channel_manage/set_channel_id",{
                        "channel_id":opt_data.channel_id,
                        "group_id"  :group_id
                    },function(resp){
                        window.location.reload(); 
                    });
                }else{
                    alert( "请选择小组" );
                }                
            },
            "onLoadData" : null
        });


    });

    $(".opt-assign-admin").on("click",function(){
        var opt_data = $(this).get_opt_data();       
        var main_type    = opt_data.main_type;        
        var up_group_id =opt_data.up_group_id ;

        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/channel_manage/get_teacher_admin",
            "args_ex" : {
            },

            select_primary_field   : "teacherid",
            select_display         : "package_name",
            select_no_select_value : 0,
            select_no_select_title : "[未设置]",
            'field_list' :[
                {
                    title:"teacher_id",
                    field_name:"teacherid"
                },{
                    title:"姓名",
                    field_name:"realname"
                }
            ] ,
            filter_list:[
            ],
            "auto_close" : true,
            "onChange"   : function( val) {
                var teacher_id = val ;
                var me=this;
                if (teacher_id>=0) {
                    $.do_ajax("/channel_manage/set_teacher_ref_type",{
                        "teacher_id":teacher_id,
                        "group_id"  :opt_data.group_id
                    },function(resp){
                        window.location.reload(); 
                    });
                }else{
                    alert( "请选择小组" );
                }                
            },
            "onLoadData" : null
        });


    });


    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();  
        var id_channel_name=$("<input/>");
        var  arr=[
            ["名称" ,  id_channel_name]
        ];
        
        $.show_key_value_table("修改", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/channel_manage/update_channel_name",{
                    "channel_name" :id_channel_name.val(),
                    "channel_id":opt_data.channel_id,
                });
            }
        });
 
    });

    $(" .opt-assign-admin,.opt-add_other_teacher").each(function(){
        var opt_data = $(this).get_opt_data();
        if(opt_data.level != "l-2"){
            $(this).hide();
        }
    });
    $(".opt-assign-channel, .opt-edit").each(function(){
        var opt_data = $(this).get_opt_data();
        if(opt_data.level != "l-1"){
            $(this).hide();
        }
    });
    $(".opt-tea_origin_url, .opt-detail, .opt-edit_other_teacher").each(function(){
        var opt_data = $(this).get_opt_data();
        if(opt_data.level != "l-3"){
            $(this).hide();
        }
    });
    $(".opt-add_other_teacher").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id_phone              = $("<input/>");
        var id_tea_nick           = $("<input/>");
        var id_teacher_type       = $("<select/>");
        //var id_teacher_ref_type   = $("<select/>");
        var id_email              = $("<input/>");
        var zs_id                 = $("<input/>");

        //Enum_map.append_option_list("teacher_ref_type", id_teacher_ref_type);
        Enum_map.append_option_list("teacher_type", id_teacher_type,true,[0,21,22,31,41]);

        var arr = [
            ["电话", id_phone],
            ["姓名", id_tea_nick],
            ["老师类型", id_teacher_type],
            //["推荐人类型", id_teacher_ref_type],
            ["电子邮件", id_email],
            ["指定招师",zs_id]
        ];

        $.show_key_value_table("新增老师", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var teacher_type       = id_teacher_type.val();
                var teacher_money_type = 4;
                $.do_ajax( '/tea_manage/add_teacher',{
                    "phone"              : id_phone.val(),
                    "tea_nick"           : id_tea_nick.val(),
                    "teacher_type"       : teacher_type,
                    "teacher_ref_type"   : opt_data.group_id,
                    "email"              : id_email.val(),
                    "teacher_money_type" : teacher_money_type,
                    "level"              : 0,
                    "identity"           : 0,
                    "add_type"           : 1,
                    "wx_use_flag"        : 0,
                    "zs_id"              : zs_id.val(),
                });
            }
        },function(){
            $.admin_select_user(
                zs_id ,
                "admin", null,true, {
                    "main_type": 8 //分配用户
                }
            );

        });
    });
    $(".opt-tea_origin_url").on("click",function(){
        var phone = $(this).get_opt_data("admin_phone");
        var url = "http://wx-teacher-web.leo1v1.com/tea.html?"+phone;
        BootstrapDialog.alert(url);
    });
    $(".opt-detail").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var teacherid        = $("<input readonly='readonly'/>"); 
        var teachername      = $("<input readonly='readonly'/>"); 
        var phone            = $("<input readonly='readonly'/>");
        var email            = $("<input readonly='readonly'/>");
        var teacher_type_str = $("<input readonly='readonly'/>");
        var zs_name          = $("<input readonly='readonly'/>");
        teacherid.val(opt_data.admin_id);
        teachername.val(opt_data.admin_name);
        phone.val(opt_data.admin_phone);
        email.val(opt_data.email);
        teacher_type_str.val(opt_data.teacher_type_str);
        zs_name.val(opt_data.zs_name);
        var arr = [
            ["电话", phone],
            ["姓名",    teachername],
            ["老师类型",    teacher_type_str],
            ["电子邮件", email],
            ["指定招师", zs_name],
        ];

        $.show_key_value_table("详细记录", arr, {
            label    :   "确认",
            cssClass :   "btn-warning",
            action   :   function(dialog){
            }
        },function(){
        });
    }) ;
    $('.opt-edit_other_teacher').on("click",function(){
        var opt_data = $(this).get_opt_data();
        var zs_id                 = $("<input/>");
        var arr = [
            ["指定招师",zs_id]
        ];

        $.show_key_value_table("修改指定招师信息", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax( '/channel_manage/update_zs_id',{
                    "teacherid"          : opt_data.admin_id,
                    "zs_id"              : zs_id.val(),
                });
            }
        },function(){
            $.admin_select_user(
                zs_id ,
                "admin", null,true, {
                    "main_type": 8 //分配用户
                }
            );
        });
    });
    $('.opt-change').set_input_change_event(load_data);
});

