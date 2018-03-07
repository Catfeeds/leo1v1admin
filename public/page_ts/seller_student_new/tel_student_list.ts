/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-tel_student_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
			      global_tq_called_flag:	$('#id_global_tq_called_flag').val(),
            end_time:	$('#id_end_time').val(),
			      subject:	$('#id_subject').val(),
            seller_student_status:	$('#id_seller_student_status').val(),
			      grade:	$('#id_grade').val(),
            page_count: g_args.page_count ,
            page_num: g_args.page_num ,
        });
    }


    // 处理标记空号功能 [james]


    var local_userid = 0; 
    var test_arr = ['99','684','1460','1077','1307','1207','834']; // 冒烟
    // var test_arr = ['1408','1419','1407','1406']; // 测试

    if($.inArray(g_adminid,test_arr)>=0){ // 测试
        $('.opt-sign').on('click',function(){
            $('.bs-example-modal-sm').modal('toggle');
            var opt_data=$(this).get_opt_data();
            local_userid = opt_data.userid;
            do_submit();
        });


        $('.submit_tag').on("click",function(){
            sign_func();
        });

        $('.invalid_type').on("change",function(){
            do_submit();
        });

        var do_submit = function(){
            var invalid_type = $('.invalid_type').val();
            if(invalid_type == 0){
                $('.submit_tag').attr('disabled','disabled');
            }else{
                $('.submit_tag').removeAttr('disabled');
            }
        }

        var sign_func = function(){
            var invalid_type = $('.invalid_type').val();
            var checkText=$(".invalid_type").find("option:selected").text();

            $('.tip_text').text(checkText);
            $('.confirm-sm').modal('toggle');
            $('.confirm_tag').on("click",function(){
                $.do_ajax("/ajax_deal3/sign_phone",{
                    "adminid" : g_adminid,
                    "confirm_type" : invalid_type,
                    "userid"  : local_userid,
                    "type"    : 2
                });
                window.location.reload();
            });
        }

        if(g_args.tq_called_flag != 2){
            $('#id_edit').attr('disabled','disabled');
        }else{
            $('#id_edit').removeAttr('disabled');
        }
    }


    // 处理标记空号功能 [james-end]







    $('#id_date_range').select_date_range({
        'date_type'     : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    Enum_map.append_option_list("seller_student_status",$("#id_seller_student_status"), false, [1,2,101,102]);

    $('#id_seller_student_status').val(g_args.seller_student_status);

	  $('#id_global_tq_called_flag').val(g_args.global_tq_called_flag);
	  $.enum_multi_select( $('#id_global_tq_called_flag'), 'tq_called_flag', function(){load_data();} )

	$('#id_subject').val(g_args.subject);
	$.enum_multi_select( $('#id_subject'), 'subject', function(){load_data();} )


	$('#id_grade').val(g_args.grade);
	$.enum_multi_select( $('#id_grade'), 'grade', function(){load_data();} )

    $('.opt-change').set_input_change_event(load_data);
    $(".opt-publish-flag").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var $seller_status=$("<select></selelct>");
        $seller_status.append("<option value='1'>是</option><option value='2'>否</option>");
        var arr=[
            ["是否跟进",  $seller_status],
        ];

        $.show_key_value_table("设置是否跟进", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ss_deal/deal_tel_state",{
                    "user_id" : opt_data.userid,
                    "seller_status" : $seller_status.val()
                },function(result){
                    if ($seller_status.val()=="1") {
                        $.wopen("/seller_student_new2/tmk_student_list2?userid="+opt_data.userid );
                    }else{
                        load_data();
                    }
                });
            }
        });
    });

    $(".opt-telphone").on("click",function(){
        //
        var opt_data= $(this).get_opt_data();

        var phone=opt_data.phone;
        try{
            window.navigate(
                "app:1234567@"+phone+"");
        } catch(e){

        };

        $.do_ajax_t("/ss_deal/call_ytx_phone", {
            "phone": opt_data.phone
        } );
    });



    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();

        /*
          if(opt_data.lessonid > 0 ){
          alert('已有排课, 你可以换时间,换老师!');
          return;
          }
        */

        var $tmk_next_revisit_time = $("<input  /> ");
        var $nick= $("<input  /> ");
        var $tmk_desc= $("<textarea/>");
        var $grade= $("<select/>");
        var $subject= $("<select/>");
        var $tmk_student_status= $("<select/>");
        Enum_map.append_option_list("grade", $grade,true );
        Enum_map.append_option_list("subject", $subject,true );


        // james-start
        if($.inArray(g_adminid,test_arr)>=0){ // 测试
            Enum_map.append_option_list("tmk_student_status", $tmk_student_status,true,[0,1,3] );
        }else{
            Enum_map.append_option_list("tmk_student_status", $tmk_student_status,true ); // 原始
        }
        // james-end


        $tmk_next_revisit_time.datetimepicker({
        lang:'ch',
        datepicker:true,
        timepicker:true,
        format:'Y-m-d H:i',
        step:30,
          onChangeDateTime :function(){
            }
      });

        $nick.val(opt_data.nick);
        $grade.val(opt_data.grade);
        $subject.val(opt_data.subject);
        $tmk_student_status.val(opt_data.tmk_student_status);
        $tmk_next_revisit_time.val(opt_data.tmk_next_revisit_time);
        $tmk_desc.val(opt_data.tmk_desc);


        var arr=[
            ["电话",   opt_data.phone]  ,
            ["姓名", $nick]  ,
            ["年级", $grade]  ,
            ["科目",   $subject]  ,
            ["TMK状态", $tmk_student_status  ] ,
            ["下次回访时间", $tmk_next_revisit_time ]  ,
            ["备注", $tmk_desc] ,
        ];

        $.show_key_value_table("编辑", arr ,[
            {

                label    : '确认',
                cssClass : 'btn-warning',
                action   : function(dialog) {
                    $.do_ajax("/ss_deal/tmk_save_user_info",{
                        'test_lesson_subject_id' : opt_data.test_lesson_subject_id,
                        'userid'                 : opt_data.userid,
                        'nick'                   : $nick.val(),
                        'grade'                  : $grade.val(),
                        'subject'                : $subject.val(),
                        'tmk_desc'               : $tmk_desc.val(),
                        'tmk_next_revisit_time'  : $tmk_next_revisit_time.val(),
                        'tmk_student_status'     : $tmk_student_status.val(),
                        'tmk_student_status_old' : opt_data.tmk_student_status
                    });
                }
            }],function(){
            });



    });
    $(".show_phone").on("click",function(){
        var phone = $(this).data("phone");
        BootstrapDialog.alert(phone);
    });

});
