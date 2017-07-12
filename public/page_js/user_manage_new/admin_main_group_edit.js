/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-admin_main_group_edit.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			main_type:	$('#id_main_type').val(),
			groupid:	$('#id_groupid').val()
        });
    }


    Enum_map.append_option_list("account_role", $('#id_main_type'),true );
	$('#id_main_type').val(g_args.main_type);
	$('#id_groupid').val(g_args.groupid);

    $("#id_edit_group").on("click",function(){
        if($('#id_main_type').val() == 0){
            alert('请先选择主分类!');
            return;
        }
        if($('#id_groupid').val() == null){
            alert('请先选择次分类');
            return;
        }
        var id_group_name=$("<input/>");
	    var id_master_adminid=$("<input/>");
        id_master_adminid.val($("#id_group_master").data("master_adminid"));


        var  arr=[
            ["组名" ,  id_group_name],
            ["主管" ,  id_master_adminid]
        ];
        
        id_group_name.val( $("#id_groupid").find( "option:selected") .text()  );
        
        $.show_key_value_table("修改分组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/admin_main_group_edit",{
                    "groupid" : g_args.groupid,
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

    $("#id_add_group").on("click",function(){
        if($('#id_main_type').val() == 0){
            alert('请先选择主分类!');
            return;
        }
	    var id_group_name=$("<input/>");
        var  arr=[
            ["组名" ,  id_group_name]
        ];
        
        $.show_key_value_table("新增主管分组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/admin_main_group_add",{
                    "main_type" : g_args.main_type,
                    "group_name" : id_group_name.val()
                });
            }
        });

    });

    $("#id_asign_group").on("click",function(){
        var main_groupid = $('#id_groupid').val();
        var main_type = g_args.main_type;
	    if(main_groupid == null){
            alert('请选择主管');
        }else{
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
                            "up_groupid":main_groupid
                        },function(resp){
                            window.location.reload(); 
                        });
                    }else{
                        alert( "请选择小组" );
                    }                
                },
                "onLoadData" : null
            });

        }

    });

    $("#id_del_group").on("click",function(){
        if($('#id_groupid').val() == null){
            alert('请先选择次分类');
            return;
        }
        BootstrapDialog.confirm(
            "要删除主管分组:"+ $("#id_groupid").find( "option:selected") .text()   + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/user_deal/admin_main_group_del", {
                        "groupid": g_args.groupid
                    });
                }
            }
        );
    });


    $("#id_add_group_user").on("click",function(){
        if($('#id_groupid').val() == null){
            alert('请先选择次分类');
            return;
        }
       	var id_child_group_name=$("<input/>");
        var  arr=[
            ["小组" ,  id_child_group_name]
        ];
        
        $.show_key_value_table("新增小组", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/admin_group_add",{
                    "main_type" :$('#id_main_type').val() ,
                    "group_name" : id_child_group_name.val(),
                    "up_groupid": $('#id_groupid').val()
                });
            }
        });

    });
        
    $(".opt-del").on("click",function(){
        var opt_data=$(this).get_opt_data();
        
        BootstrapDialog.confirm(
            "要彻底删除:"+ opt_data.admin_nick  + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/user_deal/admin_main_group_user_del", {
                        "groupid": opt_data.child_groupid
                    }) ;
                }
            }
        );

	    
    });
    $(".opt-remove").on("click",function(){
        var opt_data=$(this).get_opt_data();
        
        BootstrapDialog.confirm(
            "要移除:"+ opt_data.admin_nick  + "?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/user_deal/admin_main_group_user_remove", {
                        "groupid": opt_data.child_groupid
                    }) ;
                }
            }
        );

	    
    });

    $(".opt-edit").on("click",function(){      
        var opt_data=$(this).get_opt_data();
       	var id_group_assign_percent=$("<input/>");
        var  arr=[
            ["比例(请输入整数,如10%,输入10即可)" ,  id_group_assign_percent]
        ];
        
        $.show_key_value_table("配置比例", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/update_group_assign_percent",{
                    "groupid": opt_data.child_groupid,
                    "group_assign_percent" :id_group_assign_percent.val()
                });
            }
        });

    });


	$('.opt-change').set_input_change_event(load_data);
});

