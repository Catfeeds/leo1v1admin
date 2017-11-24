/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/festival-festival_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val()
    });
}
$(function(){


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

    $("#add_new_ad_info").on("click",function(){
        var data           = $(this).get_opt_data();
        var id_name = $("<input/>");
        var id_days   = $("<input/>");
        var id_start_time = $("<input />");
        var id_end_time = $("<input />");
        id_start_time.datetimepicker({
            datepicker:true,
            timepicker:false,
            format:'Y-m-d',
            step:30,
            onChangeDateTime :function(){

            }

        });
        id_end_time.datetimepicker({
            datepicker:true,
            timepicker:false,
            format:'Y-m-d',
            step:30,
            onChangeDateTime :function(){

            }
        });


        var arr = [
            ["名称",id_name],
            ["开始时间",id_start_time],
            ["结束时间",id_end_time],
           // ["时长",id_days],
        ];

        $.show_key_value_table("新增假日",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/festival/add_new_festival",{
                    "name"   : id_name.val(),
                    "start" : id_start_time.val(),
                    "end"   : id_end_time.val(),
                  //  "days"   : id_days.val(),
                })
            }
        });

    });
    $(".opt-edit").on("click",function(){
        var data           = $(this).get_opt_data();
        var id = data.id;
        var id_name = $("<input/>");
        var id_days   = $("<input/>");
        var id_start_time = $("<input />");
        var id_end_time = $("<input />");
        id_start_time.datetimepicker({
            datepicker:true,
            timepicker:false,
            format:'Y-m-d',
            step:30,
            onChangeDateTime :function(){

            }

        });
        id_end_time.datetimepicker({
            datepicker:true,
            timepicker:false,
            format:'Y-m-d',
            step:30,
            onChangeDateTime :function(){

            }
        });


        var arr = [
            ["名称",id_name],
            ["开始时间",id_start_time],
            ["结束时间",id_end_time],
           // ["时长",id_days],
        ];
        id_name.val(data.name);
        id_start_time.val(data.begin_time_str);
        id_end_time.val(data.end_time_str);
        id_days.val(data.days);

        $.show_key_value_table("修改",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/festival/update_festival_new",{
                    "name"   : id_name.val(),
                    "start" : id_start_time.val(),
                    "end"   : id_end_time.val(),
                  //  "days"   : id_days.val(),
                    "id"     : id
                })
            }
        });

    });


    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();

        BootstrapDialog.confirm(
            "确认要删除?",
            function(val) {
                if  (val)  {
                    $.do_ajax( "/festival/del_festival", {
                        "id": opt_data.id
                    });
                }
            }
        );
    });





	$('.opt-change').set_input_change_event(load_data);
});
