/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-seller_get_new_count_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
      adminid:	$('#id_adminid').val(),
      seller_new_count_type:	$('#id_seller_new_count_type').val()
        });
    }

  Enum_map.append_option_list("seller_new_count_type",$("#id_seller_new_count_type"));

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
    if (!$.check_power(1003)) {
        $("#id_add").hide();
    }

});
