
function load_data(){

    reload_self_page({
        studentid: $("#id_studentid").val(),
        contract_type: $("#id_contract_type").val(),
        isset_assistantid: $("#id_isset_assistantid").val(),
        start_time: $("#id_start_time").val(),
        end_time: $("#id_end_time").val()
        
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


	//init  input data
	$("#id_start_time").val(g_args.start_time);
	$("#id_end_time").val(g_args.end_time);
    $("#id_studentid").val(g_args.studentid);
    $("#id_isset_assistantid").val(g_args.isset_assistantid );
	$("#id_contract_type").val(g_args.contract_type);

    $("#id_studentid").admin_select_user({
        "type"   : "student",
        "onChange": function(){
            load_data(  );
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



	$(".c_sel").on("change",function(){
        load_data();
	});


    $(" .opt-money-check").on("click",function(){
        var orderid=$(this).get_opt_data("orderid");
        var $check_money_flag = $("<select/>");
        var $check_money_desc = $("<textarea/>");
        Enum_map.append_option_list( "check_money_flag",  $check_money_flag ,true );
        
        var arr=[
            ["确认状态" , $check_money_flag],
            ["说明" ,  $check_money_desc],
        ];
        show_key_value_table("财务确认", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                do_ajax("/user_deal/order_check_money",{
                    "orderid" :orderid,
                    "check_money_flag" : $check_money_flag.val(),
                    "check_money_desc" : $check_money_desc.val()
                });
            }
        });

    });

    
    $("#id_show_all").on("click",function(){
	    //
		var url= $(".page-opt-show-all" ).attr("data");
        if (!url) {
            alert("已经是全部了!");
            return ;
        }else{
		    var page_num=0xFFFFFFFF+1; 
            url=url.replace(/{Page}/, page_num  );
            $(this).attr("href",url);
        }
	    
    });



});
