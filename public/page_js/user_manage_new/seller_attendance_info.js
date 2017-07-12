/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-seller_attendance_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
            account:	$('#id_account').val(),
            seller_work_status:	$('#id_seller_work_status').val(),
            plan_seller_work_status:	$('#id_plan_seller_work_status').val()
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

    Enum_map.append_option_list("seller_work_status",$('#id_seller_work_status'),false); 
	$('#id_seller_work_status').val(g_args.seller_work_status);
    Enum_map.append_option_list("seller_work_status",$('#id_plan_seller_work_status'),false,[0,1]); 
	$('#id_plan_seller_work_status').val(g_args.plan_seller_work_status);
	$('#id_account').val(g_args.account);
    $('.opt-edit').on("click",function(){
        var opt_data = $(this).get_opt_data();
        var month = g_args.start_time.substr(0,8)+"01";
        $.do_ajax('/user_manage_new/get_seller_month_time_js',{
            adminid: opt_data.adminid,
            groupid:"",
            month: month
        },function(resp) {
            if(resp.data ==0){
                BootstrapDialog.show({
                    title: '无应出勤信息',
                    message: '<a href = \"admin_member_list\" target=\"_blank\" style=\"color:red;font-size:18px;text-decoration:underline;\">该销售尚未设置应出勤信息,点击前往设置出勤信息!</a>',
                    buttons: [{
                        label: '返回',
                        action: function(dialog) {
                            dialog.close();
                        }
                    }]
                });
                return;
            }else{
                var id_leave_and_overtime=$("<select/>");
                if(opt_data.plan_do == 0){
                    Enum_map.append_option_list("seller_work_status", id_leave_and_overtime,true,[0,3]);
                }else{
                    Enum_map.append_option_list("seller_work_status", id_leave_and_overtime,true,[1,2]); 
                }


                var arr=[
                    ["实际出勤情况", id_leave_and_overtime]
                ];

                id_leave_and_overtime.val(opt_data.real_do);
                
                $.show_key_value_table("修改实际出勤情况", arr ,{
                    label: '确认',
                    cssClass: 'btn-warning',
                    action : function(dialog) {
                        $.do_ajax( '/user_manage_new/update_seller_work_status', {
                            "start_time":g_args.start_time,
                            "adminid": opt_data.adminid,
                            "month":month,
                            "seller_work_status" : id_leave_and_overtime.val()
                        });
                    }
                });

            }
            
        });
        
    });
    var init_noit_btn=function( id_name, title) {
        var btn=$('#'+id_name);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value =btn.data("value");
        btn.text(value);
        if (value >0 ) {
            btn.addClass("btn-warning");
        }
    };

    init_noit_btn("id_plan_work_count",    "应出勤人数" );
    init_noit_btn("id_real_work_count",    "实际出勤人数" );
    init_noit_btn("id_leave_count",    "请假人数" );
    init_noit_btn("id_overtime_count",    "加班人数" );
    $("#id_overtime_count").on("click",function(){     
        $("#id_seller_work_status").val(3);
        $('#id_account').val("");
        $("#id_plan_seller_work_status").val(-1);
        load_data();
    });
    $("#id_leave_count").on("click",function(){     
        $("#id_seller_work_status").val(2);
        $('#id_account').val("");
        $("#id_plan_seller_work_status").val(-1);
        load_data();
    });
    $("#id_real_work_count").on("click",function(){     
        $("#id_seller_work_status").val(-2);
        $('#id_account').val("");
        $("#id_plan_seller_work_status").val(-1);
        load_data();
    });
    $("#id_plan_work_count").on("click",function(){     
        $("#id_plan_seller_work_status").val(1);
        $("#id_seller_work_status").val(-1);
        $('#id_account').val("");
        load_data();
    });




    $(".plan_do_list,.real_do_list").each(function(){
        if($(this).text()=="休息"){
            $(this).css("background-color","#fff");
        }else if($(this).text()=="上班"){
            $(this).css("background-color","#17a6e8");
        }else if($(this).text()=="请假"){
            $(this).css("background-color","red");
        }else if($(this).text()=="加班"){
            $(this).css("background-color","orange");
        }

    });

    /*$.each($(".l-2,.l-3,.l-4"),function(){       
        $(this).hide();
       
    });*/


    var link_css=        {
        color: "#3c8dbc",
        cursor:"pointer"
    };

    $(".l-1 .main_type").css(link_css);
    $(".l-2 .up_group_name").css(link_css);
    $(".l-3 .group_name").css(link_css);

    $(".l-1 .main_type").on("click",function(){
        var $this=$(this);
        var class_name= $this.data("class_name");
        if (!$this.data("show") ==true) {
            $(".up_group_name."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".up_group_name."+class_name ).parent(".l-2");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );

    });

    $(".l-2 .up_group_name").on("click",function(){
        var $this=$(this);
        var class_name= $this.data("class_name");
        if (!$this.data("show") ==true) {
            $(".group_name."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".group_name."+class_name ).parent(".l-3");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });

    $(".l-3 .group_name").on("click",function(){
        var $this=$(this);
        var class_name= $this.data("class_name");
        if (!$this.data("show") ==true) {
            $(".account."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".account."+class_name ).parent(".l-4");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });

    
    $(".opt-edit").each(function(){
        var opt_data = $(this).get_opt_data();
        if(opt_data.level != "l-4" ){
            $(this).hide();
        }
    });

    $("#id_opt_date_type").hide();
	$('.opt-change').set_input_change_event(load_data);
});

