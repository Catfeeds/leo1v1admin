/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-commodity_exchange_management.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			gift_type:	$('#id_gift_type').val(),
			status:	$('#id_status').val(),
            assistantid : $("#id_assistantid").val()
        });
    }
    
    //BootstrapDialog.confirm(  );
    
	Enum_map.append_option_list("gift_status",$("#id_status")); 

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
	$('#id_gift_type').val(g_args.gift_type);
	$('#id_status').val(g_args.status);


    $(".opt-send").on("click",function(){
        var opt_data=$(this).get_opt_data();
	    
        var id_express_name=$("<select/>");
        var id_express_num=$("<input/>");
        
        Enum_map.append_option_list("express_name", id_express_name, true );


        var arr=[
            ["快递公司", id_express_name],
            ["快递单号", id_express_num],
           
        ];

        $.show_key_value_table("发货信息", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                $.do_ajax( '/present/set_material_sent', {
                    "exchangeid"   : opt_data.exchangeid,
                    "express_value" : id_express_name.val(),
                    "express_name" : id_express_name.find("option:selected").text(),
                    "express_num"  : id_express_num.val()
                });
            }
        });



    });
    $(".opt-confirm").on("click",function(){
        var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm("确认用户已经签收礼品?!",function(ret){
            if (ret){
                $.do_ajax( "/present/set_material_finished",{
                    "exchangeid"   : opt_data.exchangeid,
                });
            }
        });



    });
    $(".opt-exchange").on("click",function(){
        var opt_data=$(this).get_opt_data();
	    
        BootstrapDialog.confirm("确认用户已经兑换礼品?!",function(ret){
            if (ret){
                $.do_ajax( "/present/set_virtual_finished",{
                    "exchangeid"   : opt_data.exchangeid,
                });
            }
        });

    });

    
	$('.opt-change').set_input_change_event(load_data);
	$('#id_assistantid').val(g_args.assistantid);
	

    

    $.admin_select_user($("#id_assistantid"), "assistant",function(){
        load_data();
    });
    

    $(".opt-all-div").each(function(){
        var $div=$(this);
        if ($div.data("gift_type") == 1 ) {           
            $div.find(".opt-exchange").hide();
        }else{   
            $div.find(".opt-confirm").hide();
            $div.find(".opt-send").hide();
        }
    });

    if ( window.location.pathname =="/user_manage_new/commodity_exchange_management_assistant" ) {
        $("#id_assistantid").parent().parent().hide();
    }


    $(".opt-set_status").on("click",function(){
        var opt_data   = $(this).get_opt_data();
        var exchangeid = opt_data.exchangeid;

        var id_status = $("<select />");
        Enum_map.append_option_list("gift_status",id_status);
        id_status.val(opt_data.status);
        var arr = [
            ["状态",id_status],
        ];

        $.show_key_value_table("变更礼物状态",arr,{
            label:"确认",
            cssClass:"btn-warning",
            action:function(dialog) {
                $.do_ajax("/user_manage_new/set_gift_status",{
                    "exchangeid" : exchangeid,
                    "status"     : id_status.val()
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        });
    });

    if(g_account=="郑璞"){
        download_show();
    }
});

