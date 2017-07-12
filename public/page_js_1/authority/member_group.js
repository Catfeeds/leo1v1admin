$(function(){
    Enum_map.append_option_list("admin_group", $(".group_id"));
    $(".admin_id").val(g_args.admin_id);
    $(".group_id").val(g_args.group_id);

    admin_select_user($(".admin_id"), "admin",function(){
        load_data();
    });


    function load_data(){
        reload_self_page({
            admin_id : $(".admin_id").val(),
		    group_id : $(".group_id").val()
        });
	}

    //筛选
	$(".will_change").on("change",function(){
		load_data();
	});





    $('.add_member').on('click', function(){
        var id_adminid = $("<input>");
        var id_groupid = $("<select/>");
        
        Enum_map.append_option_list("admin_group", id_groupid,true);
        
        var arr = [
            [ "adminid",  id_adminid] ,
            [ "groupid",  id_groupid] ,
        ];


      
        show_key_value_table("新增成员", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var adminid      = id_adminid.val();
                var groupid      = id_groupid.val();
                if (!adminid || !groupid) {
                    BootstrapDialog.alert('请将信息填写完整');
                    return;
                }
                $.ajax({
                    url  : '/authority/add_member_info',
                    type : 'POST',
                    data : {
                        'adminid'    : adminid,
                        'groupid'    : groupid
                    },
                    dataType: 'json',
	                success  : function(result){
                        if(result['ret'] != 0){
				            alert(result['info']);
                        }else{
                            window.location.reload();
                        }
			        }

                });
			    dialog.close();
            }
        });

        id_adminid.admin_select_dlg_ajax({
            "opt_type" :  "select", // or "list"
            "url"          : "/user_manage/get_user_list",
            //其他参数
            "args_ex" : {
                type  :  "admin"
            },

            select_primary_field : "id",
            select_display       : "nick",   //选好的显示类别
            select_no_select_value  :  0  , // 没有选择是，设置的值 
            select_no_select_title  :  "[未设置]"  , // "未设置"

            //字段列表
            'field_list' :[
                {
                    title:"sellerid",
                    width :50,
                    field_name:"id"
                },{
                    title:"昵称",
                    field_name:"nick"
                },{
                    title:"手机",
                    field_name:"phone"
                }
            ] ,
            //查询列表
            filter_list:[
                [
                    {
                        size_class: "col-md-4" ,
                        title :"性别",
                        type  : "select" ,
                        'arg_name' :  "gender"  ,

                        select_option_list: [ {
                            value : -1 ,
                            text :  "全部" 
                        },{
                            value :  1 ,
                            text :  "男" 
                        },{
                            value :  3001,
                            text :  "女" 
                        }]
                    },{
                        size_class: "col-md-8" ,
                        title :"姓名/手机",
                        'arg_name' :  "nick_phone"  ,
                        type  : "input" 
                    }

                ] 
            ],

            "auto_close"       : true,
            //选择
            "onChange"         : null,
            //加载数据后，其它的设置
            "onLoadData"       : null

        });



        
    });

    $('.opt-update-member').on('click', function(){
        var id_adminid = $("<input>");
        var id_groupid = $("<select/>");
        
        Enum_map.append_option_list("admin_group", id_groupid,true);
        var old_adminid = $(this).get_opt_data("id"); 
      
        do_ajax("/authority/get_member_info",{
            "adminid":old_adminid
        },function(result){
            id_adminid.val(result.adminid);
            id_groupid.val(result.groupid);
        });
        var arr = [
            [ "adminid",  id_adminid] ,
            [ "groupid",  id_groupid] ,
        ];
        show_key_value_table("修改成员", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var adminid      = id_adminid.val();
                var groupid      = id_groupid.val();
                $.ajax({
                    url  : '/authority/edit_member_info',
                    type : 'POST',
                    data : {
                        'old_adminid' : old_adminid,
                        'adminid'     : adminid,
                        'groupid'     : groupid
                    },
                    dataType: 'json',
	                success  : function(result){
                        if(result['ret'] != 0){
				            alert(result['info']);
                        }else{
                            window.location.reload();
                        }
			        }

                });
			    dialog.close();
            }


        });
            id_adminid.admin_select_dlg_ajax({
                "opt_type" :  "select", // or "list"
                "url"          : "/user_manage/get_user_list",
                //其他参数
                "args_ex" : {
                    type  :  "admin"
                },

                select_primary_field : "id",
                select_display       : "nick",   //选好的显示类别
                select_no_select_value  :  0  , // 没有选择是，设置的值 
                select_no_select_title  :  "[未设置]"  , // "未设置"

                //字段列表
                'field_list' :[
                    {
                        title:"sellerid",
                        width :50,
                        field_name:"id"
                    },{
                        title:"昵称",
                        field_name:"nick"
                    },{
                        title:"手机",
                        field_name:"phone"
                    }
                ] ,
                //查询列表
                filter_list:[
                    [
                        {
                            size_class: "col-md-4" ,
                            title :"性别",
                            type  : "select" ,
                            'arg_name' :  "gender"  ,

                            select_option_list: [ {
                                value : -1 ,
                                text :  "全部" 
                            },{
                                value :  1 ,
                                text :  "男" 
                            },{
                                value :  3001,
                                text :  "女" 
                            }]
                        },{
                            size_class: "col-md-8" ,
                            title :"姓名/手机",
                            'arg_name' :  "nick_phone"  ,
                            type  : "input" 
                        }

                    ] 
                ],

                "auto_close"       : true,
                //选择
                "onChange"         : null,
                //加载数据后，其它的设置
                "onLoadData"       : null

            });
        
    });




    $(".opt-del").on("click", function(){
        var adminid = $(this).get_opt_data("id"); 
        show_message("删除","要删除成员'"+adminid+"'吗?!" , function(dialog){
            $.ajax({
                url: '/authority/delete_member',
                type: 'POST',
                dataType: 'json',
                data: {
                    'adminid': adminid
			    },
                success: function(data) {
                    if(data.ret == -1){
                        alert(data.info);
                    }else{
                        window.location.reload();
                    }
                }
            });
        });
    });


});
