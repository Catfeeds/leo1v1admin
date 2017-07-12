$(function(){
    
    function load_data($sid, $start_time, $end_time, $present_status){
        var url = "/stu_manage/present?sid="+$sid+"&present_status="+$present_status+"&start_time="+$start_time+"&end_time="+$end_time+"&nick="+g_nick;
        window.location.href = url;
    }
    var sadf="" ;
	//时间控件
	$('#datetimepicker6').datetimepicker({
		lang:'ch',
		timepicker:false,
        onChangeDateTime :function(){
			load_data(
                g_sid,
				$("#datetimepicker6").val(),
				$("#datetimepicker7").val(),
                $("#id_present_status").val()
			);
		},
		format:'Y-m-d'
	});
		
	$('#datetimepicker7').datetimepicker({
		lang:'ch',
		timepicker:false,
        onChangeDateTime :function(){
            load_data(
                g_sid,
				$("#datetimepicker6").val(),
				$("#datetimepicker7").val(),
                $("#id_present_status").val()
			);
		},
		format:'Y-m-d'
	});

    $("#id_present_status").on("change",function(){
        load_data(
            g_sid,
			$("#datetimepicker6").val(),
			$("#datetimepicker7").val(),
            $("#id_present_status").val()
		);
    });

    $(".done_n").on("click",function(){
        var status = $(this).parent().parent().data("status");
        var giftid = $(this).parent().parent().data("exchangeid");
        var type = $(this).parent().parent().data("type");

        //若状态为未处理且是实物，则修改状态为已发送，同时记录快递公司与单号
        if(status == 0 && type != 2){
            $(".mesg_alert05").show();
            $("#id_submit1").data("giftid", giftid);

        // 若为实际物体且已发送或是虚拟物体未发送，直接改为已经接受
        }else if(status != 2){
            $(".mesg_alert06").show();
            $("#id_submit2").data("giftid", giftid);
        }
    });
    
    $("#id_submit2").on("click", function(){
        var giftid = $("#id_submit2").data('giftid');
        $.ajax({
			type     :"post",
			url      :"/stu_manage/update_gift_to_sended",
			dataType :"json",
			data     :{"giftid":giftid},
			success  : function(result){
                if(result['ret'] == 0){
                    window.location.reload();
                }else{
                    alert(result['info']);
                }
			}
		});
    });

    $("#id_submit1").on("click", function(){
        var giftid = $("#id_submit1").data('giftid');
        var express_name = $("#id_express_name").val();
        var express_num = $("#id_express_num").val();
        $.ajax({
			type     :"post",
			url      :"/stu_manage/update_gift_to_sending",
			dataType :"json",
			data     :{"giftid":giftid, "express_name": express_name, "express_num": express_num},
			success  : function(result){
                if(result['ret'] != 0){
                    alert(result.info);
                }else{
                    window.location.reload();
                }
			}
		});
    });
});
