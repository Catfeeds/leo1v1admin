function load_data(){

    reload_self_page({
        contract_type: $("#id_contract_type").val(),
        contract_status: $("#id_contract_status").val(),
        studentid: $("#id_studentid").val(),
        start_time: $("#id_start_time").val(),
        end_time: $("#id_end_time").val(),
        test_user: $("#id_test_user").val(),
        has_money: $("#id_has_money").val()
        
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
    
    Enum_map.append_option_list( "contract_type", $("#id_contract_type"));
    Enum_map.append_option_list( "test_user", $("#id_test_user"));


	//init  input data
	$("#id_grade").val(g_args.grade);
	$("#id_start_time").val(g_args.start_time);
	$("#id_end_time").val(g_args.end_time);
	$("#id_has_money").val(g_args.has_money);
	$("#id_contract_type").val(g_args.contract_type);
	$("#id_contract_status").val(g_args.contract_status);
    $("#id_test_user").val(g_args.test_user);
    $("#id_studentid").val(g_args.studentid);


    $("#id_studentid").admin_select_user({
        "type"   : "student",
        "onChange": function(){
            load_data(  );
        }
    });
    
    var show_select_lesson_account_dlg=function(userid) {
        var nick_div=$("<div/>");
        do_ajax_get_nick("student",userid,function(id,nick){
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
        
        show_key_value_table("续费课时包", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                //lesson_account_continue_add
                do_ajax("/user_deal/add_contract_for_lesson_account",{
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
        admin_select_user( $(this), "student",
                function( id ){
                    show_select_lesson_account_dlg(id);
                },true);
    });


    //display
    $.each($(".opt-back-money"),function(i,item) {
        var from_type=$(item).get_opt_data("from_type");
        if (from_type==1) { //
            $(item).hide();
        }
        
    });

    //display
    $.each($(".opt-next"),function(i,item) {
        var from_type=$(item).get_opt_data("from_type");
        var contract_type=$(item).get_opt_data("contract_type");
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


	//时间控件
	$('#id_start_time').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
        onChangeDateTime :function(){
            load_data();
		}
	});
	
	$('#id_end_time').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
        onChangeDateTime :function(){
            load_data(
			);
		}
	});//时间控件-over
	




	//点击进入个人主页
	$('.opt-user').on('click',function(){
		var userid = $(this).parent().data("userid");
		var nick   = $(this).parent().data("stu_nick");
		//$(this).attr('href','/stu_manage?sid = '+userid+'&nick='+nick+"&"  );
        window.open('/stu_manage?sid='+ userid+"&return_url="+ encodeURIComponent(window.location.href)) ;
        
	});

	$('.opt-back-money').click(function(){

		var orderid=$(this).parent().data("orderid");
        $.ajax({
			type     :"post",
			url      :"/user_manage/contract_get_info",
			dataType :"json",
			data     :{'orderid': orderid},
			success  : function(result){
                var data=result.data;
                var html_node=dlg_need_html_by_id( "id_dlg_back_money");
                html_node.find("#id_conid2").html(data.contractid);
                html_node.find("#id_stu_name2").html(data.stu_name);
                html_node.find("#id_grade2").html(data.grade);
                html_node.find("#id_parent_name2").html(data.parent_name);
                html_node.find("#id_parent_phone2").html(data.parent_phone);
                html_node.find("#id_lesson_total2").html(data.lesson_total + '课次');
                html_node.find("#id_lesson_reduce2").html(data.lesson_left + '课次');
                html_node.find("#id_need_receipt2").html(data.need_receipt);

                var refund = data.price;
                html_node.find("#id_should_refund_value").html(refund + '元');
                html_node.find("#id_refund").val(refund);

                BootstrapDialog.show({
                    title: '退费',
                    message : html_node    ,
                    closable: true, 
                    buttons: [{
                        label: '返回',
                        action: function(dialog) {
                            dialog.close();
                        }
                    },{
                        label: '确认',
                        action: function(dialog) {
                            var real_refund = 100*parseFloat(html_node.find("#id_refund").val());
                            var refund_reason = html_node.find("#id_refund_reson").val();
                            $.ajax({
			                    type     :"post",
			                    url      :"/user_manage/add_refund_apply",
			                    dataType :"json",
			                    data     :{'orderid': orderid, "real_refund":real_refund,"refund_reason":refund_reason},
			                    success  : function(result){
                                    if(result['ret'] != 0){
                                        alert(result.info);
                                    }else{
                                        window.location.reload();    
                                    }
                                }
		                    });
                        }
                    }]
                });
			}

		});
	});


	$(".c_sel").on("change",function(){
        load_data();
	});

    $(".opt-change-cash").on("click", function(){
        alert($.trim(this));
        if(g_power_list[$.trim(this)] == 16 ){
            $(this).hide();
        }else{
            $(this).show();
        }
        var contractid=  $(this).parent().data('contractid');
        var orderid =  $(this).parent().data('orderid');
        var userid=  $(this).parent().data('userid');
        var html_node=$("<span> <input type=input style=\"width:80px\"></input>元</span> ");

        var dlg=BootstrapDialog.show({
            title: '更改金额',
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



    $(".opt-change-money").on("click", function(){
        var contractid=  $(this).parent().data('contractid');
        var orderid =  $(this).parent().data('orderid');
        var userid=  $(this).parent().data('userid');
        var html_node=$("<span> <input type=input style=\"width:80px\"> </input>元</span> ");

        var dlg=BootstrapDialog.show({
            title: '更改金额',
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
			            url      :"/user_manage/set_contract_money",
			            dataType :"json",
			            data     :{
                            'orderid': orderid,
                            'userid':userid,
                            'price':   parseFloat(html_node.find("input").val())*100
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
    $(".opt-del").on("click", function(){
        var orderid =  $(this).parent().data('orderid');
        var userid=  $(this).parent().data('userid');
        
        var name=$(this).closest("tr").find(".stu_nick").text();
        var price=$(this).closest("tr").find(".price").text();

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





    $(".opt-change-state").on("click", function(){
        var contractid = $(this).parent().data('contractid');
        var status     = $(this).parent().data('contract_status');
        var orderid    = $(this).parent().data('orderid');
        var money      = $(this).parent().data('price');
        var userid     = $(this).parent().data('userid');
        var html_node  = dlg_need_html_by_id( "id_dlg_change_state");
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
        var html_node=dlg_need_html_by_id( "id_dlg_query_user");
        var userid=0;
        var grade=0;
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
        var html_node=dlg_need_html_by_id( "id_dlg_add_contract");
        html_node.find("#id_stu_grade").val( grade);

        html_node.find("#id_user_nick").val(stu_nick );
        html_node.find("#id_contact_phone").val(parent_phone);
        html_node.find("#id_parent_nick").val(parent_nick);
        if (!parent_nick){
            html_node.find("#id_user_nick").val("");
            html_node.find("#id_contact_phone").val(stu_nick);
        }

        html_node.find("#id_user_addr").val(address );

        html_node.find("#id_small_class" ).admin_select_course({
            "course_type": 3001 
        });

        html_node.find("#id_open_class" ).admin_select_course({
            "course_type": 1001 
        });

        html_node.find('#id_con_type').on("change",function(){
	        var val = $(this).val();
            html_node.find(".test-listen").hide();
            html_node.find(".opt-con-type-div").hide();

            
            
	        if(val == 1){
                html_node.find(".test-listen").show();
                html_node.find(".count_block").show();
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
                    var config_courseid  = 0;
                    
                    if(contract_type == -1){
                        alert("请选择合同类型");
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


    

    $("#id_query_nick").on("keydown",function(e){
        if(e.which==13 ){
            $(".opt_search_contract_by_username").click();
        }
    });

    $("#id_query_phone2").on("keydown",function(e){
        if(e.which==13 ){
            $(".opt_search_contract_by_phone").click();
        }
    });



    
    $(" .opt-change-default_lesson_count").on("click",function(){
		var nick   = $(this).closest("tr") .find(".stu_nick").text();
		var contract_starttime = $(this).closest("tr") .find(".contract_starttime").text();
        var orderid =$(this).get_opt_data("orderid");
        
        var $lesson_count=$("<input/>");
        $lesson_count.val($(this).get_opt_data("default_lesson_count")/100 );
        
        var arr=[
            ["姓名" , nick ],
            ["合同时间" , contract_starttime],
            ["课时数" , $lesson_count],
        ];
        show_key_value_table("设置每次课课时数", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax("/user_deal/course_set_default_lesson_count",{
                    "orderid" :orderid,
                    "default_lesson_count" : $lesson_count.val()*100
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
        do_ajax("/user_manage/get_self_contract",{
            "userid"     : userid,
            "contractid" : contractid
        },function(result){
            var id_contract = $("<select/>");
            var id_stu_from_type  = $("<select/>");

            Enum_map.append_option_list( "contract_type", id_contract);
            Enum_map.append_option_list( "contract_from_type", id_stu_from_type);
            id_contract.val(contract_type);
            id_stu_from_type.val(stu_from_type);

            var arr = [
                ['合同类型',id_contract],
                ['1v1详细类型',id_stu_from_type ],
            ];
            id_contract.val(result.data);


            show_key_value_table("修改合同类型", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    var contract_type = id_contract.val();

                    do_ajax('/user_deal/update_course_type',{
                        'userid'      : userid,
                        'course_type' : contract_type,
                        'stu_from_type' : id_stu_from_type.val(),
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

        do_ajax ( "/user_manage/get_contract_starttime", {
            "contractid" : contractid,
            "orderid"    : orderid
        },function(result){
            var data = result.data;
            if ( data >0 ) {
                contract_starttime.val( DateFormat ( data, "yyyy-MM-dd" ));
            }

            show_key_value_table("修改合同生效时间", arr ,{
                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog){
                    do_ajax('/user_manage/set_contract_starttime', {
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



});

