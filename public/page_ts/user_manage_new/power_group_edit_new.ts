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
                    $.do_ajax("/user_manage_new/set_power_with_groupid_list_new",{
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
    });
}
$(function(){

    $.fn.zTree.init($("#treeDemo"), setting, window["zNodes"]);

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

    get_search_group(g_args.role_groupid);
    $('#id_groupid').val(g_args.groupid);

    $("#search_this").on('click',function(){
        var role_groupid = $('#id_role_groupid').val();
        var groupid = $("#id_groupid").val();
        window.location.href = "/user_manage_new/power_group_edit_new?groupid="+groupid+"&role_groupid="+role_groupid;
    })

    // 添加用户
    $("#id_add_user").on("click",function(){
        $.admin_select_user($("#id_add_user"), "admin",function(val){
            $.do_ajax("/user_power/add_user",{
                "user_id" : val,
                "role_groupid" : $('#id_role_groupid').val(),
                "groupid" : $("#id_groupid").val(),
            },function(response){
                if(response.ret == -1){
                    BootstrapDialog.alert(response.info);
                    return false;
                }else{
                    window.location.reload();
                }
            })
        });
    });

    //批量添加用户
    $('#batch_add_user').on("click",function(){
        var cur_role = $("#id_role_groupid").val();
        var cur_power = $("#id_groupid").val();
        var id_add_role_groupid = $("<select onchange='get_role_group(this.options[this.options.selectedIndex].value)'/>");
        Enum_map.append_option_list("account_role",id_add_role_groupid,true);
        id_add_role_groupid.val(cur_role);

        var id_change_role = $("<select >");
        Enum_map.append_option_list("boolean",id_change_role,true,[1,0]);

        var id_add_power =$("<select id='power_group'/>");
        var $group = $.trim($("#role_"+cur_role).clone().html());
        id_add_power.html($group);
        id_add_power.val(cur_power);

        var id_user_add =$("<input id='user_add' style='width:80%'/>");

        var arr=[
            ["角色", id_add_role_groupid ],
            ["是否改变用户角色", id_change_role ],
            ["权限组", id_add_power ],
            ["添加用户", id_user_add ],
        ];
        $.show_key_value_table("批量添加用户", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                var edit_power_name = id_add_power.val();
                var edit_power_id = id_add_power.attr("power");

                var data = {
                    "change_role":id_change_role.val(),
                    "role_groupid" : id_add_role_groupid.val(),
                    'groupid': id_add_power.val(),
                    'uid_str' : id_user_add.attr("uid_str"),
                }

                $.ajax({
                    type     :"post",
                    url      :"/user_power/batch_add_user",
                    dataType :"json",
                    data     :data,
                    success : function(result){
                        BootstrapDialog.alert(result['info']);
                        if(result.ret == 0){
                            window.location.reload();
                        }
                    }
                });
            }
        },function(){
            id_user_add.admin_select_dlg_ajax_more({
                "opt_type" : "select", // or "list"
                "url"      : "/user_power/get_user_list",
                //其他参数
                "args_ex" : {
                    //type  :  "teacher"
                },

                select_primary_field   : "id",   //要拿出来的值
                select_display         : "name",
                select_no_select_value : 0,
                select_no_select_title : "[未设置]",
                width : 1000,
                //字段列表
                'field_list' :[
                    {
                    title:"id",
                    width :50,
                    field_name:"id"
                },{
                    title:"name",
                    render:function(val,item) {
                        return item.name;
                    }
                },{
                    title:"account",
                    render:function(val,item) {
                        return item.account;
                    }
                },{
                    title:"角色",
                    field_name:"account_role_str"
                },{
                    title:"电话",
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
                            value :  2 ,
                            text :  "女"
                        }]
                    },{
                        size_class : "col-md-8" ,
                        title      : "姓名/电话",
                        'arg_name' : "name_phone"  ,
                        type       : "input"
                    }

                ]
                ],
                "auto_close"       : true,
                //选择
                "onChange"         : function(val,row_data){
                    $("#user_add").attr({"uid_str":val});
                    console.log(val);
                },
                //加载数据后，其它的设置
                "onLoadData"       : null

            });


        } ,false,800)

    });

    // 删除账户
    $(".opt-del-account").on("click",function(){

        var title = "你确实删除账户为： " + $(this).get_opt_data("account") + "姓名为：" + $(this).get_opt_data("name");
        var data =  {
            "uid" : $(this).get_opt_data("uid"),
            "role_groupid" : $('#id_role_groupid').val(),
            "groupid" : $("#id_groupid").val(),
        };
        console.log(data);
        BootstrapDialog.confirm(title,function(ret){
            if(ret){
                $.do_ajax("/user_power/dele_role_user",data);
            }
        })
    });

    // 批量删除账户
    $('#batch_dele_user').on("click",function(){
        var dele_uid_str = '';
        $.each($('.dele_uid_str'),function(i,item){
            if($(this).prop('checked')){
                dele_uid_str += $.trim($(this).parent().next().text()) + ',';
            }
        });

        if(dele_uid_str == ''){
            BootstrapDialog.alert("请选择删除的用户id!");
            return false;
        }

        dele_uid_str = dele_uid_str.substr(0,dele_uid_str.length-1);

        var data =  {
            "dele_uid_str" : dele_uid_str,
            "role_groupid" : $('#id_role_groupid').val(),
            "groupid" : $("#id_groupid").val(),
        };

        console.log(data);

        BootstrapDialog.confirm("你确认删除这些用户",function(ret){
            if(ret){
                $.do_ajax("/user_power/batch_dele_user",data);
            }
        })
    })

    var treeObj = $.fn.zTree.getZTreeObj('treeDemo');
    var allNodes = {};
    var showPart = 1; //默认显示部分根节点
    $("#id_show_power").on("click",function(){ // 显示有权限部分
        var allNodes = treeObj.getNodesByFilter(function (node) { return ( node.level == 0)});
        //console.log(allNodes);
        if(showPart == 1){
            showPart = 2;
            $(this).text('显示所有根节点');
            for (var i = 0; i < allNodes.length; i ++) {
                var obj = $("#treeDemo_"+allNodes[i].id+"_check");
                var className = obj.attr('class');
                if( className.indexOf("checkbox_true_full") < 0 && className.indexOf("checkbox_false_part") < 0){
                    treeObj.hideNode(allNodes[i]);
                }
            }
        }else{
            showPart = 1;
            $(this).text('显示有权限根节点');
            for (var i = 0; i < allNodes.length; i ++) {
                treeObj.showNode(allNodes[i]);
            } 
        }
    });

    var showWhole = 1;
    $("#id_show_all_power").on("click",function(){ // 显示所有权限
        //var nodes = treeObj.getNodesByParam("isHidden", true);
        if (showWhole == 1) {
            showWhole = 2;
            $(this).text('隐藏所有节点');
            treeObj.expandAll(true);
        } else {
            showWhole = 1;
            $(this).text('显示所有节点');
            treeObj.expandAll(false);

        }
    });

    $(".id_edit_power_group").on("click",function(){

        var edit_type = $(this).attr("edit");

        var id_edit_role_groupid =$("<select/>");
        Enum_map.append_option_list("account_role",id_edit_role_groupid,true);
        id_edit_role_groupid.val($("#id_role_groupid").val());

        var id_add_power =$("<input/>");

        var is_copy_power = $("<select >");
        Enum_map.append_option_list("boolean",is_copy_power,true,[1,0]);

        var id_copy_role_groupid = $("<select onchange='get_copy_group(this.options[this.options.selectedIndex].value)'/>");
        Enum_map.append_option_list("account_role",id_copy_role_groupid,true);

        var id_copy_power =$("<select id='copy_power_group' style='margin-left:10px'/>");
        var $group = $.trim($("#role_"+id_copy_role_groupid.val()).clone().html());
        id_copy_power.html($group);

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
            // ['是否复制权限',is_copy_power],
            // ['所要复制权限',[id_copy_role_groupid,id_copy_power]],
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
                    // 'is_copy_power':is_copy_power.val(),
                    // 'copy_groupid':id_copy_power.val()
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
                    window.location.href = "/user_manage_new/power_group_edit_new";
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

        $.do_ajax("/user_power/set_group_power", {
            "groupid" : $('#id_groupid').val(),
            "role_groupid" : $("#id_role_groupid").val(),
            "power_list_str" : power_list
        });

    });


    $("#id_power_back").on("click", function () {
        $.do_ajax("/user_manage_new/power_back",{});
    });

    $("#id_power_back_list").on("click", function () {
        window.location.href="/user_manage_new/power_back_list";
    });

    $('.opt-change').set_input_change_event(load_data);

});
function get_search_group(val){
    //alert(val);
    var $group = $.trim($("#role_"+val).clone().html());
    $("#id_groupid").html($group);
}

function get_role_group(val){
    //alert(val);
    var $group = $.trim($("#role_"+val).clone().html());
    $("#power_group").html($group);
}

function get_copy_group(val){
    var $group = $.trim($("#role_"+val).clone().html());
    $("#copy_power_group").html($group);
}
