/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-refund_list.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type         :	$('#id_date_type').val(),
            opt_date_type     :	$('#id_opt_date_type').val(),
            start_time        :	$('#id_start_time').val(),
            end_time          :	$('#id_end_time').val(),
            refund_type       : $("#id_refund_type").val(),
            userid            : $("#id_userid").val(),
            is_test_user      : $("#id_is_test_user").val(),
		        qc_flag:	$('#id_qc_flag').val(),
            seller_groupid_ex :	$('#id_seller_groupid_ex').val()
        });
    }


    $('.opt-change').set_input_change_event(load_data);
    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);

    $("#id_seller_groupid_ex").init_seller_groupid_ex(g_adminid_right);

    Enum_map.append_option_list( "test_user", $("#id_is_test_user"));
    $("#id_is_test_user").val(g_args.is_test_user);
    $("#id_userid").val(g_args.userid);
    $("#id_refund_type").val(g_args.refund_type);
	$('#id_qc_flag').val(g_args.qc_flag);

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

    $.admin_select_user( $("#id_userid"), "student", load_data);

    $.each($(".opt-change-state"),function(){
        var refund_status=$(this).parent().data("refund_status");
        console.log(refund_status);
        if(refund_status==1){
            $(this).hide();
        }
    });

    $(".opt-change-state").on("click",function(){
        var orderid     = $(this).get_opt_data("orderid");
        var contractid  = $(this).get_opt_data("contractid");
        var apply_time  = $(this).get_opt_data("apply_time");
        var user_nick   = $(this).get_opt_data("user_nick");
        var phone       = $(this).get_opt_data("phone");
        var real_refund = $(this).get_opt_data("real_refund");
       
        var arr=[
            [ "订单id", orderid ],
            [ "合同id", contractid],
            [ "学员姓名", user_nick+"/"+phone ],
            [ "实退金额", real_refund+'元' ],
        ];

        $.show_key_value_table("确认已退费",arr,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/set_order_refund",{
                    "orderid":orderid
                },function(result){
                    if(result.ret==0){
                        load_data();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        });
    });


    $(".opt-flow-node-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
       // alert(opt_data.flowid);
         $.flow_show_all_info( opt_data.flowid);
    });


    $(".opt-cancel-refund").on("click",function(){
        var data=$(this).get_opt_data();
        BootstrapDialog.confirm(
            "要取消退费?!",
            function(val) {
                if (val){
                    $.do_ajax("/user_manage_new/cancel_refund",{
                        "apply_time" : data.apply_time,
                        "orderid"    : data.orderid,
                    },function(result){
                        if(result.ret==0){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    });

                }
            }
        );
    });

    $("#id_add_refund").on("click",function(){
        $.admin_select_user($(this),"student",function(userid){
            select_order_list_ajax(userid);
        });
    });

    var select_order_list_ajax = function(id) {
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type"           : "select",
            "url"                : "/ss_deal/get_order_list_js",
            select_primary_field : "orderid",
            select_display       : "orderid",
            "args_ex"  : {
                userid : id
            },'field_list' : [{
                title  : "时间",
                render : function(val,item) {
                    return item.order_time;
                }
            },{
                title  : "下单人",
                render : function(val,item) {
                    return item.sys_operator;
                }
            },{
                title  : "学生",
                render : function(val,item) {
                    return item.student_nick;
                }
            },{
                title  : "类型",
                render : function(val,item) {
                    return item.contract_type_str;
                }
            },{
                title  : "课时数",
                render : function(val,item) {
                    return item.lesson_total * item.default_lesson_count/100;
                }
            },{
                title  : "剩余课时数",
                render : function(val,item) {
                    return item.lesson_left/100;
                }

            },{
                title  : "发票编号",
                render : function(val,item) {
                    return item.invoice;
                }
            }],
            filter_list  : [],
            "auto_close" : true,
            "onChange"   : function(orderid,item){
                if (!item.invoice) {
                    add_refund_contract( orderid );
                }else{
                    BootstrapDialog.confirm("该合同有发票,家长寄回了吗?!,没有不能申请退费!",
                    function(val){
                        if (val){
                            add_refund_contract( orderid );
                        }
                    });
                }
            },
            "onLoadData" : null,
        });
    };

    var add_refund_contract = function(orderid){
        var $id_stu_info      = $("<div/>");
        var $id_orderid       = $("<div/>");
        var $id_lesson_total  = $("<div/>");
        var $id_lesson_left   = $("<div/>");
        var $id_price         = $("<div/>");
        var $id_per_price     = $("<div/>");
        var $id_should_refund = $("<input/>");
        var $id_real_refund   = $("<input/>");
        var $id_pay_account   = $("<input/>");
        var $id_pay_account_admin   = $("<input/>");
        var $id_refund_info   = $("<textarea/>");
        var $id_save_info     = $("<textarea/>");
        var $id_button        = $("<button id=\"id_upload_rar\" class=\"btn btn-primary\" >上传记录文件</button>");
        var $id_has_receipt   = $("<select/>");
        var lesson_unassigned = 0;
        var receipt_html      = "<option value=\"0\">没有</option><option value=\"1\">有</option>";
        $id_has_receipt.append(receipt_html);

        $.do_ajax("/user_manage_new/get_unassigned_lesson_count",{
            "orderid" : orderid,
        },function(result){
            lesson_unassigned = result.lesson_unassigned;
        });

        console.log(lesson_unassigned);
        $.do_ajax("/user_manage_new/get_order_info",{
            "orderid" : orderid
        },function(result){
            var data = result.data;
            console.log("should_refund:"+data.should_refund+"order_left:"+data.order_left+"lesson_unassigned:"+lesson_unassigned);
            var arr = [
                ["学生", $id_stu_info] ,
                ["合同id", $id_orderid] ,
                ["购买课时", $id_lesson_total] ,
                ["剩余课时", $id_lesson_left] ,
                ["实付金额", $id_price] ,
                ["课时单价", $id_per_price] ,
                ["应退课时", $id_should_refund] ,
                ["退费金额", $id_real_refund] ,
                ["支付帐号", $id_pay_account] ,
                ["支付帐号持有人", $id_pay_account_admin] ,
                ["上传附件", $id_button] ,
                ["退费原因", $id_refund_info] ,
                ["挽单结果", $id_save_info] ,
            ];

            $id_stu_info.html(data.realname+"/"+data.phone);
            $id_orderid.html(data.orderid);
            $id_lesson_total.html(data.lesson_total);
            $id_lesson_left.html(data.lesson_left);
            $id_price.html(data.price);
            $id_per_price.html(data.per_price);

            var should_refund = 0;
            var real_refund   = 0;
            $.show_key_value_table("合同退费",arr,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    should_refund   = $id_should_refund.val()*100;
                    real_refund     = $id_real_refund.val()*100;
                    pay_account     = $id_pay_account.val();
                    pay_account_admin     = $id_pay_account_admin.val();
                    var refund_info = $id_refund_info.val();
                    if(refund_info==''){
                        BootstrapDialog.alert("请填写退费原因!");
                        return false;
                    }

                    $.do_ajax("/user_manage/set_refund_order", {
                        "userid"            : data.userid,
                        "orderid"           : data.orderid,
                        "contractid"        : data.contractid,
                        "contract_type"     : data.contract_type,
                        "lesson_total"      : data.lesson_total,
                        "order_left"        : data.lesson_left,
                        "should_refund"     : should_refund,
                        "real_refund"       : real_refund,
                        "pay_account"       : pay_account,
                        "pay_account_admin" : pay_account_admin,
                        "price"             : data.price,
                        "refund_info"       : refund_info,
                        "save_info"         : $id_save_info.val(),
                        "file_url"          : $("#id_upload_rar").val(),
                        "lesson_unassigned" : lesson_unassigned,
                        "competition_flag"  : data.competiton_flag,
                    },function(result){
                        if(result.ret==0){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    });
                }
            },function(){
                $.custom_upload_file("id_upload_rar",false,setComplete,$("#id_upload_rar"),["rar","zip"],false);
                $id_should_refund.on("change",function(){
                    should_refund = $(this).val();
                    real_refund   = data.price-data.per_price*(data.lesson_total-should_refund);
                    if(should_refund>data.order_left){
                        BootstrapDialog.alert("所退课时不足!");
                        $id_should_refund.val(0);
                        $id_real_refund.val(0);
                    }else{
                        if(should_refund==data.lesson_total){
                            real_refund=data.price;
                        }
                        $id_real_refund.val(real_refund);
                    }
                });

                $id_real_refund.on("change",function(){
                    should_refund=$id_should_refund.val();
                    real_refund=$(this).val();
                    if(real_refund>data.per_price*should_refund){
                        BootstrapDialog.alert("所退金额错误!");
                        $id_should_refund.val(0);
                        $id_real_refund.val(0);
                    }
                });
            });
        });
    }

    var setComplete = function(up,info,file,obj) {
        var res = $.parseJSON(info);
        var url = res.key;
        if (res.url) {
            BootstrapDialog.alert("地址解析出错！请刷新重试！");
        } else {
            obj.val(url);
            BootstrapDialog.alert("上传完成！");
        }
    };

    $(".opt-file_url").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if (opt_data.file_url) {
            $.do_ajax("/common_new/get_qiniu_download",{
                "file_url" : opt_data.file_url
            },function(resp){
                $.wopen(resp.url, false);
            });
        }else{
            alert("附件不存在!");
        }
    });


    $(".opt-desc").on("click",function(){
        var opt_data=$(this).get_opt_data();
        console.log(opt_data.orderid);

        $.show_key_value_table("明细",[
            ["退费课时" , opt_data.should_refund],
            ["退费金额" , opt_data.real_refund ],
            ["发票" , opt_data.invoice ],
            ["退费原因" , opt_data.refund_info ],
            ["挽单情况" , opt_data.save_info ],
        ]);

    });


    //点击进入个人主页
    $('.opt-user').on('click',function(){
        var opt_data=$(this).get_opt_data();
         console.log(opt_data.orderid);
        console.log(opt_data.apply_time);
        $.wopen('/stu_manage?sid='+ opt_data.userid);
    });

    $(".opt-confirm").on("click",function(){

        var adminid = $("#adminid").attr('data-adminid');

        var allow_adminid = ['60','68','186','349','540','684','968','99','1024','1184'];

        var is_allow = $.inArray(adminid,allow_adminid);

        if (is_allow == -1) {
            alert('您没有修改权限!');
            load_data();
            return;
        }

        var opt_data = $(this).get_opt_data();
        var v = $(this).data("v");
        if(!v) {
            v="[]";
        }

        $.do_ajax("/user_manage/get_refund_info", {
            "orderid"    :  opt_data.orderid,
            "apply_time" :  opt_data.apply_time ,
        },function(result){
            var confirm_config = result.confirm_config;
            var confirm_list   = result.confirm_list;

            $(this).admin_select_dlg_edit({
                onAdd:function( call_func ) {
                    var  id_key1    = $("<select />");
                    var  id_key2    = $("<select />");
                    var  id_key3    = $("<select />");
                    var  id_key4    = $("<select />");
                    var  id_reason  = $("<input />");
                    var  id_score   = $("<input />");
                    var gen_option_list = function( map_list) {
                        var str="<option value='-1'> 全部 </option>";
                        $.each( map_list,function( key, item)  {
                            if (item.name.slice(-2) != '其他') {
                                str+="<option value="+key+">"+item.name+"</option>";
                            }
                        });

                        $.each( map_list,function( key, item)  {
                            if (item.name.slice(-2) == '其他') {
                                str+="<option value="+key+">"+item.name+"</option>";
                            }
                        });

                        return str;
                    };
                    id_key1.html(gen_option_list( confirm_config ) );


                    var reset_list =function(key1,key2,key3 ){
                        if (key2==-1 && key3== -1 ) { //1
                            id_key2.html( gen_option_list ( confirm_config[key1]["list"]  ));
                            id_key3.html("");
                            id_key4.html("");
                        } else if( key3== -1 ) {//2
                            id_key3.html(gen_option_list(confirm_config[key1]["list"][key2]["list"]));
                            id_key4.html("");
                        } else { //3
                            id_key4.html(gen_option_list(confirm_config[key1]["list"][key2]["list"][key3]["list"]));
                        }
                    }


                    id_key1.on("change",function(){
                        reset_list( id_key1.val(),  -1, -1 );
                    });

                    id_key2.on("change",function(){
                        reset_list( id_key1.val(),  id_key2.val(), -1 );

                        //当'部门'+'其他'时 实现代码
                        var id_key2_content = id_key2.find("option:selected").text();
                        if (id_key2_content.slice(-2) == '其他') {
                            id_key3.parent().append("<input id = 'id_key3_input' />");
                            id_key3.parent().find("select").remove();

                            id_key4.parent().append("<input id = 'id_key4_input' />");
                            id_key4.parent().find("select").remove();
                        }

                        $("#id_key3_input").on("blur",function(){
                            $('#id_key4_input').val($('#id_key3_input').val());
                        });

                    });

                    id_key3.on("change",function(){
                        reset_list( id_key1.val(),  id_key2.val(), id_key3.val() );
                    });

                    var arr=[
                        ["部门",         id_key1],
                        ["一级原因",     id_key2],
                        ["二级原因",     id_key3],
                        ["三级原因",     id_key4],
                        ["评分",         id_score],
                        ["原因分析",     id_reason],
                    ];

                    $.show_key_value_table("增加", arr, {
                        label: '确认',
                        cssClass: 'btn-warning',
                        action: function (dialog) {

                        // 当'部门'+'其他'时 实现代码

                            var key2_str =  id_key2.find("option:selected").text();
                            var key3     = -1;
                            var key4     = -1;
                            var key3_str = '';
                            var key4_str = '';

                            if (key2_str.slice(-2) == '其他') {
                                key3_str = $("#id_key3_input").val();
                                key4_str = $("#id_key3_input").val();
                            } else {
                                key3       = id_key3.val();
                                key3_str   = id_key3.find("option:selected").text();
                                key4       = id_key4.val();
                                key4_str   = id_key4.find("option:selected").text();
                            }

                            call_func({
                                'key1'       : id_key1.val(),
                                'key1_str'   : id_key1.find("option:selected").text(),
                                'key2'       : id_key2.val(),
                                'key2_str'   : key2_str,
                                'key3'       : key3,
                                'key3_str'   : key3_str,
                                'key4'       : key4,
                                'key4_str'   : key4_str,
                                'reason'     : id_reason.val(),
                                'score'      : id_score.val()

                            });

                            dialog.close();
                        }
                    });

                },
                sort_func : function(a,b){
                    var a_time=a["week"]*10000000+a["start_time"];
                    var b_time=b["week"]*10000000+b["start_time"];
                    if (a_time==b_time ) {
                        return 0;
                    }else{
                        if (a_time>b_time) return 1;
                        else return -1;
                    }
                }, 'field_list' :[
                    {
                        title:"部门",
                        render:function(val,item) {
                            return item['key1_str'];
                        }
                    },{
                        title:"一级原因",
                        render:function(val,item) {
                            return item['key2_str'];
                        }
                    },{
                        title:"二级原因",
                        render:function(val,item) {
                            return item['key3_str'];
                        }
                    },{
                        title:"三级原因",
                        render:function(val,item) {
                            return item['key4_str'];
                        }
                    },{
                        title:"原因分析",
                        render:function(val,item) {
                            return item['reason'];
                        }
                    },{
                        title:"得分",
                        render:function(val,item) {
                            return item['score'];
                        }
                    }

                ] ,
                data_list: confirm_list,

                onChange:function( data_list, dialog)  {
                    $.do_ajax("/user_manage/set_refund_analysis_info",{
                        "orderid"      : opt_data.orderid,
                        "apply_time"   : opt_data.apply_time,
                        "confirm_info" : JSON.stringify(data_list)
                    });
                }
            });
        });
    }) ;


    $('.opt-complaint').on("click", function (g_adminid_right) {
        var opt_data=$(this).get_opt_data();

        var $main_type_name = $("<select/>");
        var $main_group_name = $("<select/>");
        var $group_name = $("<select/>");
        var $account = $("<select/>");
        var $complaint_info = $("<textarea/>");
        var $teacher_adminid = $("<input/>");
        var $punish_style = $("<select />");
        var me = $(this);
        var key_list = me.val();


        $main_type_name.html("<option value=\"\" >[全部]</option><option data-type=\"5\" value=\"全职老师\" >全职老师</option><option value=\"助教\" >助教</option><option value=\"销售\"  >销售</option><option value=\"教务\" >教务</option>");

        $punish_style.html("<option value=\"1\" >A类</option><option value=\"2\"  >B类</option><option value=\"3\" >C类</option>");

        var clean_select = function ($select) {
            $select.html("<option value=\"\">[全部]</option>");
        };


        key_list = key_list.split(",");
        if(g_adminid_right != "" && g_adminid_right != null){
            key_list = g_adminid_right;
            // console.log(g_adminid_right);
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
            if ($main_type_name.val() == "全职老师") {
                $main_group_name.parent().parent().css('display','none');
                $group_name.parent().parent().css('display','none');
                $account.parent().parent().css('display','none');

                $teacher_adminid.parent().parent().css('display','table-row');
                $punish_style.parent().parent().css('display','table-row');
            } else {
                $main_group_name.parent().parent().css('display','table-row');
                $group_name.parent().parent().css('display','table-row');
                $account.parent().parent().css('display','table-row');
                $teacher_adminid.parent().parent().css('display','none');
                $punish_style.parent().parent().css('display','none');
                $punish_style.val('0');
            }

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
            ["老师", $teacher_adminid],
            ["处罚类型",$punish_style],
            ["退费投诉原因", $complaint_info],
        ];

        $.show_key_value_table("退费订单", arr, {
            label: '确认',
            cssClass: 'btn-warning',
            action: function (dialog) {

                if($account.val()){
                    $account_id  = '';
                    $account_str = $account.val();
                } else if($teacher_adminid.val()) {
                    $account_id  = $teacher_adminid.val();
                    $account_str = $teacher_adminid.next().val();
                }

                $.do_ajax("/ss_deal/add_refund_complaint",{
                    'complained_adminid'  : $account_id,
                    'complaint_info'      : $complaint_info.val(),
                    'complained_adminid_type' : $main_type_name.find("option:selected").attr('data-type'),
                    'complained_adminid_nick' : $account_str,
                    'punish_style' : $punish_style.val(),
                    'order_id' : opt_data.orderid,
                    'apply_time' : opt_data.apply_time

                });
            }
        },function(){

            $teacher_adminid.parent().parent().css('display','none');
            $punish_style.parent().parent().css('display','none');
            $.admin_select_user($teacher_adminid, "teacher");
        });
    });

    $(".opt-set-money").on("click",function(){
	      var data            = $(this).get_opt_data();
        var id_refund_money = $("<input/>");
        var arr = [
            ["退费金额",id_refund_money],
        ];

        id_refund_money.val(data.real_refund);

        $.show_key_value_table("修改退费金额",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/user_manage/set_refund_money",{
                    "orderid"     : data.orderid,
                    "apply_time"  : data.apply_time,
                    "real_refund" : id_refund_money.val()
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })

            }
        });
    });

    if(g_account=="wenbin" || g_account=="sherry"){
        download_show();
    }
});
