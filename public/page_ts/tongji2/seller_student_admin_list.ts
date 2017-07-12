/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-seller_student_admin_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
      del_flag:	$('#id_del_flag').val()
        });
    }

  Enum_map.append_option_list("boolean",$("#id_del_flag"));

  $('#id_del_flag').val(g_args.del_flag);




  $('.opt-change').set_input_change_event(load_data);
    $(".opt-clean-seller-student").on("click",function(){
        var opt_data=$(this).get_opt_data();
        if (opt_data.del_flag) {
            BootstrapDialog.confirm("要清空数据? "+ opt_data.account, function(val) {
                if (val) {
                    $.do_ajax("/ss_deal/clean_admin_seller_student", {
                        "adminid": opt_data.adminid
                    }  );
                }
            });
        }else{
            alert("在职不能清空");
        }

    });

    $(".opt-show").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/seller_student_new/assign_sub_adminid_list?order_by_str=&date_type=0&opt_date_type=0&publish_flag=1&start_time=2016-01-01&end_time=2017-04-15&userid=-1&origin=&origin_ex=&grade=-1&origin_level=-1&subject=-1&phone_location=&admin_revisiterid="+opt_data.adminid+"&seller_student_status=-1&seller_student_sub_status=-1&has_pad=-1&sub_assign_adminid_2=-1&origin_assistantid=-1&tq_called_flag=-1&global_tq_called_flag=-1&tmk_adminid=-1&tmk_student_status=-1&seller_resource_type=-1"
               );

    });


});
