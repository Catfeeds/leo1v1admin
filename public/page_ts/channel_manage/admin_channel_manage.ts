/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/channel_manage-admin_channel_manage.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }
    $(".common-table" ).table_admin_level_4_init();
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

    $(" .opt-assign-admin").each(function(){
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

    $('.opt-change').set_input_change_event(load_data);
});

