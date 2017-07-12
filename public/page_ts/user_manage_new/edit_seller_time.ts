/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-edit_seller_time.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			month: g_args.month,
            date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
            adminid:g_args.adminid	,
			groupid:g_args.groupid

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
    $("#id_opt_date_type").hide();

	$('#id_month').val(g_args.month);
	$('#id_adminid').val(g_args.adminid);
	$('#id_groupid').val(g_args.groupid);



  
    var init_title=function( id_name, start_time ) {
        start_time=start_time*1;
        var tr_list=$("#"+id_name+"> td");
        for ( var i=0;i<7;i++) {
            var title=$.DateFormat(start_time+i*86400, "yyyy-MM-dd");
            $(tr_list[i]).text(title);
        }
    };
    var start_time=$("#id_week_info").data("start_time")*1;

    init_title("tr_list_1", start_time );
    init_title("tr_list_2", start_time+7*86400 );
    init_title("tr_list_3", start_time+14*86400 );
    init_title("tr_list_4", start_time+21*86400 );
    init_title("tr_list_5", start_time+28*86400 );
    init_title("tr_list_6", start_time+35*86400 );
    if($($("#tr_list_6 > td")[0]).text().substr(5,2) != $($("#tr_list_4 > td")[0]).text().substr(5,2) ){
        $("#tr_list_6").hide();
    }
    $("#cal_week tbody td").on("click",function(){
        var num = $($("#tr_list_4 > td")[0]).text().substr(5,2);
        if($(this).text().substr(5,2) == num){
            if ( $(this).hasClass("select_free_time")) {
                $(this).removeClass("select_free_time");
            }else{
                $(this).addClass("select_free_time");
            }
        }
    });

    $("#id_update").on("click",function(){
        var month_list=[];
        $("#cal_week tbody td").each(function() {
            var $this=$(this);
            
            if ($(this).text().substr(5,2) == $($("#tr_list_4 > td")[0]).text().substr(5,2)) {
                var tmp_date= $this.text();
                if($(this).hasClass("select_free_time")){
                    month_list.push ([
                        ""+tmp_date +":1"
                    ]);
                    
                }else{
                    month_list.push ([
                        ""+tmp_date +":0"
                    ]);

                }
            } 
        });
        $.do_ajax("/user_deal/update_seller_month_time",{
            groupid : g_groupid,
            adminid: g_adminid,
            month: g_month,
            month_time : JSON.stringify(month_list )
        });
    });
   
    $.do_ajax('/user_manage_new/get_seller_month_time_js',{
        groupid : g_groupid,
        adminid: g_adminid,
        month: g_month
    },function(resp) {
        var month_time = resp.data;
        $("#cal_week tbody td").each(function() {
            if ($(this).text().substr(5,2) != $($("#tr_list_4 > td")[0]).text().substr(5,2)) {
                $(this).css('color','#fff');
            }
        });

        $.each(month_time,function(i,item){
            var day = item[0].substr(0,10);
            var day_flag = item[0].substr(11,1);
            
            $("#cal_week tbody td").each(function() {
                var $this=$(this);
                
                if ($(this).text() == day ) {
                    if(day_flag ==1){
                        $(this).addClass("select_free_time");
                    }
                }
            });

        });

        
    });

    
	$('.opt-change').set_input_change_event(load_data);
});



