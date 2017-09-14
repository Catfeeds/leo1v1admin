/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-admin_group_manage.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            monthtime_flag:	$('#id_monthtime_flag').val()
        });
    }

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });



    // table_admin_level_4_init:function(show_flag) {

    //     var $table=$(this);
    //     if (!show_flag) {
    //         $.each($table.find(".l-2,.l-3,.l-4,.l-5"),function(){
    //             $(this).hide();
    //         });
    //     }
    //     var link_css=        {
    //         color: "#3c8dbc",
    //         cursor:"pointer"
    //     };

    //     $table.find(".l-1 .main_type").css(link_css);
    //     $table.find(".l-2 .first_group_name").css(link_css);
    //     $table.find(".l-3 .up_group_name").css(link_css);
    //     $table.find(".l-4 .group_name").css(link_css);
    //     var switch_show_flag=function($item, self_class,  parent_class  ,  level_count ) {
    //         var show_flag= $item.data("show");
    //         if (!show_flag) {
    //             show_flag=0;
    //         }
    //         if (!level_count) {
    //             level_count=4;
    //         }
    //         show_flag= (show_flag+1)% level_count;
    //         $item.data("show" ,show_flag) ;

    //         var class_name= $item.data("class_name");

    //         var $opt_item=null;
    //         var select_class= "."+self_class+"."+class_name;
    //         if (show_flag ==1 ) {
    //             $opt_item=$table.find( select_class).parent("." +parent_class  );
    //             $opt_item.show();
    //         } else  if (show_flag ==2 ) {
    //             $opt_item=$table.find(select_class),
    //             $opt_item.parent().show();
    //         } else  if (show_flag ==3 ) {
    //             $opt_item=$table.find(select_class),
    //             $opt_item.parent().show();
    //         }else{
    //             $table.find(select_class).parent().hide();
    //         }
    //         $item.parent().show();
    //         return show_flag;
    //     };


    //     $table.find(".l-1 .main_type").on("click",function(){
    //         switch_show_flag($(this), "up_group_name","l-2");
    //     });

    //     $table.find(".l-2 .first_group_name").on("click",function(){
    //         switch_show_flag($(this), "group_name","l-3");
    //     });


    //     $table.find(".l-3 .up_group_name").on("click",function(){
    //         switch_show_flag($(this), "group_name","l-4");
    //     });

    //     $table.find(".l-4 .group_name").on("click",function(){
    //         switch_show_flag($(this), "account","l-5",2);
    //     });
    // },



    $('#id_monthtime_flag').val(g_args.monthtime_flag);

    $(".common-table" ).table_admin_level_4_init();

    if(g_args.monthtime_flag==1){
        $("#id_copy_now").parent().hide();
    }
    $(".opt-add-main-group,.opt-add-main-group-new").each(function(){
        var opt_data = $(this).get_opt_data();
        var level    = opt_data.level;
        var main_type    = opt_data.main_type;
        if(main_type =="未定义" || level != "l-1"){
            $(this).hide();
        }
    });
    $(".opt-edit-main-group,.opt-add-main-group-user,.opt-del-main-group,.opt-assign-main-group,.opt-edit-main-group-new,.opt-add-main-group-user-new,.opt-del-main-group-new,.opt-assign-main-group-new").each(function(){
        var opt_data = $(this).get_opt_data();
        var level    = opt_data.level;
        var main_type    = opt_data.main_type;
        if(main_type =="未定义" || level != "l-2"){
            $(this).hide();
        }
    });
    $(".opt-edit-group,.opt-del-group,.opt-assign-group-user,.opt-edit-group-new,.opt-del-group-new,.opt-assign-group-user-new").each(function(){
        var opt_data = $(this).get_opt_data();
        var level    = opt_data.level;
        var main_type    = opt_data.main_type;
        if(main_type =="未定义" || level != "l-3"){
            $(this).hide();
        }
    });

    $(".opt-del-admin,.opt-del-admin-new").each(function(){
        var opt_data = $(this).get_opt_data();
        var level    = opt_data.level;
        var main_type    = opt_data.main_type;
        if(main_type =="未定义" || level != "l-4"){
            $(this).hide();
        }
    });

    // $(".opt-del-admin,.opt-del-admin-new").each(function(){
    //     var opt_data = $(this).get_opt_data();
    //     var level    = opt_data.level;
    //     var main_type    = opt_data.main_type;
    //     if(main_type =="未定义" || level != "l-5"){
    //         $(this).hide();
    //     }
    // });



    $(".opt-add-main-group").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var main_type    = opt_data.main_type;
        //  alert(main_type);
        var id_group_name=$("<input/>");
        var  arr=[
            ["组名" ,  id_group_name]
        ];

        $.show_key_value_table("新增主管分组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/admin_main_group_add",{
                    "main_type" : main_type,
                    "group_name" : id_group_name.val()
                });
            }
        });

    });

    $(".opt-edit-main-group").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var main_type    = opt_data.main_type;
        var id_group_name=$("<input/>");
        var id_master_adminid=$("<input/>");


        var  arr=[
            ["组名" ,  id_group_name],
            ["主管" ,  id_master_adminid]
        ];

        id_group_name.val( opt_data.up_group_name );
        id_master_adminid.val( opt_data.up_master_adminid );

        $.show_key_value_table("修改分组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/admin_main_group_edit",{
                    "groupid" :opt_data.up_groupid ,
                    "group_name" : id_group_name.val(),
                    "master_adminid" : id_master_adminid.val()
                });
            }
        },function(){
            $.admin_select_user(
                id_master_adminid ,
                "admin", null,true, {
                    "main_type": $('#id_main_type').val()//分配用户
                }
            );

        });
    });

    $(".opt-add-main-group-user").on("click",function(){

        var opt_data = $(this).get_opt_data();
        var main_type    = opt_data.main_type;

        var id_child_group_name=$("<input/>");
        var  arr=[
            ["小组" ,  id_child_group_name]
        ];

        $.show_key_value_table("新增小组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/admin_group_add",{
                    "main_type" :main_type ,
                    "group_name" : id_child_group_name.val(),
                    "up_groupid": opt_data.up_groupid
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
            "url"      : "/user_deal/get_group_list_new",
            //其他参数
            "args_ex" : {
                "main_type":main_type
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
                    $.do_ajax("/user_deal/set_up_groupid",{
                        "groupid":groupid,
                        "up_groupid":up_groupid
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

    $(".opt-del-main-group").on("click",function(){
        var opt_data = $(this).get_opt_data();

        BootstrapDialog.confirm(
            "要删除主管分组:"+ opt_data.up_group_name   + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/user_deal/admin_main_group_del", {
                        "groupid": opt_data.up_groupid
                    });
                }
            }
        );
    });


    $(".opt-edit-group").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id_group_name=$("<input/>");
        var id_master_adminid=$("<input/>");


        var  arr=[
            ["组名" ,  id_group_name],
            ["助长" ,  id_master_adminid]
        ];

        id_master_adminid.val(opt_data.master_adminid);
        id_group_name.val(opt_data.group_name );

        $.show_key_value_table("修改分组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/admin_group_edit",{
                    "groupid" : opt_data.groupid,
                    "group_name" : id_group_name.val(),
                    "master_adminid" : id_master_adminid.val()
                });
            }
        },function(){
            $.admin_select_user(
                id_master_adminid ,
                "admin", null,true, {
                    "main_type": 2 //分配用户
                }
            );

        });

    });


    $(".opt-del-group").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除分组:"+ opt_data.group_name   + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/user_deal/admin_group_del", {
                        "groupid": opt_data.groupid
                    }) ;
                }
            }
        );
    });


    $(".opt-assign-group-user").on("click",function(){
        var opt_data = $(this).get_opt_data();
        $.admin_select_user( $("<div></div>") , "admin" , function (adminid){
            if (adminid>0) {
                $.do_ajax("/user_deal/admin_group_user_add",{
                    "groupid" : opt_data.groupid,
                    "main_type" : opt_data.main_type,
                    "adminid" :  adminid
                });
            }
        });
    });


    $(".opt-del-admin").on("click",function(){
        var opt_data=$(this).get_opt_data();

        BootstrapDialog.confirm(
            "要删除:"+ opt_data.account  + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/user_deal/admin_group_user_del", {
                        "groupid": opt_data.groupid,
                        "adminid": opt_data.adminid
                    }) ;
                }
            }
        );


    });

    $(".opt-set-subject").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id_subject=$("<select/>");

        Enum_map.append_option_list("subject", id_subject,true );
        var  arr=[
            ["科目" ,  id_subject],
        ];
        $.do_ajax("/user_deal/get_admin_group_subject",{
            "groupid" : opt_data.groupid
        },function(result){
            id_subject.val(result.subject);
        });

        $.show_key_value_table("修改分组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/set_admin_group_subject",{
                    "groupid" : opt_data.groupid,
                    "subject" : id_subject.val(),
                });
            }
        });

    });


    //alert(111);
    $(".opt-add-main-group-new").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var main_type    = opt_data.main_type;
        //  alert(main_type);
        var id_group_name=$("<input/>");
        var  arr=[
            ["组名" ,  id_group_name]
        ];

        $.show_key_value_table("新增主管分组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/admin_main_group_add_new",{
                    "main_type" : main_type,
                    "group_name" : id_group_name.val(),
                    "start_time" : g_args.start_time
                });
            }
        });

    });

    $(".opt-edit-main-group-new").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var main_type    = opt_data.main_type;
        var id_group_name=$("<input/>");
        var id_master_adminid=$("<input/>");


        var  arr=[
            ["组名" ,  id_group_name],
            ["主管" ,  id_master_adminid]
        ];

        id_group_name.val( opt_data.up_group_name );
        id_master_adminid.val( opt_data.up_master_adminid );

        $.show_key_value_table("修改分组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/admin_main_group_edit_new",{
                    "groupid" :opt_data.up_groupid ,
                    "group_name" : id_group_name.val(),
                    "master_adminid" : id_master_adminid.val(),
                    "start_time" : g_args.start_time
                });
            }
        },function(){
            $.admin_select_user(
                id_master_adminid ,
                "admin", null,true, {
                    "main_type": $('#id_main_type').val()//分配用户
                }
            );

        });
    });

    $(".opt-add-main-group-user-new").on("click",function(){

        var opt_data = $(this).get_opt_data();
        var main_type    = opt_data.main_type;

        var id_child_group_name=$("<input/>");
        var  arr=[
            ["小组" ,  id_child_group_name]
        ];

        $.show_key_value_table("新增小组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/admin_group_add_new",{
                    "main_type" :main_type ,
                    "group_name" : id_child_group_name.val(),
                    "up_groupid": opt_data.up_groupid,
                    "start_time" : g_args.start_time
                });
            }
        });

    });

    $(".opt-assign-main-group-new").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var main_type    = opt_data.main_type;
        var up_groupid =opt_data.up_groupid ;

        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/user_deal/get_group_list_new_month",
            //其他参数
            "args_ex" : {
                "main_type":main_type,
                "start_time" : g_args.start_time
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
                    $.do_ajax("/user_deal/set_up_groupid_new",{
                        "groupid":groupid,
                        "up_groupid":up_groupid,
                        "start_time" : g_args.start_time
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

    $(".opt-del-main-group-new").on("click",function(){
        var opt_data = $(this).get_opt_data();

        BootstrapDialog.confirm(
            "要删除主管分组:"+ opt_data.up_group_name   + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/user_deal/admin_main_group_del_new", {
                        "groupid": opt_data.up_groupid,
                        "start_time" : g_args.start_time
                    });
                }
            }
        );
    });


    $(".opt-edit-group-new").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id_group_name=$("<input/>");
        var id_master_adminid=$("<input/>");


        var  arr=[
            ["组名" ,  id_group_name],
            ["助长" ,  id_master_adminid]
        ];

        id_master_adminid.val(opt_data.master_adminid);
        id_group_name.val(opt_data.group_name );

        $.show_key_value_table("修改分组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/admin_group_edit_new",{
                    "groupid" : opt_data.groupid,
                    "group_name" : id_group_name.val(),
                    "master_adminid" : id_master_adminid.val(),
                    "start_time" : g_args.start_time
                });
            }
        },function(){
            $.admin_select_user(
                id_master_adminid ,
                "admin", null,true, {
                    "main_type": 2 //分配用户
                }
            );

        });

    });


    $(".opt-del-group-new").on("click",function(){
        var opt_data = $(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除分组:"+ opt_data.group_name   + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/user_deal/admin_group_del_new", {
                        "groupid": opt_data.groupid,
                        "start_time" : g_args.start_time
                    }) ;
                }
            }
        );
    });


    $(".opt-assign-group-user-new").on("click",function(){
        var opt_data = $(this).get_opt_data();
        // alert(g_args.start_time);
        $.admin_select_user( $("<div></div>") , "admin" , function (adminid){
            if (adminid>0) {
                $.do_ajax("/user_deal/admin_group_user_add_new",{
                    "groupid" : opt_data.groupid,
                    "main_type" : opt_data.main_type,
                    "adminid" :  adminid,
                    "start_time" : g_args.start_time
                });
            }
        });
    });


    $(".opt-del-admin-new").on("click",function(){
        var opt_data=$(this).get_opt_data();

        BootstrapDialog.confirm(
            "要删除:"+ opt_data.account  + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/user_deal/admin_group_user_del_new", {
                        "groupid": opt_data.groupid,
                        "adminid": opt_data.adminid,
                        "start_time" : g_args.start_time
                    }) ;
                }
            }
        );


    });

    $("#id_copy_now").on("click",function(){
        var opt_data=$(this).get_opt_data();

        BootstrapDialog.confirm(
            "确定要复制当前数据吗?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/user_deal/copy_admin_group_info", {
                        "start_time" : g_args.start_time
                    }) ;
                }
            }
        );

    });



    $('.opt-change').set_input_change_event(load_data);
});
