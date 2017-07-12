$(function(){
    $(".refund_change").on("change",function(){
        var contract_type = $("#id_contract_type").val();
        var grade = $("#id_grade").val();
        var start_time = $("#datetimepicker4").val();
        var end_time = $("#datetimepicker5").val();
        load_data(contract_type, grade, start_time, end_time);
    });

    function load_data(contract_type, grade, start_time, end_time){
        url = "/user_manage/refund_record?contract_type="+contract_type+"&grade="+grade+"&start_time="+start_time+"&end_time="+end_time;
        window.location.href = url;
    }

    

	//时间控件
	$('#datetimepicker4').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y/m/d',
        onChangeDateTime :function(){
            load_data(
                    -1,
                    -1,
				$("#datetimepicker4").val(),
                $("#datetimepicker5").val()
			);
        }
	});
	
	$('#datetimepicker5').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y/m/d',
        onChangeDateTime :function(){
            load_data(
                    -1,
                    -1,
				$("#datetimepicker4").val(),
                $("#datetimepicker5").val()
			);
		}
	});//时间控件-over
		
    $(".stu_search").on("click", function(){
        var nick = $("#id_nick").val();
        var phone = $("#id_phone").val();
        var contractid = $("#id_contractid").val();

        //if(nick == "" && phone == "" && contractid == ""){
        //    alert("姓名，电话，合同编号不能全部为空！");
        //}

        url = "/user_manage/refund_record?nick="+nick+"&phone="+phone+"&contractid="+contractid;
        window.location.href = url;
    });

    $(".opt-info-ex").on("click",function(){
        var orderid = $(this).parent().data("orderid");
        $.ajax({
				type     :"post",
				url      :"/user_manage/get_refund_detail",
				dataType :"json",
				data     :{'orderid': orderid},
				success  : function(result){
                    var arr=[];
                    arr.push([ "退费原因", result.info.refund_reason] );
                    arr.push([ "支付方式", result.info.channelid ] );
                    arr.push([ "支付账号",  result.info.pay_account] );
                    arr.push([ "支付金额",  result.info.price] );
                    arr.push([ "打款时间",  result.info.refund_time] );
                    arr.push([ "打款方式",  result.info.refund_channel] );
                    arr.push([ "打款流水号",  result.info.refund_number] );
                    show_key_value_table("退款信息",arr );
				}
			});
    });

    $(".opt-change-state ").on("click", function(){
        $("#id_confirm_contractid").html();
        $("#id_confirm_name").html();
        $("#id_confirm_refund").html();
        var orderid=$(this).parent().data('orderid');
        var id_confirm_status =  obj_copy_node("#id_confirm_status");
        var id_confirm_channel =  obj_copy_node("#id_confirm_channel");
        var id_confirm_num=  obj_copy_node("#id_confirm_num");
        var arr=[
            [ "合同编号", $(this).parent().data('contractid') ] ,
            [ "学员姓名", $(this).parent().data('name') ] ,
            [ "实退金额", $(this).parent().data('refund')+'元'  ] ,
            [ "状态", id_confirm_status  ],
            [ "打款方式",  id_confirm_channel],
            [ "打款流水号",  id_confirm_num ],
        ];

        show_key_value_table("修改退费状态", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {

                var refund_channel = id_confirm_channel.val();
                var status = id_confirm_status.val();
                var refund_num = id_confirm_num.val();           
                if(refund_num == ""){
                    alert("流水号不能为空！");
                    return;
                }
                $.ajax({
			        type     :"post",
			        url      :"/user_manage/change_refund_status",
			        dataType :"json",
			        data     :{"orderid": orderid,"refund_channel":refund_channel,"status":status,'refund_number':refund_num},
			        success  : function(result){
                        if(result.ret != 0){
                            alert(result.info);
                        }else{
                            window.location.reload();
                        }
			        }
		        });

            }
        });
    });

    $("#id_change_status").on("click",function(){
    });

});
