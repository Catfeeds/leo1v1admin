/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/campus_manage-admin_campus_manage.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }


    $(".common-table" ).table_admin_level_4_init();
    $("#id_add_campus").on("click",function(){
        var id_campus_name=$("<input/>");
        var  arr=[
            ["名称" ,  id_campus_name]
        ];
        
        $.show_key_value_table("新增校区", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/campus_manage/add_admin_campus",{
                    "campus_name" :id_campus_name.val(),
                });
            }
        });
        
    });

    $(".opt-assign-main-group,.opt-del-campus,.opt-edit-name").each(function(){
        var opt_data = $(this).get_opt_data();
        if(opt_data.level != "l-1"){
            $(this).hide();
        }
    });

    $(".opt-edit-name").on("click",function(){
        var opt_data = $(this).get_opt_data();  
        var id_campus_name=$("<input/>");
        var  arr=[
            ["名称" ,  id_campus_name]
        ];
        
        id_campus_name.val(opt_data.campus_name);
        $.show_key_value_table("修改", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/campus_manage/update_admin_campus_name",{
                    "campus_name" :id_campus_name.val(),
                    "campus_id":opt_data.campus_id,
                });
            }
        });
 
    });
    $(".opt-assign-main-group").on("click",function(){
        var opt_data = $(this).get_opt_data();       
        var main_type    = opt_data.main_type;        
        var up_groupid =opt_data.up_groupid ;

	    $("<div></div>").admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/user_deal/get_group_list_campus",
            //其他参数
            "args_ex" : {
            },

            select_primary_field   : "groupid",
            select_display         : "package_name",
            select_no_select_value : 0,
            select_no_select_title : "[未设置]",

            //字段列表
            'field_list' :[
                {
                    title:"groupid",
                    width :50,
                    field_name:"groupid"
                },{
                    title:"类型",
                    field_name:"main_type_str"
                },{
                    title:"组名",
                    field_name:"group_name"
                },{
                    title:"助长",
                    field_name:"group_master_nick"
                }
            ] ,
            //查询列表
            filter_list:[
            ],
            "auto_close" : true,
            "onChange"   : function( val) {
                var groupid = val ;
                var me=this;
                if (groupid>0) {
                    $.do_ajax("/campus_manage/set_campus_id",{
                        "campus_id":opt_data.campus_id,
                        "groupid":groupid
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

    $(".opt-del-campus").on("click",function(){
        var opt_data = $(this).get_opt_data();        

        BootstrapDialog.confirm(
            "要删除校区吗:"+ opt_data.campus_name   + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/campus_manage/campus_del", {
                        "campus_id": opt_data.campus_id
                    });
                }
            }
        );
    });

    $(".opt-del-main-group").each(function(){
        var opt_data = $(this).get_opt_data();
        if(opt_data.level != "l-2"){
            $(this).hide();
        }
    });

    $(".opt-del-main-group").on("click",function(){
        var opt_data = $(this).get_opt_data();        

        BootstrapDialog.confirm(
            "要删除主管分组:"+ opt_data.up_group_name   + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/campus_manage/admin_main_group_del", {
                        "groupid": opt_data.up_groupid
                    });
                }
            }
        );
    });

  
	$('.opt-change').set_input_change_event(load_data);
});









