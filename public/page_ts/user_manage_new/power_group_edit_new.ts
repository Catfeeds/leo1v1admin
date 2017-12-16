``// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-power_group_edit_new.d.ts" />

     var setting = {view: {showIcon: false},
         check: {  
             enable: true,  
             chkStyle: "checkbox",  
             chkboxType: { "Y": "ps", "N": "ps" }  
         }, 
         data: {
             simpleData: {
                 enable: true
             }
         },
         callback: {
             onClick: zTreeOnClick
         }

     };

function zTreeOnClick(event, treeId, treeNode) {
    var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
    if (treeNode.page_id != 0) {
        var powerid  = treeNode.page_id;
        $.do_ajax("/user_manage_new/get_group_list_by_powerid",{
            "powerid" : powerid
        },function(response){
            var data_list   = [];
            var select_list = [];
            $.each( response.data,function(){
                data_list.push([this["groupid"], this["group_name"]  ]);
                if (this["has_power"]) {
                    select_list.push (this["groupid"]) ;
                }
            });

            $(this).admin_select_dlg({
                header_list     : [ "id","名称" ],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                onChange        : function( select_list,dlg) {
                    $.do_ajax("/user_manage_new/set_power_with_groupid_list",{
                        powerid: powerid,
                        groupid_list:JSON.stringify(select_list)
                    },function(){
                        dlg.close();
                    });
                }
            });
        }) ;
    } else {
        if (treeNode.open == true) {
            treeObj.expandNode(treeNode, false, true, true);
        } else {
            treeObj.expandNode(treeNode, true, true, true);
        }
    }
}
function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		groupid:	$('#id_groupid').val(),
		show_flag:	$('#id_show_flag').val()
    });
}
$(function(){
    $.fn.zTree.init($("#treeDemo"), setting, zNodes);

    // 处理全选
    var treeObj = $.fn.zTree.getZTreeObj('treeDemo');
    var nodes = treeObj.getCheckedNodes(true);
    for(var i = 0; i < nodes.length; i ++) {
        if (nodes[i].name == '服务管理') {
            nodes[i].checked = true;
            treeObj.updateNode(nodes[i]); 
        }
    }

	$('#id_groupid').val(g_args.groupid);
	$('#id_show_flag').val(g_args.show_flag);


	  $('.opt-change').set_input_change_event(load_data);

    $("#id_add_user").on("click",function(){ // 添加用户
        $.admin_select_user($("#id_add_user"), "admin",function(val){
            $.do_ajax("/user_manage_new/opt_accont_group",{
                "uid" : val ,
                "groupid" : g_args.groupid ,
                "opt_type" :"add"
            });

        });
    });

    $(".opt-del-account").on("click",function(){ // 删除账户
        $.do_ajax("/user_manage_new/opt_accont_group",{
            "uid" : $(this).get_opt_data("uid"),
            "groupid" : g_args.groupid ,
            "opt_type" :"del"
        });
    });

    $("#id_show_power").on("click",function(){ // 显示有权限部分
        var treeObj = $.fn.zTree.getZTreeObj('treeDemo');
        var nodes = treeObj.getCheckedNodes(false);
        //alert(nodes.length);
        for (var i = 0; i < nodes.length; i ++) {
            if (nodes[i].isParent) {
                var child = nodes[i].children;
                var flag = true;
                for (var j = 0; j < child.length; j ++) {
                    if (child[j].checked == true) {
                        flag = false;
                        break;
                    }
                    if (child[j].isParent && child[j].checked == false) {
                        var three = child[j].children;
                        for(var k = 0; k < three.length; k ++) {
                            if (three[k].checked == true) {
                                flag = false;
                                break;
                            }
                        }
                    }
                }
                if (flag == true) {
                    treeObj.hideNode(nodes[i]);
                }
            } else {
                treeObj.hideNode(nodes[i]);
            }        
        }
    });

    $("#id_show_all_power").on("click",function(){ // 显示所有权限
        var treeObj = $.fn.zTree.getZTreeObj('treeDemo');
        //var nodes = treeObj.getNodesByParam("isHidden", true);
        var nodes = treeObj.getNodes();
        if (nodes[0].open == true) {
            //treeObj.hideNodes(nodes);
            treeObj.expandAll(false);
        } else {
            treeObj.showNodes(nodes);
            treeObj.expandAll(true);
        }
    });

    $("#id_del_group").on("click",function(){ // 删除当前角色
        BootstrapDialog.confirm("要删除当前角色?!",function(ret){
            if (ret){
                $.do_ajax( "/user_manage_new/power_group_del",{
                    groupid: g_args.groupid
                });
            }
        });
      //
    });
    $("#id_add_group").on("click",function(){ // 新增角色
        BootstrapDialog.confirm("要新增角色?!",function(ret){
            if (ret){
                $.do_ajax( "/user_manage_new/power_group_add",{
                });
            }
        });
    });

    $("#id_edit_group").on("click",function(){ // 修改角色名
        var v=$("#id_groupid").find("option:selected").text();

        $.show_input("修改角色名",  v, function(val){
            val=$.trim(val);
            if (!val) {
                alert("名称不能为空");
            }else{
                $.do_ajax( "/user_manage_new/power_group_set_name",{
                    "groupid" : g_args.groupid,
                    "group_name" : val
                });
            }
        });
    });

    $("#id_reload_power").on("click",function(){ // 更新在线用户权限
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/user_deal/set_reload_power_time",{});
    });


    $('#id_submit_power').on('click', function() { // 提交按钮
        var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
        var nodes = treeObj.getCheckedNodes(true);

        var power_list = '';
        for (var i = 0; i < nodes.length; i ++) {
            if (nodes[i].page_id != 0) {
                power_list += nodes[i].page_id + ',';
            }
        }

        if (power_list) {
            power_list = power_list.substr(0, power_list.length-1);
        }

        $.do_ajax("/user_manage_new/set_group_power", {
            "groupid" :g_args.groupid,
            "power_list_str" : power_list
        },function(){
            $.do_ajax("/user_deal/reload_account_power",{});
        });

    });
});
