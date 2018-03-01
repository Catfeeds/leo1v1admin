/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-contract_list.d.ts" />

function load_data(){
    $.reload_self_page({
		    order_by_str        : g_args.order_by_str,
        orderid             :	$('#id_orderid').val(),
        date_type           : $('#id_date_type').val(),
        opt_date_type       : $('#id_opt_date_type').val(),
        seller_groupid_ex   :	$('#id_seller_groupid_ex').val(),
        start_time          : $('#id_start_time').val(),
        end_time            : $('#id_end_time').val(),
        assistantid         :	$('#id_assistantid').val(),
        grade               : $('#id_grade').val(),
        subject             : $('#id_subject').val(),
        contract_type       : $("#id_contract_type").val(),
        order_activity_type : $("#id_order_activity_type").val(),
        contract_status     : $("#id_contract_status").val(),
        studentid           : $("#id_studentid").val(),
        test_user           : $("#id_test_user").val(),
        sys_operator        : $("#id_sys_operator").val(),
        stu_from_type       : $("#id_stu_from_type").val(),
        has_money           : $("#id_has_money").val(),
        account_role        : $("#id_account_role").val(),
        teacherid           : $('#id_teacherid').val(),
		    adminid             :	$('#id_adminid').val(),
        tmk_adminid         : $('#id_tmk_adminid').val(),
        origin_userid       : $('#id_origin_userid').val(),
        referral_adminid    :	$('#id_referral_adminid').val(),
        spec_flag           :	$('#id_spec_flag').val(),
        is_origin           :	$('#id_is_origin').val(),
    });
}

function isNumber( s ){
    var regu = "^[0-9]+$";
    var re = new RegExp(regu);
    if (s.search(re) != -1) {
        return true;
    } else {
        return false;
    }
}

$(function(){
	  $('#id_is_origin').val(g_args.is_origin);
    Enum_map.append_option_list( "contract_from_type", $("#id_stu_from_type"));
    Enum_map.append_option_list( "account_role", $("#id_account_role"));

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


	  $('#id_contract_type').val(g_args.contract_type);
	  $.enum_multi_select( $('#id_contract_type'), 'contract_type', function(){load_data();} )
	  $('#id_contract_status').val(g_args.contract_status);
	  $.enum_multi_select( $('#id_contract_status'), 'contract_status', function(){load_data();} )

	  $('#id_spec_flag').admin_set_select_field({
		    "enum_type"    : "boolean",
		    "field_name" : "spec_flag",
		    "select_value" : g_args.spec_flag,
		    "onChange"     : load_data,
		    "multi_select_flag"     : false ,
		    "th_input_id"  : "th_spec_flag",
		    "only_show_in_th_input"     : true,
        "show_title_flag" : true,
		    "btn_id_config"     : {},
	  });


	$('#id_test_user').admin_set_select_field({
		  "enum_type"    : "boolean",
      "field_name"  :"test_user",
		"select_value" : g_args.test_user,
		"onChange"     : load_data,
		"multi_select_flag"     : false ,
		"th_input_id"  : "th_test_user",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});


    //init  input data
    $('#id_orderid').val(g_args.orderid);
    $("#id_has_money").val(g_args.has_money);
    $("#id_order_activity_type").val(g_args.order_activity_type );
    $("#id_sys_operator").val(g_args.sys_operator);
    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
    $("#id_stu_from_type").val(g_args.stu_from_type);
    $('#id_assistantid').val(g_args.assistantid);
    $('#id_origin_userid').val(g_args.origin_userid);
    $("#id_account_role").val(g_args.account_role);
    $('#id_tmk_adminid').val(g_args.tmk_adminid);
    $('#id_teacherid').val(g_args.teacherid);

    $("#id_seller_groupid_ex").init_seller_groupid_ex();
    $('#id_referral_adminid').val(g_args.referral_adminid);

	  $('#id_adminid').admin_select_user_new({
		    "user_type"    : "account",
		    "select_value" : g_args.adminid,
		    "onChange"     : load_data,
		    "th_input_id"  : "th_adminid",
		    "only_show_in_th_input"     :  true,
		    "can_select_all_flag"     : true
	  });


	  $('#id_studentid').admin_select_user_new({
		    "user_type"    : "student",
		    "select_value" : g_args.studentid,
		    "onChange"     : load_data,
		    "th_input_id"  : "th_studentid",
		    "can_select_all_flag"     : true,
        "only_show_in_th_input" :false,

	  });

	  $('#id_contract_type').admin_set_select_field({
		    "enum_type"    : "contract_type",
		    "select_value" : g_args.contract_type,
		    "onChange"     : load_data,
		    "th_input_id"  : "th_contract_type",
        "only_show_in_th_input" :false,
		    "btn_id_config"     : {}
	  });
	  $('#id_contract_status').admin_set_select_field({
		    "enum_type"    : "contract_status",
		    "select_value" : g_args.contract_status,
		    "onChange"     : load_data,
		    "th_input_id"  : "th_contract_status",
		    "btn_id_config"     : {}
	  });


	  $('#id_grade').admin_set_select_field({
		    "enum_type"    : "grade",
		    "select_value" : g_args.grade,
		    "onChange"     : load_data,
		    "th_input_id"  : "th_grade",
		    "btn_id_config"     : {}
	  });
	  $('#id_subject').admin_set_select_field({
		    "enum_type"    : "subject",
		    "select_value" : g_args.subject,
		    "onChange"     : load_data,
		    "th_input_id"  : "th_subject",
		    "btn_id_config"     : {}
	  });

    $.admin_select_user( $("#id_teacherid"), "teacher", load_data );

    var show_select_lesson_account_dlg=function(userid) {
        var nick_div=$("<div/>");

        $.do_ajax_get_nick("student",userid,function(id,nick){
            nick_div.text(nick);
        });

        var $subject =$("<select/>");
        Enum_map.append_option_list( "subject", $subject, true );
        //do_ajax( "/stu_manage/lesson_account_add", {

        var $add_money=$("<input/>");
        var $add_lesson_count=$("<input/>");
        var arr=[
            [ "userid", userid  ]
            ,[ "姓名", nick_div ]
            ,[ "科目",  $subject]
            ,[ "金额",  $add_money ]
            ,[ "课时",  $add_lesson_count]
        ];

        $.show_key_value_table("续费课时包", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                //lesson_account_continue_add
                $.do_ajax("/user_deal/add_contract_for_lesson_account",{
                    "studentid" :userid,
                    "course_name" :$subject.find("option:selected").text(),
                    "money" : $add_money.val(),
                    "lesson_count" : $add_lesson_count.val()
                });
            }
        });

    };

    $("#id_add_lesson_account").on("click",function(){
        //
        $.admin_select_user( $(this), "student",
                             function( id ){
                                 show_select_lesson_account_dlg(id);
                             },true);
    });


    //display
    $.each($(".opt-next"),function(i,item) {
        var from_type     = $(item).get_opt_data("from_type");
        var contract_type = $(item).get_opt_data("contract_type");
        if (from_type==0 &&  contract_type != 3001 ) { //
            $(item).hide();
        }
    });

    $(".opt-next").on("click",function(){
        if ($(this).get_opt_data("from_type")==0) {
            //small_class/index?courseid=1001
            window.open ( "/small_class/index?&courseid="+$(this).get_opt_data("config_courseid") ,"_blank");
        }else{
            window.open ( "/stu_manage/lesson_account/?sid="+$(this).get_opt_data("userid")+"&lesson_account_id="+$(this).get_opt_data("config_lesson_account_id") ,"_blank");
        }

    });






    //点击进入个人主页
    $('.opt-user').on('click',function(){
        var userid = $(this).parent().data("userid");
        var nick   = $(this).parent().data("stu_nick");
        var sys_operator   = $(this).parent().data("sys_operator");
        //$(this).attr('href','/stu_manage?sid = '+userid+'&nick='+nick+"&"  );
        if(g_args.account_role_self==1){
            if(g_args.ass_master_flag==1){
                $.wopen('/user_manage/ass_archive?userid=' + userid);
            }else{
                $.wopen('/user_manage/ass_archive_ass?userid=' + userid);
            }
        }else if(g_args.account_role_self==2){
            if(g_args.acc != sys_operator){
                alert("请查看您下单的学生信息!");
            }else{
                window.open(
                    '/stu_manage?sid='+ userid +"&return_url="+ encodeURIComponent(window.location.href)
                );
            }
        }else{
            window.open(
                '/stu_manage?sid='+ userid +"&return_url="+ encodeURIComponent(window.location.href)
            );
        }

    });


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

    $.admin_select_user(
        $('#id_referral_adminid'),
        "admin", load_data ,false, {
            " main_type": 2,
            select_btn_config: [
                {
                "label": "[已分配]",
                "value": -2
            }, {
                "label": "[未分配]",
                "value": 0
            }]
        }
    );




    $.admin_select_user(
        $('#id_assistantid'),
        "assistant", load_data ,false, {
            " main_type": 2,
            select_btn_config: [
                {
                "label": "[已分配]",
                "value": -2
            }, {
                "label": "[未分配]",
                "value": 0
            }]
        }
    );


    $.admin_select_user(
        $('#id_tmk_adminid'),
        "admin", load_data ,false, {
            " main_type": 2,
            select_btn_config: [
                {
                "label": "[已分配]",
                "value": -2
            }, {
                "label": "[未分配]",
                "value": 0
            }]
        }
    );

    $.admin_select_user(
        $('#id_origin_userid'),
        "student", load_data ,false, {
            select_btn_config: [
                {
                "label": "[已分配]",
                "value": -2
            }, {
                "label": "[未分配]",
                "value": 0
            }]
        }
    );


    $(".opt-change").set_input_change_event(load_data);
    $(".opt-change-cash").on("click", function(){
        alert($.trim(this));

        if(g_power_list[$.trim(this)] == 16 ){
            $(this).hide();
        }else{
            $(this).show();
        }
        var contractid = $(this).parent().data('contractid');
        var orderid    = $(this).parent().data('orderid');
        var userid     = $(this).parent().data('userid');
        var html_node  = $("<span> <input type=input style=\"width:80px\"></input>元</span> ");

        var dlg = BootstrapDialog.show({
            title    : '更改金额',
            message  : html_node,
            closable : true,
            buttons  : [{
                label: '返回',

                action: function(dialog) {
                    dialog.close();
                }
            },{
                label: '提交',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.ajax({
                        type     :"post",
                        url      :"/user_manage/set_contract_money",
                        dataType :"json",
                        data     :{
                            'orderid': orderid,
                            'userid':userid,
                            'price': html_node.find("input").val()
                        }, success  : function(result){
                            if(result.ret == -1){
                                alert(result.info);
                            }else{
                                window.location.reload();
                            }
                        }
                    });
                }
            }]
        });


        dlg.getModalDialog().css("width","250px");
        dlg.getModalDialog().css("min-width","250px");

    });






    $(".opt-set-origin").on("click",function(){
        var opt_data=$(this).get_opt_data();

        /*
          $.do_ajax("/user_manage/set_stu_origin",{
          orderid : orderid,
          origin  : val
          });
        */

        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "select", // or "list"
            "url"          : "/ss_deal/get_require_list_js",
            select_primary_field : "require_id",
            select_display       : "require_id",

            //其他参数
            "args_ex" : {
                userid:opt_data.userid
            },
            //字段列表
            'field_list' :[
                {
                title:"渠道",
                render:function(val,item) {
                    return item.origin;
                }
            },{
                title:"科目",
                render:function(val,item) {
                    return item.subject_str;
                }
            },{

                title:"时间",
                render:function(val,item) {
                    return item.require_time ;
                }

            },{
                title:"教务是否接受",
                //width :50,
                render:function(val,item) {
                    return $(item.accept_flag_str );
                }
            },{
                title:"课程是否成功",
                render:function(val,item) {
                    return $(item.success_flag_str);
                }

            },{
                title:"老师",
                render:function(val,item) {
                    return item.teacher_nick;
                }
            },{
                title:"上课时间",
                render:function(val,item) {
                    return item.lesson_start;
                }

            }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : function(require_id,row_data){
                if (require_id>0) {
                    var lessonid=row_data.lessonid;
                    var origin= row_data.origin;
                    $.do_ajax("/user_deal/order_set_test_lesson_info", {
                        "orderid"   : opt_data.orderid,
                        "from_test_lesson_id"   : lessonid ,
                        "origin"   : origin,
                    });

                }
            },
            //加载数据后，其它的设置
            "onLoadData"       : null,
        });
    });


    $(" .opt-is-not-spec-flag ").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if (!opt_data.flowid ) {
            alert("不是特殊申请的合同");
            return;
        }

        var $promotion_spec_is_not_spec_flag=$("<select/>");
        Enum_map.append_option_list( "boolean", $promotion_spec_is_not_spec_flag,true);
        $promotion_spec_is_not_spec_flag.val(opt_data.promotion_spec_is_not_spec_flag);
        var arr=[
            [ "是否强制设置为正常订单(非特殊申请)", $promotion_spec_is_not_spec_flag ]
        ];

        $.show_key_value_table("是否强制设置为正常订单(非特殊申请)", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax('/ajax_deal/order_set_promotion_spec_is_not_spec_flag',{
                    "orderid" : opt_data.orderid,
                    "promotion_spec_is_not_spec_flag" : $promotion_spec_is_not_spec_flag.val(),
                } );

            }
        });

    });

    $(".opt-change-money").on("click", function(){
        var contractid = $(this).parent().data('contractid');
        var orderid    = $(this).parent().data('orderid');
        var userid     = $(this).parent().data('userid');
        var $price           = $("<input/> ");
        var $discount_price  = $("<input/> ");
        var $discount_reason = $("<textarea/> ");

        $.do_ajax("/user_manage/get_contract_money",{
            "orderid":orderid
        },function(result){
            console.log(result);
            var data = result.data;
            $price.val(data.price/100);
            if(data.discount_price && data.discount_price!=0){
                $discount_price.val(data.discount_price/100);
                $discount_reason.val(data.discount_reason);
            }
        });

        var arr=[
            ["实付价格",$price],
            ["原始价格",$discount_price],
            ["优惠原因",$discount_reason],
        ];

        $.show_key_value_table("更改金额", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                if(isNaN($price.val())){
                    BootstrapDialog.alert("实付金额只能为数字");
                    return ;
                }else if(isNaN($discount_price.val())){
                    BootstrapDialog.alert("原始价格只能为数字");
                    return ;
                }

                $.do_ajax("/user_manage/set_contract_money", {
                    "orderid"         : orderid,
                    "price"           : parseFloat($price.val())*100,
                    "discount_price"  : parseFloat($discount_price.val())*100,
                    "discount_reason" : $discount_reason.val()
                },function(result){
                    if(result.ret==-1){
                        alert(result.info);
                    }else{
                        window.location.reload();
                    }
                });
            }
        });

    });

    $(".opt-del").on("click", function(){
        var orderid = $(this).parent().data('orderid');
        console.log(orderid);
        var userid  = $(this).parent().data('userid');

        var name  = $(this).closest("tr").find(".stu_nick").text();
        var price = $(this).closest("tr").find(".price").text();

        var dlg=BootstrapDialog.show({
            title: '删除合同',
            message : "删除["+name+"]的合同 ?! 金额："+price+"元",
            closable: true,
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            },{
                label: '提交',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.ajax({
                        type     :"post",
                        url      :"/user_manage/del_contract",
                        dataType :"json",
                        data     :{
                            'orderid': orderid,
                            'userid':userid
                        }, success  : function(result){
                            if(result.ret == -1){
                                BootstrapDialog.alert(result.info);
                            }else{
                                window.location.reload();
                            }
                        }
                    });
                }
            }]
        });
        dlg.getModalDialog().css("width","250px");
        dlg.getModalDialog().css("min-width","250px");
    });

    $(".opt-change-state").on("click", function(){
        var contractid = $(this).parent().data('contractid');
        var status     = $(this).parent().data('contract_status');
        var orderid    = $(this).parent().data('orderid');
        var money      = $(this).parent().data('price');
        var userid     = $(this).parent().data('userid');
        var html_node  = $.dlg_need_html_by_id( "id_dlg_change_state");
        html_node.find(".orderid").html(orderid);
        html_node.find(".money").html(money);
        html_node.find(".contract_status").html(status);
        BootstrapDialog.show({
            title: '更改付款状态',
            message : html_node,
            closable: true,
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            },{
                label: '提交',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.ajax({
                        type     :"post",
                        url      :"/user_manage/set_contract_payed",
                        dataType :"json",
                        data     :{
                            'orderid': orderid,
                            "channelid": html_node.find("#id_pay_channel").val(),
                            'userid':userid,
                            'pay_number': html_node.find("#id_pay_number").val()
                        }, success  : function(result){
                            alert("金额确认为\""+money+"元\"么？");
                            if(result.ret !=0){
                                alert(result.info);
                            }else{
                                window.location.reload();
                            }
                        }
                    });
                }
            }]
        });
    });

    $("#id_set_payed").on("click",function(){
        $.ajax({
            type     :"post",
            url      :"/user_manage/set_contract_payed",
            dataType :"json",
            data     :{'orderid': $("#id_set_payed").data('orderid'), "channelid": $("#id_pay_channel").val(), 'userid':$("#id_set_payed").data('userid'),'pay_number': $("#id_pay_number").val()},
            success  : function(result){
                if(result.ret == -1){
                    alert(result.info);
                }else{
                    window.location.reload();
                }
            }
        });
    });

    //+合同
    $('#id_query_user').on("click",function(){
        var html_node = $.dlg_need_html_by_id( "id_dlg_query_user");
        var userid    = 0;
        var grade     = 0;
        var stu_nick     ;
        var parent_nick  ;
        var parent_phone ;
        var address      ;

        html_node.find("#id_query_phone").val();
        html_node.find("#id_query_phone").on("keydown",function(e){
            if(e.which==13 ){
                html_node.find("#id_query_student").click();
            }
        });

        html_node.find("#id_query_student").on("click",function(){
            var phone = $.trim( html_node.find("#id_query_phone").val());
            if(phone != ""){
                $.ajax({
                    type     : "post",
                    url      : "/user_manage/contract_get_student_info",
                    dataType : "json",
                    data     : {'phone':phone},
                    success  : function(result){
                        if(  result.ret !== 0){
                            html_node.find("#id_user_acc").text("该账号不存在");
                            userid=0;
                            grade=0;
                        }else{
                            html_node.find("#id_user_acc").text(result.data.phone);
                            html_node.find("#id_user_grade").text(result.data.grade);
                            html_node.find("#id_user_region").text(result.data.region);
                            html_node.find("#id_user_textbook").text(result.data.textbook);

                            stu_nick     = result.data.stu_nick;
                            parent_nick  = result.data.parent_nick;
                            parent_phone = result.data.parent_phone;
                            address      = result.data.address;

                            userid=result.data.userid;
                            grade = result.data.grade_num;
                        }
                    }
                });
            }else{
                alert("请输入电话号码");
            }
        });

        BootstrapDialog.show({
            title: '新增合同-查询用户',
            message : html_node,
            closable: true,
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            },{
                label: '加合同',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    if(userid == 0){
                        alert("请先确认用户是否存在");
                    }else{
                        dialog.close();
                        show_add_contract(userid, grade, stu_nick, parent_nick  , parent_phone, address);
                    }
                }
            }]
        });
    });

    var show_add_contract=function(  userid, grade ,stu_nick, parent_nick  , parent_phone, address){
        //id_order_origin
        var html_node=$.dlg_need_html_by_id( "id_dlg_add_contract");
        html_node.find("#id_stu_grade").val( grade);
        html_node.find("#id_user_nick").val(stu_nick );
        html_node.find("#id_contact_phone").val(parent_phone);
        html_node.find("#id_parent_nick").val(parent_nick);
        html_node.find("#id_user_addr").val(address );

        html_node.find("#id_small_class" ).admin_select_course({
            "course_type": 3001
        });
        html_node.find("#id_open_class" ).admin_select_course({
            "course_type": 1001
        });

        html_node.find("#id_origin_select").admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/user_manage_new/get_seller_student_orgin",
            //其他参数
            "args_ex"  : {
                phone: parent_phone
            },
            select_primary_field : "origin",
            select_display       : "origin",
            //字段列表
            'field_list' :[
                {
                title:"电话",
                field_name:"phone"
            },{
                title:"渠道",
                //width :50,
                render:function(val,item) {
                    return item.origin;
                }
            },{
                title:"状态",
                field_name:"status_str"
            }
            ] ,
            //查询列表
            filter_list:[
            ],
            "auto_close"       : true,
            //选择
            "onChange"         : function(val){
                html_node.find("#id_origin_select").val(val);
            },
            //加载数据后，其它的设置
            "onLoadData" : null
        });

        var select_origin_type=0;
        html_node.find("#id_select_origin").on("change",function(){
            select_origin_type=$(this).val();
            if(select_origin_type==0){
                html_node.find(".origin_select").show();
                html_node.find(".origin_input").hide();
            }else if(select_origin_type==1){
                html_node.find(".origin_input").show();
                html_node.find(".origin_select").hide();
            }
        });

        html_node.find("#id_bind_order").admin_select_dlg_ajax({
            "opt_type" : "select",
            "url"      : "/user_manage/get_user_normal_order",
            select_primary_field : "orderid",
            select_display       : "orderid",
            "args_ex" : {
                userid : userid
            },
            'field_list' :[
                {
                title:"合同编号",
                field_name:"orderid"
            },{
                title:"添加时间",
                field_name:"order_time_str"
            },{
                title:"合同课时数",
                field_name:"lesson_total"
            }
            ],
            filter_list:[],
            "auto_close" : true,
            "onLoadData" : null
        });

        html_node.find('#id_con_type').on("change",function(){
            var val = $(this).val();
            html_node.find(".test-listen").hide();
            html_node.find(".opt-con-type-div").hide();

            if(val == 1){
                html_node.find(".test-listen").show();
                html_node.find(".count_block").show();
                html_node.find(".bind_order").show();
            }else if(val == 0 || val == 3){
                html_node.find(".count_block").show();
            }else if(val == 3001 ){ //small_class
                html_node.find(".small-class-div").show();
            }else if(val == 0 || val == 4){

            }else if(val ==1001){
                html_node.find(".open-class-div").show();
            }
        });

        BootstrapDialog.show({
            title: '新增合同',
            message : html_node,
            closable: true,
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            },{
                label: '增加',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    var stu_nick         = $.trim(html_node.find("#id_user_nick").val());
                    var grade            = html_node.find("#id_stu_grade").val();
                    var subject          = html_node.find("#id_stu_subject").val();
                    var parent_nick      = $.trim(html_node.find("#id_parent_nick").val());
                    var phone            = $.trim(html_node.find("#id_contact_phone").val());
                    var address          = $.trim(html_node.find("#id_user_addr").val());
                    var lesson_total     = $.trim(html_node.find("#id_lesson_count").val());
                    var contract_type    = html_node.find("#id_con_type").val();
                    var need_receipt     = html_node.find("#id_need_receipt").val();
                    var title            = $.trim(html_node.find("#id_receipt_title").val());
                    var requirement      = $.trim(html_node.find("#id_lesson_requirement").val());
                    var presented_reason = $.trim(html_node.find("#id_presented_reason").val());
                    var should_refund    = html_node.find("#id_should_refund").val();
                    var taobao_orderid   = html_node.find(".taobao_orderid").val();
                    var competition_flag = html_node.find("#id_competition_flag").val();
                    var bind_orderid     = html_node.find("#id_bind_order").val();
                    var config_courseid  = 0;

                    var origin='';
                    if(select_origin_type==0){
                        origin = html_node.find("#id_origin_select").val();
                    }else if(select_origin_type == 1){
                        origin = html_node.find("#id_origin_input").val();
                    }

                    if(competition_flag==1 && contract_type!=0 && contract_type!=3){
                        BootstrapDialog.alert("竞赛合同的类型只能为1对1常规,或续费合同!");
                        return;
                    }

                    if(subject == -1){
                        BootstrapDialog.alert("请选择合同科目");
                        return;
                    }

                    if(contract_type == -1){
                        BootstrapDialog.alert("请选择合同类型");
                        return;
                    }

                    if ( contract_type==3001  ) { //small class
                        config_courseid  = html_node.find("#id_small_class").val();
                        if ( !(config_courseid>0) ){
                            alert("请选择小班课");
                            return;
                        }
                    }else if(contract_type==1001){
                        config_courseid  = html_node.find("#id_open_class").val();
                        if ( !(config_courseid>0) ){
                            alert("请选择公开课");
                            return;
                        }
                    }else if(contract_type != 2  && !isNumber(lesson_total) ){
                        alert("课程总数应该为数字");
                        return;
                    }
                    /**
                     *
                     else if(contract_type==1 && bind_orderid==''){
                     BootstrapDialog.alert("请选择赠送合同需要绑定的付费合同!");
                     return;
                     }
                    */

                    $.ajax({
                        type     :"post",
                        url      :"/user_manage/add_contract",
                        dataType :"json",
                        data     :{
                            'userid'            : userid
                            ,'stu_nick'         : stu_nick
                            ,'grade'            : grade
                            ,'subject'          : subject
                            ,'parent_nick'      : parent_nick
                            ,'parent_phone'     : phone
                            ,'address'          : address
                            ,'lesson_total'     : lesson_total
                            ,'need_receipt'     : need_receipt
                            ,'title'            : title
                            ,'requirement'      : requirement
                            ,'contract_type'    : contract_type
                            ,"presented_reason" : presented_reason
                            ,"should_refund"    : should_refund
                            ,"config_courseid"  : config_courseid
                            ,"taobao_orderid"   : taobao_orderid
                            ,"origin"           : origin
                            ,"competition_flag" : competition_flag
                            ,"bind_orderid"     : bind_orderid
                        },
                        success  : function(result){
                            if(result.ret != 0){
                                alert(result.info);
                            }else{
                                alert("插入成功！");
                                window.location.reload();
                            }
                        }
                    });
                }
            }]
        });
    };

    var show_add_contract_new=function( require_id ,contract_type , data ,contract_from_type){
        var html_node=$.dlg_need_html_by_id( "id_dlg_add_contract_new");
        //原价
        var $discount_price       = html_node.find(".field-discount_price");
        var $order_promotion_desc = html_node.find(".field-order_promotion_desc");
        var $div_spec             = html_node.find( ".div-spec");
        var $order_require_flag   = html_node.find(".field-order_require_flag");

        var $has_share_activity_flag    = html_node.find(".field-has_share_activity");
        var $order_desc_list = html_node.find(".field-order_desc_list");

        var $nick    = html_node.find(".field-nick");
        var $grade   = html_node.find(".field-grade");
        var $phone   = html_node.find(".field-phone");
        var $subject = html_node.find(".field-subject");
        var $lesson_count     = html_node.find(".field-lesson_count");
        var $competition_flag = html_node.find(".field-competition_flag");
        var $pre_money        = html_node.find(".field-pre-money");

        var $order_promotion_type = html_node.find(".field-order_promotion_type");
        var $period_flag = html_node.find(".field-period_flag");
        var $promotion_spec_present_lesson= html_node.find(".field-promotion_spec_present_lesson");
        var $promotion_spec_discount_price= html_node.find(".field-promotion_spec_discount_price");
        var $discount_reason= html_node.find(".field-discount_reason");
        var $receipt_title= html_node.find(".field-receipt_title");
        var $is_new_stu = html_node.find(".field-is_new_stu");
        if(contract_type==3){
            $is_new_stu.parent().parent().show();
        }


        Enum_map.append_option_list( "boolean", $order_require_flag ,true);
        Enum_map.append_option_list( "boolean", $has_share_activity_flag,true);
        Enum_map.append_option_list( "grade", $grade,true);
        Enum_map.append_option_list( "subject", $subject,true);
        Enum_map.append_option_list( "boolean", $competition_flag,true);
        Enum_map.append_option_list( "order_promotion_type", $order_promotion_type,true);


        $nick.val(data.nick);
        $grade.val(data.grade);
        $phone.val(data.phone);
        $subject.val(data.subject);


        $order_require_flag.val(0);

        var opt_spec=function(){
            if ($order_require_flag.val()==1) {
                $div_spec.show();
            }else{
                $div_spec.hide();
            }
        };

        $order_require_flag.on("change", opt_spec);
        $order_promotion_type.val(2); //打折
        opt_spec();
        var get_disable_activity_list = function() {
            var arr = [];
            $order_desc_list.find(".table-row").each( function (i, item){
                var $item=$(item);
                var succ_flag= $item.data("succ_flag");
                var order_activity_type= $item.data("order_activity_type");
                if (succ_flag==2) {
                    arr.push( order_activity_type );
                }
            });
            return arr.join(',');
        }

        var reload_present_info = function() {
            var order_promotion_type=  $order_promotion_type.val();
            if (!($lesson_count.val() >0) ) {
                return;
            }

            $.do_ajax("/ss_deal/get_order_price_info",{
                grade: data.grade,
                userid : data.userid,

                disable_activity_list  :  get_disable_activity_list(),
                competition_flag:$competition_flag.val(),
                lesson_count:$lesson_count.val()*100,
                order_promotion_type: order_promotion_type,
                contract_type: contract_type,
                period_flag : $period_flag.val(),
                require_id :  require_id
            },function(resp){
                var data=resp.data;
                console.log(data);
                $discount_price.val(data.price );
                $promotion_spec_present_lesson.val( data.present_lesson_count );
                $promotion_spec_discount_price.val( data.discount_price );
                //alert("xx");
                $.do_ajax( "/ajax_deal2/get_order_desc_html_str",{
                    "str" : JSON.stringify(data.desc_list)
                }, function (resp){
                    $order_desc_list.html(resp.html_str);
                    $order_desc_list.find(".table-header").append($("<th>操作</th>"));
                    $order_desc_list.find(".table-row").each( function (i, item){
                        var $item=$(item);
                        var succ_flag= $item.data("succ_flag");
                        var fa_type = "fa-times";
                        if (succ_flag ==2 ) {
                            var fa_type = "fa-check";
                        }
                        var $td_item=$("<td> <a class=\"btn fa " +fa_type +"\" > </a></td>");
                        $td_item.find("a").on("click",function(){
                            if (succ_flag==2) {
                                $item.data("succ_flag" ,1 );
                            }else{
                                $item.data("succ_flag" ,2 );
                            }
                            reload_present_info();
                        });
                        $item.append($td_item);
                    } ) ;

                } );

                if (order_promotion_type==1) {
                    $order_promotion_desc.val("赠送:"+ data.present_lesson_count +"课时" );
                } else if (order_promotion_type==2) {
                    if (data.discount==100) {
                        $order_promotion_desc.val("无折扣" );
                    }else{
                        $order_promotion_desc.val("打折:"+ data.discount_price +"元("+data.discount_count + "折)" );
                    }
                }else{
                    $order_promotion_desc.val("");
                }
            });
        };
        $competition_flag.set_input_change_event(reload_present_info);
        $lesson_count.set_input_change_event(reload_present_info);
        $order_promotion_type.set_input_change_event(reload_present_info);
        $period_flag.set_input_change_event(reload_present_info);

        $nick.set_input_readonly(true);
        $phone.set_input_readonly(true);
        $grade.set_input_readonly(true);
        if ($subject.val()>0  ) {
            $subject.set_input_readonly(true);
        }
        $discount_price.set_input_readonly(true);
        $order_promotion_desc.set_input_readonly(true);

        BootstrapDialog.show({
            title    : '创建合同['+ Enum_map.get_desc("contract_type",contract_type)+']' ,
            message  : html_node,
            closable : true,
            size: BootstrapDialog.SIZE_WIDE ,
            buttons: [{
                label  : '返回',
                action : function(dialog) {
                    dialog.close();
                }
            },{
                label  : '确认',
                action : function(dialog) {
                    var  deal_func=function() {

                        $.do_ajax("/ss_deal/seller_add_contract_new",{
                            require_id                    : require_id,
                            contract_type                 : contract_type,
                            contract_from_type            : contract_from_type,
                            competition_flag              : $competition_flag.val(),
                            lesson_total                  : $lesson_count.val()*100,
                            disable_activity_list  :  get_disable_activity_list(),
                            discount_reason               : $discount_reason.val(),
                            title                         : $receipt_title.val(),
                            order_require_flag            : $order_require_flag.val(),
                            userid                        : data.userid,
                            pre_money                     : $pre_money.val(),
                            grade                         : data.grade,
                            subject                       : $subject.val(),
                            period_flag : $period_flag.val(),
                            origin                        : data.origin,
                            order_promotion_type          : $order_promotion_type.val(),
                            promotion_spec_discount       : $promotion_spec_discount_price.val()*100,
                            promotion_spec_present_lesson : $promotion_spec_present_lesson.val()*100,
                            has_share_activity_flag       : $has_share_activity_flag.val(),
                            is_new_stu                    : $is_new_stu.val()
                        });

                    }
                    if ( $period_flag.val()==0) {
                        BootstrapDialog.confirm( "你选择全款,之后的处理过程中,不能分期,可以吗?!",
                                                 function(val ){
                                                     if (val) {
                                                         if($is_new_stu.val()==1){

                                                             $("<div></div>").admin_select_dlg_ajax({
                                                                 "opt_type" : "select", // or "list"
                                                                 "url"      : "/ss_deal/get_require_list_js",
                                                                 select_primary_field : "require_id",
                                                                 select_display       : "require_id",
                                                                 //其他参数
                                                                 "args_ex" : {
                                                                     userid:data.userid,
                                                                     subject:$subject.val()
                                                                 },
                                                                 //字段列表
                                                                 'field_list' :[
                                                                     {
                                                                     title:"渠道",
                                                                     render:function(val,item) {
                                                                         return item.origin;
                                                                     }
                                                                 },{
                                                                     title:"科目",
                                                                     render:function(val,item) {
                                                                         return item.subject_str;
                                                                     }
                                                                 },{

                                                                     title:"时间",
                                                                     render:function(val,item) {
                                                                         return item.require_time ;
                                                                     }

                                                                 },{
                                                                     title:"教务是否接受",
                                                                     //width :50,
                                                                     render:function(val,item) {
                                                                         return $(item.accept_flag_str );
                                                                     }
                                                                 },{
                                                                     title:"课程是否成功",
                                                                     render:function(val,item) {
                                                                         return $(item.success_flag_str);
                                                                     }

                                                                 },{
                                                                     title:"老师",
                                                                     render:function(val,item) {
                                                                         return item.teacher_nick;
                                                                     }
                                                                 },{
                                                                     title:"上课时间",
                                                                     render:function(val,item) {
                                                                         return item.lesson_start;
                                                                     }

                                                                 }
                                                                 ] ,
                                                                 filter_list: [],

                                                                 "auto_close"       : true,
                                                                 //选择
                                                                 "onChange"         : function(reid){
                                                                     if (reid <=0 ) {
                                                                         alert("请选择试听课");
                                                                     }else{
                                                                         reid = reid;
                                                                         deal_func(); 
                                                                     }
                                                                 },
                                                                 //加载数据后，其它的设置
                                                                 "onLoadData"       : null,
                                                             });
 
                                                         }else{
                                                             deal_func(); 
                                                         }
                                                     }
                                                 });
                    }else{
                        if($is_new_stu.val()==1){

                            $("<div></div>").admin_select_dlg_ajax({
                                "opt_type" : "select", // or "list"
                                "url"      : "/ss_deal/get_require_list_js",
                                select_primary_field : "require_id",
                                select_display       : "require_id",
                                //其他参数
                                "args_ex" : {
                                    userid:data.userid,
                                    subject:$subject.val()
                                },
                                //字段列表
                                'field_list' :[
                                    {
                                    title:"渠道",
                                    render:function(val,item) {
                                        return item.origin;
                                    }
                                },{
                                    title:"科目",
                                    render:function(val,item) {
                                        return item.subject_str;
                                    }
                                },{

                                    title:"时间",
                                    render:function(val,item) {
                                        return item.require_time ;
                                    }

                                },{
                                    title:"教务是否接受",
                                    //width :50,
                                    render:function(val,item) {
                                        return $(item.accept_flag_str );
                                    }
                                },{
                                    title:"课程是否成功",
                                    render:function(val,item) {
                                        return $(item.success_flag_str);
                                    }

                                },{
                                    title:"老师",
                                    render:function(val,item) {
                                        return item.teacher_nick;
                                    }
                                },{
                                    title:"上课时间",
                                    render:function(val,item) {
                                        return item.lesson_start;
                                    }

                                }
                                ] ,
                                filter_list: [],

                                "auto_close"       : true,
                                //选择
                                "onChange"         : function(reid){
                                    if (reid <=0 ) {
                                        alert("请选择试听课");
                                    }else{
                                        require_id = reid;
                                        deal_func(); 
                                    }
                                },
                                //加载数据后，其它的设置
                                "onLoadData"       : null,
                            });
                            
                        }else{
                            deal_func(); 
                        }
                    }

                }
            }]
        });
    };

    $(" .opt-change-default_lesson_count").on("click",function(){
        var data    = $(this).get_opt_data();
        var nick    = $(this).closest("tr") .find(".stu_nick").text();
        var orderid = data.orderid;

        var $lesson_count    = $("<input/>");
        var old_lesson_total = data.lesson_total;
        $lesson_count.val( old_lesson_total);

        var arr = [
            ["姓名",nick ],
            ["课时数",$lesson_count],
        ];

        $.show_key_value_table("修改课时数", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/user_deal/course_set_default_lesson_count",{
                    "orderid"          : orderid,
                    "lesson_total"     : $lesson_count.val()*100,
                    "old_lesson_total" : old_lesson_total*100,
                    "lesson_left"      : data.lesson_left,
                });
            }
        });
    });

    $(".opt-edit-contract").on("click", function(){
        var contractid = $(this).parent().data('contractid');
        var orderid    = $(this).parent().data('orderid');
        var userid     = $(this).parent().data('userid');
        var contract_type = $(this).parent().data('contract_type');
        var stu_from_type = $(this).parent().data('stu_from_type');
        var opt_data= $(this).get_opt_data();
        $.do_ajax("/user_manage/get_self_contract",{
            "userid"     : userid,
            "contractid" : contractid
        },function(result){
            var id_contract = $("<select/>");
            var id_stu_from_type  = $("<select/>");
            var id_subject  = $("<select/>");
            var id_grade = $("<select/>");
            var id_competition_flag=$("<select/>");

            Enum_map.append_option_list( "contract_type", id_contract,true);
            Enum_map.append_option_list( "contract_from_type", id_stu_from_type,true);
            Enum_map.append_option_list( "subject", id_subject,true);
            Enum_map.append_option_list( "grade", id_grade,true);
            Enum_map.append_option_list( "boolean", id_competition_flag,true);
            id_contract.val(contract_type);
            id_stu_from_type.val(stu_from_type);

            id_grade.val(opt_data.grade);
            id_subject.val(opt_data.subject);
            id_competition_flag.val(opt_data.competition_flag );

            var arr = [
                ['合同类型',id_contract],
                ['年级',id_grade],
                ['1v1详细类型',id_stu_from_type ],
                ['科目',id_subject ],
                ['竞赛',id_competition_flag ],
            ];
            id_contract.val(result["data"]);


            $.show_key_value_table("修改合同类型", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    var contract_type = id_contract.val();

                    $.do_ajax('/user_deal/update_course_type',{
                        'userid'      : userid,
                        'course_type' : contract_type,
                        'stu_from_type' : id_stu_from_type.val(),
                        'subject' : id_subject.val(),
                        'grade' : id_grade.val(),
                        'competition_flag' : id_competition_flag.val(),
                        'orderid'     : orderid
                    });
                    $.ajax({
                        url      : '/user_manage/update_contract_type',
                        type     : 'POST',
                        dataType : 'json',
                        data     : {
                            'userid'        : userid,
                            'contractid'    : contractid,
                            'contract_type' : contract_type
                        },
                        success             : function(data) {
                            alert(data.info);
                            if(data.ret != -1){
                                window.location.reload();
                            }
                        }
                    });
                }
            });

        });
    });


    $(".opt-change-contract-starttime").on("click",function(){
        var contractid = $(this).parent().data('contractid');
        var orderid    = $(this).parent().data('orderid');

        var contract_starttime = $("<input/>");
        var arr                = [
            [ "合同生效时间",  contract_starttime]
        ];
        contract_starttime.datetimepicker({
            lang       : 'ch',
            timepicker : false,
            format     : 'Y-m-d'
        });

        $.do_ajax ( "/user_manage/get_contract_starttime", {
            "contractid" : contractid,
            "orderid"    : orderid
        },function(result){
            var data = result["data"];
            if ( data >0 ) {
                contract_starttime.val( $.DateFormat ( data, "yyyy-MM-dd" ));
            }

            $.show_key_value_table("修改合同生效时间", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog){
                    $.do_ajax('/user_manage/set_contract_starttime', {
                        "contractid"         : contractid,
                        "orderid"            : orderid,
                        "contract_starttime" : contract_starttime.val()
                    },function(){
                        alert('设置成功' );
                        window.location.reload();
                    });
                    dialog.close();
                }
            });
        });
    });

    var opt_add_new_1 =function() {
        $.admin_select_user( $(this), "student", function(id){
            if (id<=0) {
                alert("没有选择学生!");
                return ;
            }

            $("<div></div>").admin_select_dlg_ajax({
                "opt_type" : "select", // or "list"
                "url"      : "/ss_deal/get_require_list_js",
                select_primary_field : "require_id",
                select_display       : "require_id",
                //其他参数
                "args_ex" : {
                    userid:id
                },
                //字段列表
                'field_list' :[
                    {
                        title:"渠道",
                        render:function(val,item) {
                            return item.origin;
                        }
                    },{
                        title:"科目",
                        render:function(val,item) {
                            return item.subject_str;
                        }
                    },{

                        title:"时间",
                        render:function(val,item) {
                            return item.require_time ;
                        }

                    },{
                        title:"教务是否接受",
                        //width :50,
                        render:function(val,item) {
                            return $(item.accept_flag_str );
                        }
                    },{
                        title:"课程是否成功",
                        render:function(val,item) {
                            return $(item.success_flag_str);
                        }

                    },{
                        title:"老师",
                        render:function(val,item) {
                            return item.teacher_nick;
                        }
                    },{
                        title:"上课时间",
                        render:function(val,item) {
                            return item.lesson_start;
                        }

                    }
                ] ,
                filter_list: [],

                "auto_close"       : true,
                //选择
                "onChange"         : function(require_id){
                    if (require_id <=0 ) {
                        alert("请选择试听课");
                    }else{
                        $.do_ajax("/ss_deal/get_test_lesson_info_by_require_id", {
                            "require_id" :require_id
                        },function(resp){
                            show_add_contract_new(require_id ,0, resp.data,0 );
                        });
                    }
                },
                //加载数据后，其它的设置
                "onLoadData"       : null,
            });
        },false,{
            "adminid" :  g_args.self_adminid
        });
    }

    var opt_extend_new_1 =function() {
        $.admin_select_user( $(this), "student", function(id){
            if (id<=0) {
                alert("没有选择学生!");
                return ;
            }

            $("<div></div>").admin_select_dlg_ajax({
                "opt_type" :  "select", // or "list"
                "url"          : "/ss_deal/get_require_list_js",
                select_primary_field : "require_id",
                select_display       : "require_id",

                //其他参数
                "args_ex" : {
                    userid:id
                },
                //字段列表
                'field_list' :[
                    {
                        title:"渠道",
                        render:function(val,item) {
                            return item.origin;
                        }
                    },{

                        title:"科目",
                        render:function(val,item) {
                            return item.subject_str;
                        }
                    },{

                        title:"时间",
                        render:function(val,item) {
                            return item.require_time ;
                        }

                    },{
                        title:"教务是否接受",
                        //width :50,
                        render:function(val,item) {
                            return $(item.accept_flag_str );
                        }
                    },{
                        title:"课程是否成功",
                        render:function(val,item) {
                            return $(item.success_flag_str);
                        }

                    },{
                        title:"老师",
                        render:function(val,item) {
                            return item.teacher_nick;
                        }
                    },{
                        title:"上课时间",
                        render:function(val,item) {
                            return item.lesson_start;
                        }

                    }
                ] ,
                filter_list: [],

                "auto_close"       : true,
                //选择
                "onChange"         : function(require_id){
                    if (require_id <=0 ) {
                        alert("请选择试听课");
                    }else{
                        $.do_ajax("/ss_deal/get_test_lesson_info_by_require_id", {
                            "require_id" :require_id,
                        },function(resp){
                            show_add_contract_new(require_id ,0, resp.data,11 );
                        });
                    }
                },

                //加载数据后，其它的设置
                "onLoadData"       : null,

            });

        },false,{
            "adminid" :  g_args.self_adminid
        });
    }







    $("#id_add_contract").on("click",function(){
        $.admin_select_user( $(this), "student", function(id){
            $.do_ajax("/user_deal/get_student_info_for_add_contract",{"userid":id},function(rep_json){
                var opt_data=rep_json.data;
                show_add_contract(opt_data.userid, opt_data.grade, opt_data.nick , opt_data.parent_name,opt_data.parent_phone, opt_data.address );
            } )
        });
    });
    var opt_add_next=function(){

        var show_add_next_dlg=function(userid) {
            $.do_ajax("/ss_deal/get_add_user_order_info_by_userid", {
                "userid"  :userid
            },function(ret){
                if (ret.ret) {
                    alert(ret.info) ;
                    return;
                }
                var data=ret.data;
                var $lesson_total=$("<input/>") ;
                var $price=$("<input/>") ;
                var $competition_flag=$("<select > <option value=0>否</option>  <option value=1>是</option></select>") ;
                var $order_require_flag=$("<select > <option value=0>否</option>  <option value=1>是</option></select>") ;
                var $discount_price=$("<input/>") ;
                var $discount_reason=$("<textarea/>") ;
                var $requirement=$("<textarea/>") ;

                var $need_receipt=$("<select > <option value=0>否</option>  <option value=1>是</option></select>") ;
                var $title=$("<input/>") ;
                var arr=[
                    [ "学生", data.nick ]  ,
                    [ "电话", data.phone]  ,
                    [ "年级", data.grade_str ]  ,
                    [ "渠道", data.origin ]  ,
                    [ "特殊折扣申请", $order_require_flag ]  ,
                    [ "竞赛合同", $competition_flag ]  ,
                    [ "总课时", $lesson_total ]  ,
                    [ "原价", $discount_price ]  ,
                    [ "折后价", $price ]  ,
                    [ "折扣原因", $discount_reason]  ,
                    [ "是否需要发票", $need_receipt ]  ,
                    [ "发票抬头", $title]  ,
                ];

                $.show_key_value_table("新建 续费", arr ,{
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        var price= $price.val();
                        var lesson_total=  $lesson_total.val();
                        if (price/lesson_total>150 && $competition_flag.val()==0) {
                            alert("每课时单价超过150,不通过,这边填课时数,不是课次数 ,课时数=课次数*3"  );
                            return ;
                        }



                        $.do_ajax("/ss_deal/seller_add_contract",{
                            require_id :  0,
                            from_test_lesson_id : 0,
                            competition_flag : $competition_flag.val(),
                            lesson_total : $lesson_total.val(),
                            price : $price.val(),
                            discount_price : $discount_price.val(),
                            discount_reason : $discount_reason.val(),
                            need_receipt : $need_receipt.val(),
                            title : $title.val(),
                            requirement : $requirement.val(),
                            order_require_flag: $order_require_flag.val(),
                            userid: userid,
                            grade: data.grade,
                            subject: 0,
                            contract_type: 3, //续费
                            origin: data.origin
                        });
                    }
                });

            });
        };




        $.admin_select_user( $(this), "student", function(id){
            show_add_next_dlg(id);
        });
    };


    var opt_add_new_no_test_lesson_1=function(){

        var show_add_no_test_lesson_dlg=function(test_lesson_subject_id) {
            $.do_ajax("/ss_deal/get_add_user_order_info", {
                "test_lesson_subject_id" :test_lesson_subject_id
            },function(resp){
                if (resp.ret) {
                    alert(resp.info) ;
                    return;
                }
                var data=resp.data;
                show_add_contract_new(0,0, resp.data,0 );

            });
        };



        var list_type= "student";
        if (g_account_role==2  ) {
            list_type=  "seller_student";
        }

        $.admin_select_user( $(this), list_type , function(id){
            if (id<=0) {
                alert("没有选择学生!");
                return ;
            }

            $("<div></div>").admin_select_dlg_ajax({
                "opt_type" :  "select", // or "list"
                "url"          : "/ss_deal/get_test_lesson_subject_list_js",
                select_primary_field : "test_lesson_subject_id",
                select_display       : "test_lesson_subject_id",

                //其他参数
                "args_ex" : {
                    userid:id
                },
                //字段列表
                'field_list' :[
                    {
                        title:"学生",
                        render:function(val,item) {
                            return item.nick;
                        }
                    },{
                        title:"电话",
                        render:function(val,item) {
                            return item.phone;
                        }
                    },{

                        title:"渠道",
                        render:function(val,item) {
                            return item.origin;
                        }
                    },{
                        title:"科目",
                        render:function(val,item) {
                            return item.subject_str;
                        }
                    },{
                        title:"年级",
                        render:function(val,item) {
                            return item.grade_str;
                        }

                    }
                ] ,
                filter_list: [],

                "auto_close"       : true,
                //选择
                "onChange"         : function(test_lesson_subject_id){
                    if (test_lesson_subject_id<=0 ) {
                        alert("请选择资源");
                    }else{

                        show_add_no_test_lesson_dlg(test_lesson_subject_id);
                    }
                },

                //加载数据后，其它的设置
                "onLoadData"       : null,

            });

        },false,{
            "adminid" :  g_args.self_adminid
        });
    };

    var opt_add_new_no_test_lesson=function(){

        var show_add_no_test_lesson_dlg=function(test_lesson_subject_id) {
            $.do_ajax("/ss_deal/get_add_user_order_info", {
                "test_lesson_subject_id" :test_lesson_subject_id
            },function(ret){
                if (ret.ret) {
                    alert(ret.info) ;
                    return;
                }
                var data=ret.data;
                var $lesson_total=$("<input/>") ;
                var $price=$("<input/>") ;
                var $competition_flag=$("<select > <option value=0>否</option>  <option value=1>是</option></select>") ;
                var $order_require_flag=$("<select > <option value=0>否</option>  <option value=1>是</option></select>") ;
                var $discount_price=$("<input/>") ;
                var $discount_reason=$("<textarea/>") ;
                var $requirement=$("<textarea/>") ;

                var $need_receipt=$("<select > <option value=0>否</option>  <option value=1>是</option></select>") ;
                var $title=$("<input/>") ;
                var arr=[
                    [ "学生", data.nick ]  ,
                    [ "电话", data.phone]  ,
                    [ "年级", data.grade_str ]  ,
                    [ "科目", data.subject_str ]  ,
                    [ "渠道", data.origin ]  ,
                    [ "特殊折扣申请(提成打9折)", $order_require_flag ]  ,
                    [ "竞赛合同", $competition_flag ]  ,
                    [ "总课时", $lesson_total ]  ,
                    [ "原价", $discount_price ]  ,
                    [ "折后价", $price ]  ,
                    [ "折扣原因", $discount_reason]  ,
                    [ "排课需求", $requirement ]  ,
                    [ "是否需要发票", $need_receipt ]  ,
                    [ "发票抬头", $title]  ,
                ];

                $.show_key_value_table("新建 未试听 直接签单 ", arr ,{
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        var price= $price.val();
                        var lesson_total=  $lesson_total.val();
                        if (price/lesson_total>150 && $competition_flag.val()==0) {
                            alert("每课时单价超过150,不通过,这边填课时数,不是课次数 ,课时数=课次数*3"  );
                            return ;
                        }



                        $.do_ajax("/ss_deal/seller_add_contract",{
                            require_id :  0,
                            from_test_lesson_id : 0,
                            competition_flag : $competition_flag.val(),
                            lesson_total : $lesson_total.val(),
                            price : $price.val(),
                            discount_price : $discount_price.val(),
                            discount_reason : $discount_reason.val(),
                            need_receipt : $need_receipt.val(),
                            title : $title.val(),
                            requirement : $requirement.val(),
                            order_require_flag: $order_require_flag.val(),
                            userid: data.userid,
                            grade: data.grade,
                            subject: data.subject,
                            origin: data.origin
                        });
                    }
                });

            });
        };




        $.admin_select_user( $(this), "seller_student", function(id){
            if (id<=0) {
                alert("没有选择学生!");
                return ;
            }

            $("<div></div>").admin_select_dlg_ajax({
                "opt_type" :  "select", // or "list"
                "url"          : "/ss_deal/get_test_lesson_subject_list_js",
                select_primary_field : "test_lesson_subject_id",
                select_display       : "test_lesson_subject_id",

                //其他参数
                "args_ex" : {
                    userid:id
                },
                //字段列表
                'field_list' :[
                    {
                        title:"学生",
                        render:function(val,item) {
                            return item.nick;
                        }
                    },{
                        title:"电话",
                        render:function(val,item) {
                            return item.phone;
                        }
                    },{

                        title:"渠道",
                        render:function(val,item) {
                            return item.origin;
                        }
                    },{
                        title:"科目",
                        render:function(val,item) {
                            return item.subject_str;
                        }
                    },{
                        title:"年级",
                        render:function(val,item) {
                            return item.grade_str;
                        }

                    }
                ] ,
                filter_list: [],

                "auto_close"       : true,
                //选择
                "onChange"         : function(test_lesson_subject_id){
                    if (test_lesson_subject_id<=0 ) {
                        alert("请选择资源");
                    }else{

                        show_add_no_test_lesson_dlg(test_lesson_subject_id);
                    }
                },

                //加载数据后，其它的设置
                "onLoadData"       : null,

            });

        },false,{
            "adminid" :  g_args.self_adminid
        });
    };

    var opt_add_new=function(){
        var show_add_dlg=function(require_id) {
            $.do_ajax("/ss_deal/get_test_lesson_info_by_require_id", {
                "require_id" :require_id
            },function(ret){
                var data                = ret.data;
                var $lesson_total       = $("<input/>") ;
                var $price              = $("<input/>") ;
                var $competition_flag   = $("<select > <option value=0>否</option>  <option value=1>是</option></select>") ;
                var $order_require_flag = $("<select > <option value=0>否</option>  <option value=1>是</option></select>") ;
                var $discount_price     = $("<input/>") ;
                var $discount_reason    = $("<textarea/>") ;
                var $requirement        = $("<textarea/>") ;

                var $need_receipt = $("<select > <option value=0>否</option>  <option value=1>是</option></select>") ;
                var $title        = $("<input/>") ;

                var arr           = [
                    [ "学生", data.nick]  ,
                    [ "电话", data.phone]  ,
                    [ "年级", data.grade_str ]  ,
                    [ "科目", data.subject_str ]  ,
                    [ "渠道", data.origin ]  ,
                    [ "特殊折扣申请(提成打9折)", $order_require_flag ]  ,
                    [ "竞赛合同", $competition_flag ]  ,
                    [ "总课时", $lesson_total ]  ,
                    [ "原价", $discount_price ]  ,
                    [ "折后价", $price ]  ,
                    [ "折扣原因", $discount_reason]  ,
                    [ "排课需求", $requirement ]  ,
                    [ "是否需要发票", $need_receipt ]  ,
                    [ "发票抬头", $title]  ,
                ];

                $.show_key_value_table("新建合同", arr ,{
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        var price= $price.val();
                        var lesson_total=  $lesson_total.val();
                        if (price/lesson_total>150 && $competition_flag.val()==0) {
                            alert("每课时单价超过150,不通过,这边填课时数,不是课次数 ,课时数=课次数*3"  );
                            return ;
                        }

                        $.do_ajax("/ss_deal/seller_add_contract",{
                            require_id          : require_id ,
                            from_test_lesson_id : data.lessonid,
                            competition_flag    : $competition_flag.val(),
                            lesson_total        : $lesson_total.val(),
                            price               : $price.val(),
                            discount_price      : $discount_price.val(),
                            discount_reason     : $discount_reason.val(),
                            need_receipt        : $need_receipt.val(),
                            title               : $title.val(),
                            requirement         : $requirement.val(),
                            order_require_flag  : $order_require_flag.val()
                        });

                    }
                });

            });
        };


        $.admin_select_user( $(this), "seller_student", function(id){
            if (id<=0) {
                alert("没有选择学生!");
                return ;
            }

            $("<div></div>").admin_select_dlg_ajax({
                "opt_type" :  "select", // or "list"
                "url"          : "/ss_deal/get_require_list_js",
                select_primary_field : "require_id",
                select_display       : "require_id",

                //其他参数
                "args_ex" : {
                    userid:id
                },
                //字段列表
                'field_list' :[
                    {
                        title:"渠道",
                        render:function(val,item) {
                            return item.origin;
                        }
                    },{

                        title:"科目",
                        render:function(val,item) {
                            return item.subject_str;
                        }
                    },{

                        title:"时间",
                        render:function(val,item) {
                            return item.require_time ;
                        }

                    },{
                        title:"教务是否接受",
                        //width :50,
                        render:function(val,item) {
                            return $(item.accept_flag_str );
                        }
                    },{
                        title:"课程是否成功",
                        render:function(val,item) {
                            return $(item.success_flag_str);
                        }

                    },{
                        title:"老师",
                        render:function(val,item) {
                            return item.teacher_nick;
                        }
                    },{
                        title:"上课时间",
                        render:function(val,item) {
                            return item.lesson_start;
                        }

                    }
                ] ,
                filter_list: [],

                "auto_close"       : true,
                //选择
                "onChange"         : function(require_id){
                    if (require_id <=0 ) {
                        alert("请选择试听课");
                    }else{

                        show_add_dlg(require_id);
                    }
                },

                //加载数据后，其它的设置
                "onLoadData"       : null,

            });

        },false,{
            "adminid" :  g_args.self_adminid
        });
    };

    var add_free = function( from_parent_order_type ) {
        var do_post_add_free = function ( parent_order_id, lesson_total,order_require_flag,order_require_reason,to_userid , from_parent_order_lesson_count,part_competition_flag) {
            if (! part_competition_flag) {
                part_competition_flag =0;
            }
            $.do_ajax("/ss_deal/seller_add_contract_free",{
                "from_parent_order_type" : from_parent_order_type,
                "parent_order_id"        : parent_order_id,
                "order_require_flag"     : order_require_flag,
                "order_require_reason"   : order_require_reason,
                "lesson_total"           : lesson_total*100,
                "to_userid" : to_userid,
                "from_parent_order_lesson_count" :  from_parent_order_lesson_count*100 ,
                "part_competition_flag" : part_competition_flag
            });
        };

        //转赠课时
        var show_add_free_dlg_5 = function ( parent_order_id) {
            if (parent_order_id<=0) {
                alert("没有选择合同");
                return ;
            }

            var $lesson_count=$("<input/>");
            var $from_parent_order_lesson_count =$("<input/>");
            var $to_userid=$("<input/>");
            var $order_require_flag=$("<select > <option value=0>否</option>  <option value=1>是</option></select>") ;
            var $part_competition_flag=$("<select > <option value=0>否</option>  <option value=1>是</option></select>") ;
            var $order_require_reason=$("<textarea/>");

            var arr=[
                ["提取课时数",  $from_parent_order_lesson_count],
                ["转给学生",    $to_userid ],
                ["转给课时数",  $lesson_count  ],
                ["是否转为竞赛合同",  $part_competition_flag  ],
                ["需要特殊申请",$order_require_flag ],
                ["特殊申请说明",$order_require_reason ],
            ];
            $order_require_flag.on("change" ,function(){
                opt_change_order_require_flag();
            });
            var opt_change_order_require_flag = function () {
                $order_require_reason.key_value_table_show($order_require_flag.val()==1);
            };

            $.show_key_value_table ( "赠送课时", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    var to_userid= $to_userid.val();
                    if (!to_userid) {
                        alert("还没选择被赠送人");
                    }
                    do_post_add_free( parent_order_id, $lesson_count.val()  ,$order_require_flag.val(),$order_require_reason.val() ,  to_userid  ,  $from_parent_order_lesson_count.val(),$part_competition_flag.val()  );
                }
            },function(){
                opt_change_order_require_flag();
                $.admin_select_user($to_userid,"student");
            });
        };

        var show_add_free_dlg= function ( parent_order_id) {
            if (parent_order_id<=0) {
                alert("没有选择合同");
                return ;
            }


            if (from_parent_order_type == 0  || from_parent_order_type == 4 || from_parent_order_type == 3 || from_parent_order_type == 6) {
                var $lesson_count=$("<input/>");
                var $order_require_flag=$("<select > <option value=0>否</option>  <option value=1>是</option></select>") ;
                var $order_require_reason=$("<textarea/>");
                var arr=[
                    ["赠送课时数",   $lesson_count  ],
                    ["需要特殊申请",   $order_require_flag ],
                    ["特殊申请说明",   $order_require_reason ],
                ];
                $order_require_flag.on("change" ,function(){
                    opt_change_order_require_flag();
                });
                var opt_change_order_require_flag=function () {
                    $order_require_reason.key_value_table_show($order_require_flag.val()==1);
                };


                $.show_key_value_table ( "赠送课时", arr ,{
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        do_post_add_free( parent_order_id, $lesson_count.val()  ,$order_require_flag.val(),$order_require_reason.val(),0,0, 0);
                    }
                },function(){
                    opt_change_order_require_flag();
                    if(from_parent_order_type==6){
                        $order_require_flag.parent().parent().hide();
                        $order_require_flag.val(0);
                    }

                });
            }else{ //转介绍 , 试听24小时内赠送课时
                $.do_ajax("/ajax_deal2/check_origin_user_order_type",{
                    "orderid":parent_order_id
                },function(respp){
                    var ret = respp.ret;
                    if(ret==-1){
                        BootstrapDialog.alert(respp.info);
                        return;
                    }else{
                        var com_flag = respp.flag;
                        if(com_flag==1){
                            var $part_competition_flag=$("<select > <option value=0>否</option>  <option value=1>是</option></select>") ;
                            var arr=[
                                ["是否竞赛合同",  $part_competition_flag  ]
                            ];

                            $.show_key_value_table ( "选择类型", arr ,{
                                label: '确认',
                                cssClass: 'btn-warning',
                                action: function(dialog) {
                                    do_post_add_free( parent_order_id, 6,0,"",0,0,$part_competition_flag.val()); 
                                }
                            });
                        }else{
                            do_post_add_free( parent_order_id, 6,0,"",0,0, 0 ); 
                        }
                    }

                });

            }
        };

        var select_order_list_ajax=function(id) {
            $("<div></div>").admin_select_dlg_ajax({
                "opt_type"           : "select", // or "list"
                "url"                : "/ss_deal/get_order_list_js",
                select_primary_field : "orderid",
                select_display       : "orderid",
                //其他参数
                "args_ex" : {
                    userid:id
                },
                //字段列表
                'field_list' :[
                    {
                        title:"时间",
                        render:function(val,item) {
                            return item.order_time;
                        }
                    },{
                        title:"下单人",
                        render:function(val,item) {
                            return item.sys_operator;
                        }
                    },{
                        title:"学生",
                        render:function(val,item) {
                            return item.student_nick;
                        }
                    },{
                        title:"科目",
                        render:function(val,item) {
                            return item.subject_str;
                        }
                    },{
                        title:"课时数",
                        render:function(val,item) {
                            return item.lesson_total * item.default_lesson_count/100;
                        }
                    }
                ] ,
                filter_list: [],
                "auto_close"       : true,
                "onChange"         : function(orderid){
                    if (from_parent_order_type  ==5  ) {//转赠
                        show_add_free_dlg_5( orderid );
                    }else{
                        show_add_free_dlg( orderid );
                    }
                },
                "onLoadData"       : null,
            });
        };
        var select_flag_str="student";
        /*
        if (parseInt(  g_account_role) ==  2 ) { //销售
            select_flag_str="order_users";
        }
        */

        $.admin_select_user( $("<div></div>"), select_flag_str, function(id){
            if (id <=0 ) {
                alert("请选择学生");
                return;
            }
            select_order_list_ajax(id);
        },false,{
            "adminid" : g_args.self_adminid
        });
    };


    $("#id_add_seller_contract").on("click",function(){
        var btn_add_new      = $("<button class=\"btn btn-primary\"> 新签合同 </button>");
        var btn_add_new_1    = $("<button class=\"btn btn-primary\"> 新签合同 新版 </button>");
        var btn_extend_new_1 = $("<button class=\"btn btn-primary\"> 新签合同 扩课 新版 </button>");
        var btn_add_next     = $("<button class=\"btn btn-warning\"> 续费合同 </button>");
        var btn_add_next_1   = $("<button class=\"btn btn-warning\"> 续费合同 新版</button>");
        btn_add_new.on("click", opt_add_new );
        btn_add_new_1.on("click", opt_add_new_1 );
        btn_extend_new_1.on("click", opt_extend_new_1 );
        var btn_add_0 = $("<button class=\"btn btn-info\">  课程包特殊赠送  </button>");
        var btn_add_1 = $("<button class=\"btn btn-warning\">  转介绍赠送合同 </button>");
        var btn_add_2 = $("<button class=\"btn btn-warning\"> 试听24小时内 签约赠送 合同  </button>");
        var btn_add_3 = $("<button class=\"btn btn-warning\">  特批赠送 合同  </button>");
        var btn_add_5 = $("<button class=\"btn btn-warning  disabled \"> 转赠课时 </button>");
        var btn_add_6 = $("<button class=\"btn btn-warning\"> 助教配额赠送课时 </button>");
        var btn_add_new_no_test_lesson   = $("<button class=\"btn btn-primary\"> 新签 未听报 </button>");
        var btn_add_new_no_test_lesson_1 = $("<button class=\"btn btn-primary\"> 新签 未听报 新版 </button>");
        btn_add_new_no_test_lesson.on("click", opt_add_new_no_test_lesson );
        btn_add_new_no_test_lesson_1.on("click", opt_add_new_no_test_lesson_1 );
        btn_add_next.on("click", opt_add_next);
        //续费
        btn_add_next_1.on("click",function(){
            $.admin_select_user( $(this), "student", function(id){
                $.do_ajax("/ss_deal/get_add_user_order_info_by_userid",{
                    userid: id,
                    is_ass_flag: g_account_role==1,
                },function(resp){
                    show_add_contract_new(0,3, resp.data,0 );
                });
            });
        });

        btn_add_1.on("click", function(){
            add_free( 1 );
        });
        btn_add_2.on("click", function(){
            add_free( 2 );
        });
        btn_add_3.on("click", function(){
            add_free( 3 );
        });
        btn_add_0.on("click", function(){
            add_free( 4 );
        });
        btn_add_5.on("click", function(){
            add_free( 5 );
            /*
            if(g_account=="jim" || g_account=="adrian"){
                add_free( 5 );
            }else{
                alert("该功能下线 ");
            }
            */
        });
        btn_add_6.on("click", function(){
            add_free( 6 );
        });

        var arr=[
            [ "", btn_add_new_1 ],
            [ "", btn_extend_new_1 ],
            [ "", btn_add_0 ],
            [ "", btn_add_1 ],
            [ "", btn_add_5 ],
            //[ "", btn_add_2 ],
            //[ "", btn_add_new_no_test_lesson ],
            [ "", btn_add_new_no_test_lesson_1 ],
            [ "", "-----"],
            //[ "", btn_add_next],
            [ "", btn_add_next_1],
        ];

        if(
            window.location.pathname== "/user_manage/contract_list_seller_add"
                || window.location.pathname== "/user_manage/contract_list_seller_payed"
        ) {
            arr=[
                //[ "", btn_add_new ],
                [ "", btn_add_new_1 ],
                //[ "", btn_extend_new_1 ],
                //[ "", btn_add_0 ],
                [ "", btn_add_1 ],
                //[ "", btn_add_2 ],
                //[ "", btn_add_new_no_test_lesson ],
                [ "", btn_add_new_no_test_lesson_1 ],
                [ "", btn_add_3 ],
                [ "", btn_add_5 ],
            ];
        }else if ( window.location.pathname== "/user_manage/contract_list_ass" || window.location.pathname== "/user_manage/contract_list_ass/") {
            arr=[
                //[ "", btn_add_next],
                [ "", btn_add_new_1 ],
              //  [ "", btn_extend_new_1 ],
                //[ "", btn_add_2 ],
                [ "", btn_add_next_1],
                [ "", btn_add_0 ],
                [ "", btn_add_1 ],

                [ "", btn_add_3 ],
                [ "", btn_add_new_no_test_lesson_1 ],
                [ "", btn_add_5 ],
                [ "", btn_add_6 ],
            ];
        }

        $.show_key_value_table("选择合同类型",arr );
    });


    $(".opt-relation-order").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "list", // or "list"
            "url"          : "/ss_deal/get_relation_order_list_js",
            select_primary_field : "orderid",
            select_display       : "orderid",

            //其他参数
            "args_ex" : {
                orderid:opt_data.orderid,
                contract_type: opt_data.contract_type
            },
            //字段列表
            'field_list' :[
                {
                    title:"当前",
                    render:function(val,item) {
                        return item.self_flag_str;
                    }
                },{
                    title:"时间",
                    render:function(val,item) {
                        return item.order_time;
                    }

                },{
                    title:"学生",
                    render:function(val,item) {
                        return item.student_nick;
                    }
                },{
                    title:"申请人",
                    render:function(val,item) {
                        return item.sys_operator;
                    }
                },{


                    title:"分类",
                    render:function(val,item) {
                        return item.contract_type_str ;
                    }
                },{

                    title:"赠送原因",
                    render:function(val,item) {
                        return item.from_parent_order_type_str ;
                    }

                },{
                    title:"课时数",
                    //width :50,
                    render:function(val,item) {
                        return item.lesson_total;
                    }
                },{
                    title:"金额",
                    render:function(val,item) {
                        return item.price;
                    }

                }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : function(require_id,row_data){
            },

            //加载数据后，其它的设置
            "onLoadData"       : null,

        });


    });

    $(".opt-flow-node-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.flow_show_all_info( opt_data.flowid);

    });





    if(window.location.pathname== "/user_manage/contract_list" ) {
        $("#id_sys_operator").parent().parent().data("always_hide",0);
    }

    if(window.location.pathname== "/user_manage/contract_list_seller_add"
       || window.location.pathname== "/user_manage/contract_list_seller_payed"
       || window.location.pathname== "/user_manage/contract_list_ass"
    ) {
        $("#id_add_contract").hide();
        $(".td-origin").hide();
    }

    /**
     *
     if(window.location.pathname== "/user_manage/contract_list_ass") {
     $("#id_add_contract").hide();
     }
    */

    $(".opt-get_package_time").on("click",function(){
        var opt_data        = $(this).get_opt_data();
        var get_packge_time = opt_data.get_packge_time;
        var nick            = opt_data.stu_nick;
        var msg             = "";
        var time            = 0;
        var get_packge_flag = 0;

        if (get_packge_time =="无") {
            msg="设置:" +nick+ ",已领大礼包?" ;
            get_packge_flag=1;
        }else{
            msg="设置:" +nick+ ",未领大礼包?" ;
            get_packge_flag=0;
        }

        BootstrapDialog.confirm( msg, function(val){
            if ( val) {
                $.do_ajax("/ss_deal/set_order_get_package_time",{
                    "orderid" : opt_data.orderid,
                    "get_packge_flag" : get_packge_flag,
                });
            }
        });
    });
    $(".opt-from-data").on("click",function(){
        var opt_data     = $(this).get_opt_data();
        var $upload_div  = $("<div > <button id=\"id_upload_from_url\" > 上传</button>  <a href=\"\" target=\"_blank\">查看 </a>   </div>");
        var $from_key    = $("<textarea></textarea>");
        var $upload_btn  = $upload_div.find("button") ;
        var $upload_link = $upload_div.find("a") ;
        var arr=[
            [ "订单号"  , $from_key ]
        ];
        $upload_link.attr('href',opt_data.from_url);
        $from_key.val(opt_data.from_key);
        arr.push(["上传信息", $upload_div ]);
        $.show_key_value_table("续费课时包", arr,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                if(g_account == opt_data.sys_operator){
                    $.do_ajax("/user_manage_new/update_from_data",{
                        "orderid"  : opt_data.orderid,
                        "from_url" : $upload_link.attr('href'),
                        "from_key" : $from_key.val(),
                    })
                }else{
                    alert('当前用户无法保存!');
                }
            }
        },function(){
            $.custom_upload_file(
                "id_upload_from_url" ,
                true,function( up, info, file ){
                    var res = $.parseJSON(info);
                    var url=res.key;
                    $.do_ajax("/common_new/get_qiniu_download",{
                        "file_url" :res.key ,
                        "public_flag" :1,
                    }, function(resp){
                        $upload_link.attr("href", resp.url);
                    })
                },null,
                ["png","jpg","zip","rar"] );
        });
    });




    $(".opt-split-order").on("click",function(){
        var data                   = $(this).get_opt_data();
        var id_userid              = $("<input/>");
        var id_parent_lesson_count = $("<input/>");
        var id_lesson_count        = $("<input/>");
        var id_grade               = $("<select/>");
        var id_competition_flag    = $("<select/>");

        var arr = [
            ["转移至",id_userid],
            ["扣除课时",id_parent_lesson_count],
            ["转移课时",id_lesson_count],
            ["年级",id_grade],
            ["竞赛合同",id_competition_flag],
        ];

        Enum_map.append_option_list("grade",id_grade,true);
        Enum_map.append_option_list("boolean",id_competition_flag,true);

        $.show_key_value_table("分离合同",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/user_manage_new/split_order",{
                    "orderid"      : data.orderid,
                    //"price"        : id_price.val(),
                    //"lesson_total" : id_lesson_total.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        },function(){
            $.admin_select_user(id_userid,"student");
        });
    });

    $('.opt-build-contrat').on("click",function(){
        var opt_data        = $(this).get_opt_data();
        var addressee       = $("<input/>");
        var receive_phone   = $('<input/>');
        var receive_addr    = $('<input/>');
        var lesson_duration = $('<input/>');
        var lesson_weeks    = $('<input/>');
        var student_name    = $('<a/>');
        var app_time        = $('<a/>');
        var $parent_name = $('<input/>');
        var remark          = $('<textarea></textarea>');

        $.do_ajax("/ss_deal/get_contract_info",{
            "orderid":opt_data.orderid
        },function(result){
            var data = result.data;
            var arr=[
                [ "学员姓名"  , student_name ],
                [ "<font color=red>家长姓名,用于生成合同</font>"  , $parent_name ],
                [ "收件人"  , addressee ],
                [ "收件人电话"  , receive_phone],
                [ "收件人地址"  , receive_addr],
                [ "申请时间"  , app_time],
            ];

            app_time.text(data.app_time_str);
            student_name.text(opt_data.stu_nick);
            addressee.val(data.nick);
            receive_phone.val(data.phone);
            receive_addr.val(data.address);
            if(data.lesson_duration){
                lesson_duration.val(data.lesson_duration);
            }else{
                lesson_duration.val(40);
            }
            $parent_name.val(opt_data.parent_nick);

            if(data.lesson_weeks){
                lesson_weeks.val(data.lesson_weeks);
            }else{
                lesson_weeks.val(3);
            }

            var checkMobile = function(){
                var sMobile = receive_phone.val();
                if(!(/^1[3|4|5|7|8][0-9]\d{4,8}$/.test(sMobile))){
                    alert("不是完整的11位手机号或者正确的手机号前七位");
                    return true;
                }
            }

            $.show_key_value_table("订单信息", arr,[
            //     {
            //     label: '下载电子版pdf',
            //     cssClass: 'btn-primary',
            //     action: function(dialog) {
            //         $.wopen( opt_data.pdf_url.replace(".pdf", "_gz.pdf") );
            //     }
            // },
                {
                label: '下载打印版pdf',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    $.wopen( opt_data.pdf_url );
                }
            },{
                label: '生成合同pdf ',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    $.do_ajax("/ajax_deal2/gen_order_pdf",{
                        "orderid" :opt_data.orderid,
                        "parent_name": $parent_name.val(),
                    } );
                    alert("请等待５秒...");
                }
            },
            //                                         {
            //     label: '提交财务打印 ',
            //     cssClass: 'btn-primary',
            //     action: function(dialog) {
            //         var check_flag = [addressee,receive_phone,receive_addr,lesson_weeks,lesson_duration];

            //         for(var ii in check_flag){
            //             if(!check_flag[ii].val()){
            //                 alert((check_flag[ii].parent().prev().text()+'不能为空!'));
            //                 return ;
            //             }
            //         }

            //         if(checkMobile()){
            //             return;
            //         }

            //         $.do_ajax("/ss_deal/build_contract",{
            //             "addressee"     : addressee.val(),
            //             "receive_phone" : receive_phone.val(),
            //             "receive_addr"  : receive_addr.val(),
            //             "lesson_weeks"  : lesson_weeks.val(),
            //             "lesson_duration" : lesson_duration.val(),
            //             "orderid"         : opt_data.orderid,
            //             "parent_name": $parent_name.val(),
            //             "is_submit"       : 1
            //         })
            //     }
            // },
                                                    {
                label: '保存',
                cssClass: 'btn-warning',
                action: function(dialog) {

                    if(checkMobile()){
                        return;
                    }

                    $.do_ajax("/ss_deal/build_contract",{
                        "addressee"     : addressee.val(),
                        "receive_phone" : receive_phone.val(),
                        "receive_addr"  : receive_addr.val(),
                        "lesson_weeks"  : lesson_weeks.val(),
                        "lesson_duration" : lesson_duration.val(),
                        "orderid"         : opt_data.orderid,
                        "parent_name": $parent_name.val(),
                        "is_submit"       : 0

                    })
                }
            }],function(){

            });
        });


    });



    $('.opt-mail-contrat').on("click",function(){
        var opt_data        = $(this).get_opt_data();
        var main_send_admin  = $('<input/>');
        var mail_send_time   = $('<input/>');
        var mail_code        = $('<input/>');
        var mail_code_url    = $('<button id="id_upload_mail_photo">上传图片</button>');

        var show_mail_url    = $('<input/>');
        var is_send_flag     = $('<select/>');

        Enum_map.append_option_list( "boolean", is_send_flag ,true);

        mail_send_time.datetimepicker({
            lang:'ch',
            timepicker:true,
            format:'Y-m-d H:i'
        });

        $.do_ajax("/ss_deal/get_contract_mail",{
            'orderid' : opt_data.orderid
        },function(result){
            var data = result.data;
            var arr=[
                [ "发件人"  , main_send_admin ],
                [ "发件时间"  , mail_send_time],
                [ "运单号"  , mail_code],
                [ "上传运单号照片"  , mail_code_url],
                [ "生成运单号照片链接"  , show_mail_url],
                [ "是否已邮寄", is_send_flag],
            ];

            main_send_admin.val(data.main_send_admin);
            mail_send_time.val(data.mail_send_time_str);
            mail_code.val(data.mail_code);
            show_mail_url.val(data.mail_code_url);
            is_send_flag.val(data.is_send_flag);


            $.show_key_value_table("填写合同信息", arr,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.do_ajax("/ss_deal/write_contract_mail",{
                        "main_send_admin"  : main_send_admin.val(),
                        "mail_send_time"   : mail_send_time.val(),
                        "mail_code"        : mail_code.val(),
                        'mail_code_url'    : show_mail_url.val(),
                        "is_send_flag"     : is_send_flag.val(),
                        "orderid"          : opt_data.orderid,
                    })
                }
            },function(){
                $.custom_upload_file('id_upload_mail_photo',true,function (up, info, file) {
                    var res = $.parseJSON(info);
                    $.ajax({
                        url: '/ss_deal2/set_mail_photo',
                        type: 'POST',
                        data: {
                            'mail_url'  : res.key,
                            'orderid'   : opt_data.orderid
                        },
                        dataType: 'json',
                        success: function(data) {
                            var mail_url = data.data;
                            show_mail_url.val(mail_url);
                        }
                    });

                }, null,["png", "jpg",'jpeg','bmp','gif']);
            });
        }
                 );

    });

    $(".opt-merge_order").on("click",function(){
        var id_orderid = $("<input/>");
        var data = $(this).get_opt_data();

        var arr = [
            ["目标合同",id_orderid],
        ];

        $.show_key_value_table("合并合同",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/user_manage_new/merge_order",{
                    "orderid"      : data.orderid,
                    "orderid_goal" : id_orderid.val(),
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

    $(".opt-price_desc").on("click",function(){
        var opt_data=$(this).get_opt_data();

        $.do_ajax( "/ajax_deal2/get_order_activity_list",{
            "orderid" : opt_data.orderid
        },function(resp){
            var list= resp.list;
            var data=opt_data.order_price_desc;
            if( list.length >0 )  {
                data=list;
            }
            $.do_ajax( "/ajax_deal2/get_order_desc_html_str",{
                "str" : JSON.stringify(list )
            }, function (resp){
                BootstrapDialog.alert($(resp.html_str));
            } );


        } );

    });



    var show_add_contract_new_jack=function( require_id ,contract_type , data ,contract_from_type){
        //id_order_origin

        var html_node=$.dlg_need_html_by_id( "id_dlg_add_contract_new_jack");
        //原价
        var $discount_price       = html_node.find(".field-discount_price");
        var $order_promotion_desc = html_node.find(".field-order_promotion_desc");
        var $div_spec             = html_node.find( ".div-spec");
        var $order_require_flag   = html_node.find(".field-order_require_flag");

        var $has_share_activity_flag    = html_node.find(".field-has_share_activity");
        var $order_desc_list = html_node.find(".field-order_desc_list");

        var $nick    = html_node.find(".field-nick");
        var $grade   = html_node.find(".field-grade");
        var $phone   = html_node.find(".field-phone");
        var $subject = html_node.find(".field-subject");
        var $lesson_count     = html_node.find(".field-lesson_count");
        var $competition_flag = html_node.find(".field-competition_flag");
        var $pre_money        = html_node.find(".field-pre-money");

        var $order_promotion_type = html_node.find(".field-order_promotion_type");
        var $promotion_spec_present_lesson= html_node.find(".field-promotion_spec_present_lesson");
        var $promotion_spec_discount_price= html_node.find(".field-promotion_spec_discount_price");
        var $discount_reason= html_node.find(".field-discount_reason");
        var $receipt_title= html_node.find(".field-receipt_title");
        var $order_partition_flag= html_node.find(".field-order_partition_flag");
        var $add_child_order_list= html_node.find("#id_add_child_order_list");



        Enum_map.append_option_list( "boolean", $order_require_flag ,true);
        Enum_map.append_option_list( "boolean", $has_share_activity_flag,true);
        Enum_map.append_option_list( "grade", $grade,true);
        Enum_map.append_option_list( "subject", $subject,true);
        Enum_map.append_option_list( "boolean", $competition_flag,true);
        Enum_map.append_option_list( "order_promotion_type", $order_promotion_type,true);


        $nick.val(data.nick);
        $grade.val(data.grade);
        $phone.val(data.phone);
        $subject.val(data.subject);


        $order_require_flag.val(0);

        var opt_spec=function(){
            if ($order_require_flag.val()==1) {
                $div_spec.show();
            }else{
                $div_spec.hide();
            }
        };

        $order_require_flag.on("change", opt_spec);
        $order_promotion_type.val(2); //打折
        opt_spec();

        $order_partition_flag.on("change",function(){
            if($order_partition_flag.val() ==1){
                $add_child_order_list.show();
            }else{
                $add_child_order_list.hide();
            }
        });


        var reload_present_info = function() {
            var order_promotion_type=  $order_promotion_type.val();


            $.do_ajax("/ss_deal/get_order_price_info",{
                grade: data.grade,
                userid : data.userid,
                competition_flag:$competition_flag.val(),
                lesson_count:$lesson_count.val()*100,
                order_promotion_type: order_promotion_type,
                contract_type: contract_type,
                require_id :  require_id
            },function(resp){
                var data=resp.data;
                $discount_price.val(data.price );
                $promotion_spec_present_lesson.val( data.present_lesson_count );
                $promotion_spec_discount_price.val( data.discount_price );
                $.do_ajax( "/ajax_deal2/get_order_desc_html_str",{
                    "str" : JSON.stringify(data.desc_list)
                }, function (resp){
                    $order_desc_list.html(resp.html_str);
                } );

                if (order_promotion_type==1) {
                    $order_promotion_desc.val("赠送:"+ data.present_lesson_count +"课时" );
                } else if (order_promotion_type==2) {
                    if (data.discount==100) {
                        $order_promotion_desc.val("无折扣" );
                    }else{
                        $order_promotion_desc.val("打折:"+ data.discount_price +"元("+data.discount_count + "折)" );
                    }
                }else{
                    $order_promotion_desc.val("");
                }
            });
        };
        $competition_flag.set_input_change_event(reload_present_info);
        $lesson_count.set_input_change_event(reload_present_info);
        $order_promotion_type. set_input_change_event(reload_present_info);

        $nick.set_input_readonly(true);
        $phone.set_input_readonly(true);
        $grade.set_input_readonly(true);
        if ($subject.val() ) {
            $subject.set_input_readonly(true);
        }
        $discount_price.set_input_readonly(true);
        $order_promotion_desc.set_input_readonly(true);

        $add_child_order_list.data("v" , "[]");
        $add_child_order_list.on("click",function(){
            var v=$(this).data("v");
            if(!v) {
                v="[]";
            }
            var data_list=JSON.parse(v);

            $(this).admin_select_dlg_edit({
                onAdd:function( call_func ) {
                    var id_child_order_type= $("<select> "+
                                   "<option value=1>首付款</option> "+
                                   "<option value=2>其他</option> "+
                                   "</select>");
                    var id_child_order_money=$("<input/>");

                    var arr=[
                        ["类型", id_child_order_type],
                        ["金额", id_child_order_money]
                    ];
                    $.show_key_value_table("增加", arr, {
                        label: '确认',
                        cssClass: 'btn-warning',
                        action: function (dialog) {
                            call_func({
                                "child_order_type" :  id_child_order_type.val() ,
                                "child_order_money" : id_child_order_money.val()*100,
                                "child_order_type_str" :  id_child_order_type.find("option:selected").text()
                            });
                            dialog.close();
                        }
                    });
                },
                sort_func : function(a,b){
                },
                'field_list' :[
                    {
                        title:"类型",
                        render:function(val,item) {
                            return item["child_order_type_str"];
                        }
                    },{

                        title:"金额",
                        //width :50,
                        render:function(val,item) {
                            return item["child_order_money"]/100  ;
                        }
                    }
                ] ,
                data_list: data_list,
                onChange:function( data_list, dialog)  {
                    $add_child_order_list.data("v" , JSON.stringify(data_list));
                }
            });
        }) ;

        BootstrapDialog.show({
            title    : '创建合同['+ Enum_map.get_desc("contract_type",contract_type) +']' ,
            message  : html_node,
            closable : true,
            buttons: [{
                label  : '返回',
                action : function(dialog) {
                    dialog.close();
                }
            },{
                label  : '确认',
                action : function(dialog) {
                    var promotion_spec_discount_int = parseInt($promotion_spec_discount_price.val())*100;
                    var child_list=JSON.parse($add_child_order_list.data("v"));
                    var child_money=0;
                    $.each(child_list,function(i,item){
                        child_money = child_money+item["child_order_money"];
                    });

                    if(promotion_spec_discount_int != child_money && $order_partition_flag.val() ==1){
                        alert("子合同总额不等于订单金额!");
                        return;
                    }
                    $.do_ajax("/ss_deal/seller_add_contract_new",{
                        require_id                    : require_id,
                        contract_type                 : contract_type,
                        contract_from_type            : contract_from_type,
                        competition_flag              : $competition_flag.val(),
                        lesson_total                  : $lesson_count.val()*100,
                        discount_reason               : $discount_reason.val(),
                        title                         : $receipt_title.val(),
                        order_require_flag            : $order_require_flag.val(),
                        userid                        : data.userid,
                        pre_money                     : $pre_money.val(),
                        grade                         : data.grade,
                        subject                       : $subject.val(),
                        origin                        : data.origin,
                        order_promotion_type          : $order_promotion_type.val(),
                        promotion_spec_discount       : $promotion_spec_discount_price.val()*100,
                        promotion_spec_present_lesson : $promotion_spec_present_lesson.val()*100,
                        has_share_activity_flag       : $has_share_activity_flag.val(),
                        order_partition_flag          : $order_partition_flag.val(),
                        child_order_info              : $add_child_order_list.data("v")
                    });
                }
            }]
        });
    };


    $(".opt-order-partition").on("click",function(){
        var data = $(this).get_opt_data();
        /*if(data.contract_status>0){
            alert("已付款合同不能拆分");
            return;
            }*/
        var can_period_flag= data.can_period_flag;
        var title = "编辑子合同";
        var html_node = $("<div id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>id</td><td>类型</td><td>金额</td><td>分期期数</td><td>付款</td><td>操作</td></tr></table></div>");
        $.do_ajax("/ss_deal/get_child_order_list",{
            orderid: data.orderid,
        },function(resp){
            var data_list = resp.data;
            if(resp.ret != 0){
                alert(resp.info);
                return;
            }
            $.each(data_list,function(i,item){
                if(item["child_order_type"]==0){
                    html_node.find("table").append("<tr><td>"+item['child_orderid']+"</td><td>"+item['child_order_type_str']+"</td><td>"+item['price']/100+"</td><td>"+item['period_num_info']+"</td><td>"+item['pay_status_str']+"</td><td><a href=\"javascript:;\" class=\"order_partition\"  data-status=\""+item["pay_status"]+"\" data-orderid=\""+item["parent_orderid"]+"\" data-child_orderid=\""+item['child_orderid']+"\">拆分</a>&nbsp&nbsp&nbsp&nbsp<a href=\"javascript:;\" class=\"order_partition_rebuild\"  data-status=\""+item["pay_status"]+"\" data-orderid=\""+item["parent_orderid"]+"\" data-child_orderid=\""+item['child_orderid']+"\">重置</a></td></tr>");
                }else{
                    html_node.find("table").append("<tr><td>"+item['child_orderid']+"</td><td>"+item['child_order_type_str']+"</td><td>"+item['price']/100+"</td><td>"+item['period_num_info']+"</td><td>"+item['pay_status_str']+"</td><td><a href=\"javascript:;\" class=\"update_child_order_info\" data-status=\""+item["pay_status"]+"\" data-orderid=\""+item["parent_orderid"]+"\" data-type=\""+item["child_order_type"]+"\" data-price=\""+item["price"]+"\" data-pnum=\""+item["period_num"]+"\" data-child_orderid=\""+item['child_orderid']+"\">修改</a>&nbsp&nbsp&nbsp&nbsp<a href=\"javascript:;\" class=\"delete_child_order_info\" data-status=\""+item["pay_status"]+"\" data-orderid=\""+item["parent_orderid"]+"\" data-child_orderid=\""+item['child_orderid']+"\">删除</a></td></tr>");
                }

            });
            html_node.find("table").find(".order_partition").each(function(){
                $(this).on("click",function(){
                    var parent_orderid = $(this).data("orderid");

                    var child_orderid = $(this).data("child_orderid");
                    var status = $(this).data("status");
                    if(status >0 && parent_orderid != 24041 && parent_orderid != 23800 && parent_orderid != 25175){
                        alert("已付款,不能拆分!");
                        return;
                    }

                    if(can_period_flag==1){
                        var id_child_order_type= $("<select> "+
                                                   "<option value=1>首付款</option> "+
                                                   "<option value=2>分期</option> "+
                                                   "<option value=3>其他</option> "+
                                                   "</select>");

                    }else{
                        var id_child_order_type= $("<select> "+
                                                   "<option value=1>首付款</option> "+
                                                   "<option value=3>其他</option> "+
                                                   "</select>");

                    }
                    var id_period_num= $("<select> "+
                                         "<option value=3>3期</option> "+
                                         "<option value=6>6期</option> "+
                                         "<option value=12>12期</option> "+
                                         "</select>");

                    var id_child_order_money=$("<input/>");


                    var arr=[
                        ["类型", id_child_order_type],
                        ["分期期数", id_period_num],
                        ["金额", id_child_order_money]
                    ];

                    id_child_order_type.on("change",function(){
                        if(id_child_order_type.val() ==2){
                            id_period_num.parent().parent().show();
                        }else{
                            id_period_num.parent().parent().hide();
                        }
                    });
                    $.show_key_value_table("增加子合同", arr, {
                        label: '确认',
                        cssClass: 'btn-warning',
                        action: function (dialog) {
                            $.do_ajax( '/ss_deal/add_child_order_info', {
                                "parent_orderid" : parent_orderid,
                                "child_orderid" : child_orderid,
                                "child_order_type"     : id_child_order_type.val(),
                                "period_num"           : id_period_num.val(),
                                "price"                : id_child_order_money.val()*100
                            });
                        }
                    },function(){
                        if(id_child_order_type.val() ==2){
                            id_period_num.parent().parent().show();
                        }else{
                            id_period_num.parent().parent().hide();
                        }

                    });


                });

            });

            html_node.find("table").find(".order_partition_rebuild").each(function(){
                $(this).on("click",function(){
                    var parent_orderid = $(this).data("orderid");

                    var child_orderid = $(this).data("child_orderid");
                    var status = $(this).data("status");
                    if(status >0){
                        alert("已付款,不能重置!");
                        return;
                    }

                    BootstrapDialog.confirm("确定要重置？", function(val){
                        if (val) {
                            $.do_ajax( '/ss_deal/rebulid_child_order_info', {
                                "parent_orderid" : parent_orderid,
                            });

                        }
                    });

                });

            });


            html_node.find("table").find(".update_child_order_info").each(function(){
                $(this).on("click",function(){
                    var parent_orderid = $(this).data("orderid");

                    var child_orderid = $(this).data("child_orderid");
                    var status = $(this).data("status");
                    var child_oeder_type = $(this).data("type");
                    var child_oeder_mpney = $(this).data("price");
                    var period_num = $(this).data("pnum");

                    if(status >0){
                        alert("已付款,不能修改!");
                        return;
                    }
                    if(can_period_flag==1){
                        var id_child_order_type= $("<select> "+
                                                   "<option value=1>首付款</option> "+
                                                   "<option value=2>分期</option> "+
                                                   "<option value=3>其他</option> "+
                                                   "</select>");

                    }else{
                        var id_child_order_type= $("<select> "+
                                                   "<option value=1>首付款</option> "+
                                                   "<option value=3>其他</option> "+
                                                   "</select>");

                    }

                    var id_period_num= $("<select> "+
                                         "<option value=3>3期</option> "+
                                         "<option value=6>6期</option> "+
                                         "<option value=12>12期</option> "+
                                         "</select>");


                    var id_child_order_money=$("<input/>");
                     id_child_order_type.val(child_oeder_type);
                    id_period_num.val(period_num);
                    id_child_order_money.val(child_oeder_mpney/100);

                    var arr=[
                        ["类型", id_child_order_type],
                        ["分期期数", id_period_num],
                        ["金额", id_child_order_money]
                    ];
                    id_child_order_type.on("change",function(){
                        if(id_child_order_type.val() ==2){
                            id_period_num.parent().parent().show();
                        }else{
                            id_period_num.parent().parent().hide();
                        }
                    });

                    $.show_key_value_table("修改子合同", arr, {
                        label: '确认',
                        cssClass: 'btn-warning',
                        action: function (dialog) {
                            $.do_ajax( '/ss_deal/update_child_order_info', {
                                "parent_orderid" : parent_orderid,
                                "child_orderid" : child_orderid,
                                "child_order_type"     : id_child_order_type.val(),
                                "period_num"           : id_period_num.val(),
                                "price"                : id_child_order_money.val()*100
                            });
                        }
                    },function(){
                        if(id_child_order_type.val() ==2){
                            id_period_num.parent().parent().show();
                        }else{
                            id_period_num.parent().parent().hide();
                        }

                    });


                });

            });


            html_node.find("table").find(".delete_child_order_info").each(function(){
                $(this).on("click",function(){
                    var parent_orderid = $(this).data("orderid");

                    var child_orderid = $(this).data("child_orderid");
                    var status = $(this).data("status");
                    if(status >0){
                        alert("已付款,不能删除!");
                        return;
                    }

                    BootstrapDialog.confirm("确定要删除？", function(val){
                        if (val) {
                            $.do_ajax( '/ss_deal/delete_child_order_info', {
                                "parent_orderid" : parent_orderid,
                                "child_orderid" : child_orderid,
                            });

                        }
                    });
                });

            });

            var dlg=BootstrapDialog.show({
                title:title,
                message :  html_node   ,
                closable: true,
                buttons:[{
                    label: '返回',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();

                    }
                }],
                onshown:function(){

                }

            });

            dlg.getModalDialog().css("width","900px");

        });

    });

    $(".opt-update-parent-name").on("click",function(){
        var data = $(this).get_opt_data();
        var userid= data.userid;
        var id_parent_name = $("<input/>");

        var arr = [
            ['名字',id_parent_name]
        ];
        id_parent_name.val(data.parent_nick);


        $.show_key_value_table("修改家长姓名", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax('/ajax_deal2/update_parent_name',{
                    'userid'      : userid,
                    "parent_name" : id_parent_name.val()
                });
            }
        });
    });

    $("#id_order_activity_type").on("click",function(){
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/seller_student2/get_all_activity",
            //其他参数
            "args_ex" : {
                //type  :  "teacher"
            },
            select_primary_field   : "id",   //要拿出来的值
            select_display         : "id",
            select_no_select_value : 0,
            select_no_select_title : "[未设置]",
            width:1000,
            //字段列表
            'field_list' :[
                {
                title:"id",
                field_name:"id"
            },
                {
                title:"标题",
                width:400,
                render:function(val,item) {
                    return item.title;
                }
            },
                {
                title:"是否开启",
                width:80,
                render:function(val,item) {
                    return item.open_flag_str;
                }
            },
                {
                title:"优惠力度",
                width:80,
                render:function(val,item) {
                    return item.power_value;
                }
            }

            ] ,
            //查询列表
            filter_list:[[{
                size_class: "col-md-2" ,
                title :"开启",
                type  : "select" ,
                'arg_name' :  "open_flag"  ,
                select_option_list: [{
                    value : -1 ,
                    text :  "全部"
                },{
                    value :  0 ,
                    text :  "关闭"
                },{
                    value :  1 ,
                    text :  "正式开启"
                },{
                    value :  2 ,
                    text :  "测试开启"
                }]
            },{
                size_class : "col-md-4" ,
                title      : "活动ID",
                'arg_name' : "id"  ,
                type       : "input"
            },{
                size_class : "col-md-6" ,
                title      : "标题",
                'arg_name' : "title"  ,
                type       : "input"
            }]],
            "auto_close"       : true,
            "onChange"         : function(require_id,row_data){
                $("#id_order_activity_type").val(require_id);
                load_data();
            },
            "onLoadData"       : null
        });
    });

    if ($.get_action_str() == "contract_list_seller" ) {
        $("#id_order_activity_type").parent().parent().data( "always_hide",1);
    }

    $(".show_phone").on("click",function(){
        var phone = $(this).data("phone");
        BootstrapDialog.alert(phone);
    });

    if(g_account=="wenbin" || g_account=="龚隽"){
        window.download_show();
    }
});
