/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-power_group_edit_new.d.ts" />

var setting = {
    view: {
        showIcon: false,
        addHoverDom: addHoverDom,
        removeHoverDom: removeHoverDom,
    },
    check: {  
        enable: true,
        chkStyle: "checkbox",  
        chkboxType: { "Y": "ps", "N": "ps" }  
    },
    edit: {
        enable: true,
        editNameSelectAll: true,
        renameTitle:'配置权限',
        showRenameBtn:showRenameBtn,
        showRemoveBtn:showRemoveBtn,
    },
    data: {
        simpleData: {
            enable: true
        }
    },
    callback: {
        onClick: zTreeOnClick,
        beforeEditName:beforeEditName,
    }
};

function beforeEditName(treeId, treeNode) {
    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
    var url      = treeNode.url;
    var powerid  = treeNode.page_id;
    var groupid = $("#groupid").val();
    //console.log(url);
    if (powerid != 0) {      
        var data = {
            "url" : url,
            "group_id" : groupid,
        };

        $.do_ajax("/user_power/get_desc_power",data,function(response){
            var data_list   = [];
            var select_list = [];
            var all_list = [];
            console.log(response.data);
            $.each( response.data,function(){
                data_list.push([this.field_name, this.desc]);
                all_list.push(this.field_name);
                if (this.field_value != undefined) {
                    this.field_value == 1 ? select_list.push (this.field_name) : '';
                }else{
                    this.default_value == true ? select_list.push (this.field_name) : '';
                }
            });
            

            $(this).admin_select_dlg({
                header_list     : [ "field_name","描述" ],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                onChange        : function( select_list,dlg) {
                    $.do_ajax("/user_power/save_desc_power",{
                        "url" : url,
                        "group_id": groupid,
                        "opt_key_list":JSON.stringify(select_list),
                        "all_list":JSON.stringify(all_list)
                    },function(){
                        dlg.close();
                    });
                }
            });
        });

    }
   
    return false;
}

function addHoverDom(treeId, treeNode) {
    //给节点加入"新增"button
    var sObj = $("#" + treeNode.tId + "_span");
    if (treeNode.editNameFlag || $("#addBtn_"+treeNode.tId).length>0) return;
    var addStr = "<span class='button add' id='addBtn_" + treeNode.tId + "' title='添加权限' onfocus='this.blur();'></span>";
    sObj.after(addStr);
    var btn = $("#addBtn_"+treeNode.tId);
    if (btn) btn.bind("click", function(){
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        var url      = treeNode.url;
        var groupid = $("#groupid").val();
        //console.log(url);
        var data = {
            "url" : url,
            "group_id" : groupid,
        };

        $.ajax({
            type : "post",
            url : "/user_power/get_input_define",
            dataType : "json",
            data:data,
            success : function(res){
                //console.log(res);
                var arr = new Array();
                if( res.status = 200 ){
                   
                    for(var x in res.data){
                        arr[x] = new Array();
                        var field_name = res.data[x].field_name;
                        var field = res.data[x].desc + "( " + field_name + " )";
                        arr[x].push(field);
                        var field_val = res.data[x].field_val;

                        //console.log(field_val);
                        var id_textarea = $("<textarea id='"+field_name+"' />");
                        var id_select = $("<select id='"+field_name+"' />");

                        if(res.data[x].value_type == "enum"){
                            Enum_map.append_option_list(res.data[x].enum_class, id_select,true);
                            field_val != undefined ? id_select.val(field_val) : '';
                            arr[x].push(id_select);
                            
                        }else if( res.data[x].value_type == "function" ){
                            Enum_map.append_option_list("function_power", id_select,true);
                            field_val != undefined ? id_select.val(field_val) : '';
                            arr[x].push(id_select);

                        }else{
                            field_val != undefined ? id_textarea.val(field_val) : '';
                            arr[x].push(id_textarea);

                        }
                                             
                    }

                    $.show_key_value_table("编辑权限", arr ,{
                        label: '确认',
                        cssClass: 'btn-warning',
                        action : function(dialog) {
                            var save_data = {};

                            for(var x in res.data){
                                var field_name = res.data[x].field_name;
                                save_data[field_name] = [ $("#"+field_name).val() , res.data[x].value_type ];
                            }
                            var data = {
                                "url" : url,
                                "group_id" : groupid,
                                "save_data":save_data
                            }

                            $.ajax({
                                type     :"post",
                                url      :"/user_power/save_input_define",
                                dataType :"json",
                                data     :data,
                                success : function(event){
                                    dialog.close()
                                }
                            });
                        }
                    })

                }
            },
            error:function(){
                BootstrapDialog.alert('取出错误');
            }
        });

        return false;
    });
    
};

function removeHoverDom(treeId, treeNode){
    $("#addBtn_"+treeNode.tId).unbind().remove();
}

//是否显示编辑button
function  showRenameBtn(treeId, treeNode){
    //获取节点所配置的noEditBtn属性值
    if(treeNode.noEditBtn != undefined && treeNode.noEditBtn){
        return false;
    }else
    {
        return !treeNode.isLastNode;
    }
}

function showRemoveBtn(){
    return false;
}

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
                //console.log(response.data);
                data_list.push([this["groupid"], this["role_groupid_str"] ,this["group_name"]  ]);
                if (this["has_power"]) {
                    select_list.push (this["groupid"]) ;
                }
            });
            

            $(this).admin_select_dlg({
                header_list     : [ "id","所属角色","名称" ],
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
        role_groupid:	$('#id_role_groupid').val(),
		    //show_flag:	$('#id_show_flag').val()
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

    $('.power_title').siblings().remove();

    Enum_map.append_option_list("account_role", $("#id_role_groupid"),true);
    $('#id_role_groupid').val(g_args.role_groupid);
	  $('#id_groupid').val($("#groupid").val());
	  //$('#id_show_flag').val(g_args.show_flag);
    //console.log(g_args);

	  $('.opt-change').set_input_change_event(load_data);

    $("#id_add_user").on("click",function(){ // 添加用户
        $.admin_select_user($("#id_add_user"), "admin",function(val){
            $.do_ajax("/user_power/add_user",{
                "user_id" : val,
                "role_groupid" : $('#id_role_groupid').val(),
            });

        });
    });

    $(".opt-del-account").on("click",function(){ // 删除账户

        var title = "你确实删除账户为： " + $(this).get_opt_data("account") + "姓名为：" + $(this).get_opt_data("name");
        var data =  {
            "uid" : $(this).get_opt_data("uid")
        };
        console.log(data);
        BootstrapDialog.confirm(title,function(ret){
            $.do_ajax("/user_power/dele_role_user",data);
        })
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

    $(".id_edit_power_group").on("click",function(){

        var edit_type = $(this).attr("edit");

        var id_edit_role_groupid =$("<select/>");
        Enum_map.append_option_list("account_role",id_edit_role_groupid,true,[1,2,3,4,5,6,7,8,9,10,11,12,13,14,1001,1002]);
        id_edit_role_groupid.val($("#id_role_groupid").val());

        var id_add_power =$("<input/>");

        var id_user_add =$("<input id='user_add'/>");

        if(edit_type == 1){
            //添加
            var edit_title = "*添加权限组";
        }else{
            //编辑
            var edit_title = "编辑权限组";
            var power_name = $('#id_groupid option:selected').text();
            var power_id = $('#id_groupid').val();
            id_add_power =$("<input value='"+power_name+"' power='"+power_id+"' />"); 
        }

        var arr=[
            ["*角色", id_edit_role_groupid ],
            [edit_title, id_add_power ],
            ["添加用户", id_user_add ],
        ];

        $.show_key_value_table(edit_title, arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                var edit_power_name = id_add_power.val();
                var edit_power_id = id_add_power.attr("power");

                if( edit_power_name == '' && edit_type == 1){
                    BootstrapDialog.alert("请添加权限组名称！");
                    return false;
                }

                var data = {
                    "edit_type":edit_type,
                    "role_groupid" : id_edit_role_groupid.val(),               
                    'edit_power_name': edit_power_name,
                    'edit_power_id' : edit_power_id,
                    'user_id':$('#user_add').attr("user_id"),
                }

                $.ajax({
                    type     :"post",
                    url      :"/user_power/edit_role_groupid",
                    dataType :"json",
                    data     :data,
                    success : function(result){
                        BootstrapDialog.alert(result['info']);
                        window.location.reload();
                    }
                });
            }
        },function(){
            $.admin_select_user($("#user_add"), "admin",function(val){
                $('#user_add').attr({"user_id":val});  
            });           
        } ,false,800)

    });

    $("#id_del_group").on("click",function(){ // 删除当前角色
        var title = "要删除当前角色:"+ $('#id_role_groupid option:selected').text() +" 权限组:" + $('#id_groupid option:selected').text() + "?";
        var role_groupid = $('#id_role_groupid').val();
        var groupid = $('#id_groupid').val();
        if( groupid == '' || groupid == undefined){
            BootstrapDialog.alert("当前角色没有权限组，所有无法删除！");
            return false;
        }

        BootstrapDialog.confirm(title,function(ret){
            if (ret){
                $.do_ajax( "/user_power/dele_role_groupid",{
                    groupid: groupid,
                    role_groupid : role_groupid,      
                },function(){
                    window.location = "/user_manage_new/power_group_edit_new";
                });
            }
        });
    });

    // $("#id_add_group").on("click",function(){ // 新增角色
    //     BootstrapDialog.confirm("要新增角色?!",function(ret){
    //         if (ret){
    //             $.do_ajax( "/user_manage_new/power_group_add",{
    //             });
    //         }
    //     });
    // });

    // $("#id_edit_group").on("click",function(){ // 修改角色名
    //     var v=$("#id_groupid").find("option:selected").text();

    //     $.show_input("修改角色名",  v, function(val){
    //         val=$.trim(val);
    //         if (!val) {
    //             alert("名称不能为空");
    //         }else{
    //             $.do_ajax( "/user_manage_new/power_group_set_name",{
    //                 "groupid" : g_args.groupid,
    //                 "group_name" : val
    //             });
    //         }
    //     });
    // });

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

        $.do_ajax("/user_power/set_group_power", {
            "groupid" : $('#id_groupid').val(),
            "role_groupid" : $("#id_role_groupid").val(),
            "power_list_str" : power_list
        });

    });
});
