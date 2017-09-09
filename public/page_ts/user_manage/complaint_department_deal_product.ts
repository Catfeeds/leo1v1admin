/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-complaint_department_deal_product.d.ts" />



$(function(){

    function load_data(){
        $.reload_self_page ( {
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val()
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







    $(".opt-assign_remark").on("click",function(){

        var data            = $(this).get_opt_data();
        var complaint_id    = data.complaint_id;
        var html_node    = $.obj_copy_node("#id_assign_log");

        BootstrapDialog.show({
            title: "分配列表",
            message: html_node,
            closable: true
        });

        $.ajax({
            type: "post",
            url: "/ss_deal/get_assign_log",
            dataType: "json",
            data: {
                'complaint_id': complaint_id,
            },
            success: function (result) {
                if (result['ret'] == 0) {
                    var data = result['data'];

                    var html_str = "";
                    $.each(data, function (i, item) {
                        var cls = "success";

                        html_str += "<tr class=\"" + cls + "\" > <td>" + item.ass_date + "<td>" + item.assign_str + "<td>" + item.accept_str + "<td>" + item.assign_remarks+ "</tr>";
                    });

                    html_node.find(".data-body").html(html_str);

                }
            }
        });

    });


    $('.opt-deal').on("click",function(){
        var data               = $(this).get_opt_data();
        var id_suggest_remark  = $("<textarea>");
        var id_deal_info       = $("<textarea>");
        var arr             = [
            ["处理方案",id_deal_info],
            ["投诉人建议",id_suggest_remark],
        ];
        var enable_video = 0;
        $.show_key_value_table("投诉处理",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                if (id_deal_info.val() == '') {
                    alert('处理方案不能为空!');
                    load_data();
                    return ;
                }

                $.do_ajax("/ss_deal/deal_complaint",{
                    "deal_info"        : id_deal_info.val(),
                    "suggest_info"     : id_suggest_remark.val(),
                    "complaint_state"  : 1,
                    "complaint_id"     : data.complaint_id,
                    "account_type"     : data.account_type,
                },function(result){
                    BootstrapDialog.alert(result.info);
                    dialog.close();
                    load_data();
                });
            }
        });

    });


    $('.opt-reject').on("click",function(){
        var data =  $(this).get_opt_data();
        if(!confirm('确定是要驳回分配吗?')){
            load_data();
            return;
        }
        $.do_ajax("/ss_deal/reject_complaint",{
            "complaint_id"    : data.complaint_id,
            "assign_adminid"  : data.assign_adminid,
            "accept_adminid"  : data.accept_adminid,
        },function(result){
            // BootstrapDialog.alert(result.info);
            alert(result.info);
            load_data();
        });
    });

    $('.opt-del').on("click",function(){
        var data = $(this).get_opt_data();
        if(confirm("确定要删除此条投诉吗？")){
            $.do_ajax("/ss_deal/del_complaint", {
                'complaint_id':data.complaint_id,
            }, function (result) {
                if(result.ret==0){
                    load_data();
                }else{
                    BootstrapDialog.alert(result.info);
                }
            });
        }
    });



    $('.opt-complaint-all').on("click",function(){
        var data = $(this).get_opt_data();
        $.show_key_value_table("投诉明细",[
            ["投诉类型" , data.complaint_type_str],
            ["投诉人身份" , data.account_type_str ],
            ["投诉时间" , data.complaint_date ],
            ["投诉人姓名/电话" , data.user_nick +'/'+ data.phone ],
            ["被投诉人" , data.complained_adminid_nick ],
            ["处理人" , data.deal_admin_nick ],
            ["投诉处理时间" , data.deal_date ],
            ["投诉内容" , data.complaint_info ],
            ["处理方案" , data.deal_info ],
            ["投诉人建议" , data.suggest_info ],
            ["处理状态" , data.complaint_state_str ],
        ]);
    });

    $('.opt-assign').on("click", function (g_adminid_right) {
        var $main_type_name = $("<select/>");
        var $main_group_name = $("<select/>");
        var $group_name = $("<select/>");
        var $account = $("<select/>");
        var $complaint_info = $("<textarea/>");
        var me = $(this);
        var key_list = me.val();

        var data = $(this).get_opt_data();
        $main_type_name.html("<option value=\"\" >[全部]</option><option value=\"助教\" >助教</option><option value=\"销售\"  >销售</option><option value=\"教务\" >教务</option><option value=\"教研\" >教研</option><option value=\"薪资运营\">薪资运营</option>")
        var clean_select = function ($select) {
            $select.html("<option value=\"\">[全部]</option>");
        };

        key_list = key_list.split(",");
        if(g_adminid_right != "" && g_adminid_right != null){
            key_list = g_adminid_right;
        }
        //处理key
        $.do_ajax("/user_deal/seller_init_group_info", {
            "main_type_name": key_list[0],
            "main_group_name": key_list[1],
            "group_name": key_list[2]
        }, function (ret) {
            clean_select($main_group_name);
            clean_select($group_name);
            clean_select($account);
            clean_select($complaint_info);

            $.each(ret.key2_list, function () {
                var groupid = this.groupid;
                var group_name = this.group_name;
                $main_group_name.append("<option value=\"" + group_name + "\">" + group_name + "</option>");
            });

            $.each(ret.key3_list, function () {
                var groupid = this.groupid;
                var group_name = this.group_name;
                $group_name.append("<option value=\"" + group_name + "\">" + group_name + "</option>");
            });

            $.each(ret.key4_list, function () {
                var adminid = this.adminid;
                var account = this.account;
                $account.append("<option value=\"" + account + "\">" + account + "</option>");
            });
            //$main_type.val(key_list[0]);
            $main_group_name.val(key_list[1]);
            $group_name.val(key_list[2]);
            $account.val(key_list[3]);
            if(key_list[1] == "" || key_list[1] == null){
                set_select($main_group_name, $main_type_name.val(), "", "");
            }
            //set_select($main_groupid, $main_type.val(), "", "");

        });



        var set_select = function ($select, main_type_name, main_group_name, group_name) {
            $.do_ajax("/user_deal/seller_get_group_info", {
                "main_type_name": main_type_name,
                "main_group_name": main_group_name,
                "group_name": group_name
            }, function (ret) {
                var sel_v = $select.val();
                $select.html("");
                $select.append("<option value=\"\">[全部]</option>");
                if(group_name){
                    $.each(ret.list, function () {
                        var adminid = this.adminid;
                        var account = this.account;
                        $select.append("<option value=\"" + account + "\">" + account + "</option>");
                    });
                }else{
                    if(main_group_name){
                        $.each(ret.list, function () {
                            var groupid = this.groupid;
                            var group_name = this.group_name;
                            $select.append("<option value=\"" + group_name + "\">" + group_name + "</option>");
                        });
                    }else{
                        if(main_type_name){
                            $.each(ret.list, function () {
                                var groupid = this.groupid;
                                var group_name = this.group_name;
                                $select.append("<option value=\"" + group_name + "\">" + group_name + "</option>");
                            });
                        }
                    }
                }

            });

        };


        $main_type_name.on("change", function () {
            clean_select($main_group_name);
            clean_select($group_name);
            clean_select($account);

            if ($main_type_name.val()) {
                set_select($main_group_name, $main_type_name.val(), "", "");
            }
        });



        $main_group_name.on("change", function () {
            clean_select($group_name);
            clean_select($account);
            if ($main_group_name.val()) {
                set_select($group_name, $main_type_name.val(), $main_group_name.val(), "");
            }
        });
        $group_name.on("change", function () {
            clean_select($account);
            if ($group_name.val()) {
                set_select($account, $main_type_name.val(), $main_group_name.val(), $group_name.val());
            }
        });

        var arr = [
            ["分类", $main_type_name],
            ["主管", $main_group_name],
            ["小组", $group_name],
            ["成员", $account],
            ["分配备注", $complaint_info],
        ];

        $.show_key_value_table("分配处理人", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {
                $.do_ajax("/ss_deal/do_complaint_assign_department",{
                    "accept_adminid_nick" : $account.val(),
                    "ass_remark"          : $complaint_info.val(),
                    "complaint_id"        : data.complaint_id,
                });
            }
        },function(){

        });
    });


    $(".opt-telphone").on("click",function(){
        //
        var me=this;
        var opt_data= $(this).get_opt_data();
        var phone    = ""+ opt_data.phone;
        phone=phone.split("-")[0];

        try{
            window.navigate(
                "app:1234567@"+phone+"");
        } catch(e){

        };
        $.do_ajax_t("/ss_deal/call_ytx_phone", {
            "phone": opt_data.phone
        } );

    });

    $('.opt-complaint-img').on("click",function(){
        var opt_data = $(this).get_opt_data();
        var img_str  = opt_data.complaint_img_url;
        var html_node = "";
        var img_arr = img_str.split(',');

        $.each(img_arr, function (i, item) {
            var cls = "success";
            html_node += "<tr class=\"" + cls + "\" > " + "<td>" + item +"</tr>";
        });

        BootstrapDialog.show({
            title: "图片列表",
            message: html_node,
            closable: true
        });

    });




    $('.opt-change').set_input_change_event(load_data);


});
