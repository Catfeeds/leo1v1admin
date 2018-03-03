/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-seller_get_new_count_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            adminid:	$('#id_adminid').val(),
            seller_new_count_type:	$('#id_seller_new_count_type').val(),

			      date_type_config:	$('#id_date_type_config').val(),
			      date_type:	$('#id_date_type').val(),
			      opt_date_type:	$('#id_opt_date_type').val(),
			      start_time:	$('#id_start_time').val(),
			      end_time:	$('#id_end_time').val()
        });
    }

  Enum_map.append_option_list("seller_new_count_type",$("#id_seller_new_count_type"));

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
	$('#id_adminid').val(g_args.adminid);

  $('#id_adminid').val(g_args.adminid);
  $('#id_seller_new_count_type').val(g_args.seller_new_count_type);


    $.admin_select_user(
        $('#id_adminid'),
        "admin", load_data ,false, {
            "main_type": 2 //分配用户
        }
    );


  $('.opt-change').set_input_change_event(load_data);

    $("#id_add").on("click",function(){
        var $adminid=$("<input/>");
        var $start_time=$("<input/>");
        var $end_time=$("<input/>");
        var $count=$("<input/>");
        var arr= [
            ["销售",  $adminid],
            ["增加个数",  $count],
            ["生效开始时间",  $start_time],
            ["生效结束时间",  $end_time],
        ];


        var opt_date=$.DateFormat(  (new Date ).getTime()/1000, "yyyy-MM-dd") ;

        $start_time.val( opt_date );
        $end_time.val( opt_date );
      $start_time.datetimepicker({
            lang: "zh",

        format:'Y-m-d',
        datepicker:true,
        timepicker:false
      });

      $end_time.datetimepicker({
            lang: "zh",
        format:'Y-m-d',
        datepicker:true,
        timepicker:false
      });


        $count.val(5);

        $.show_key_value_table("后台增加额度",arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ss_deal/seller_new_count_add",{
                    "opt_adminid" : $adminid.val(),
                    "count"       : $count.val(),
                    "start_time"  : $start_time.val(),
                    "end_time"    : $end_time.val(),
                });
            }
        },function(){
            $.admin_select_user( $adminid,  "admin" ,function(){}, true );
        } );


    });

    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        alert(opt_data.new_count_id );
        BootstrapDialog.confirm(
            "要删除吗？",
            function(val) {
                if (val) {
                    $.do_ajax("/ajax_deal/del_seller_new_detail", {
                        "new_count_id": opt_data.new_count_id ,
                    })
                }
            })
    });

    if (!$.check_power(1003)) {
        $("#id_add").hide();
    }

});
