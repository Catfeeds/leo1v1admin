/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-tmk_student_list_ex.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            publish_flag:	$('#id_publish_flag').val(),
            origin:	$('#id_origin').val(),
            userid:	$('#id_userid').val(),
            seller_student_status:	$('#id_seller_student_status').val(),
            tmk_student_status:	$('#id_tmk_student_status').val(),
            admin_revisiterid:	$('#id_admin_revisiterid').val(),
            subject:	$('#id_subject').val(),
            has_pad:	$('#id_has_pad').val(),
            grade:	$('#id_grade').val(),
            phone_name:	$('#id_phone_name').val()
        });
    }

    Enum_map.append_option_list("boolean",$("#id_publish_flag"));
    Enum_map.append_option_list("tmk_student_status",$("#id_tmk_student_status"));
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("pad_type",$("#id_has_pad"));
    Enum_map.append_option_list("seller_student_status",$("#id_seller_student_status"));
    Enum_map.append_option_list("grade",$("#id_grade"));

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
    $('#id_origin').val(g_args.origin);
    $('#id_userid').val(g_args.userid);
    $('#id_tmk_student_status').val(g_args.tmk_student_status);
    $('#id_subject').val(g_args.subject);
    $('#id_has_pad').val(g_args.has_pad);
    $('#id_grade').val(g_args.grade);
    $('#id_phone_name').val(g_args.phone_name);
    $('#id_admin_revisiterid').val(g_args.admin_revisiterid);
    $('#id_seller_student_status').val(g_args.seller_student_status);
    $('#id_publish_flag').val(g_args.publish_flag);


    $('.opt-change').set_input_change_event(load_data);
    $.admin_select_user(
        $('#id_userid'),
        "student", load_data ,false, {
            "adminid":g_args.tmk_adminid,
            select_btn_config: [{
                "label": "[未分配]",
                "value": 0
            }]
        }
    );
    $.admin_select_user(
        $('#id_admin_revisiterid'),
        "admin", load_data ,false, {
            " main_type": 2,
            select_btn_config: [
                {
                    "label": "[已分配]",
                    "value": -2
                }, {
                    "label": "[未分配]",
                    "value": 0
                }]
        }
    );


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
        Enum_map.append_option_list("tmk_student_status", $tmk_student_status,true );


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
                        'test_lesson_subject_id'       : opt_data.test_lesson_subject_id,
                        'userid'       : opt_data.userid,
                        'nick'       : $nick.val(),
                        'grade'       : $grade.val(),
                        'subject'       : $subject.val(),
                        'tmk_desc'       : $tmk_desc.val(),
                        'tmk_next_revisit_time'       : $tmk_next_revisit_time.val(),
                        'tmk_student_status'       : $tmk_student_status.val()
                    });
                }
            }],function(){
            });



    });
    $(".opt-telphone").on("click",function(){
        //
        var opt_data= $(this).get_opt_data();
        var phone    = ""+ opt_data.phone;
        phone=phone.split("-")[0];

        //copyToClipboard(opt_data.phone);

        try{
            window.navigate(
                "app:1234567@"+phone+"");
        } catch(e){

        };
        $.do_ajax_t("/ss_deal/call_ytx_phone", {
            "phone": opt_data.phone
        } );
        /*

        //同步...
        var lesson_info = JSON.stringify({
        cmd: "noti_phone",
        phone: phone
        });
        $.ajax({
        type: "get",
        url: "http://admin.leo1v1.com:9501/pc_phone_noti_user_lesson_info",
        dataType: "text",
        data: {
        'username': g_account,
        "lesson_info": lesson_info
        }
        });
        */
        //
        $(this).parent().find(".opt-publish-flag").click();
    });

    $(".opt-publish-flag").on("click",function(){
        var opt_data=$(this).get_opt_data();


        var $seller_student_status=$("<select></selelct>");
        var need_list=[];
        if (opt_data.seller_student_status==50) {
            need_list=[0,50];
        }else{
            need_list=[ opt_data.seller_student_status, 50];
        }
        Enum_map.append_option_list("seller_student_status",$seller_student_status,true, need_list );
        $seller_student_status.val(opt_data.seller_student_status );
        var arr=[
            ["回访状态",  $seller_student_status],
        ];

        $.show_key_value_table("设置是否公海可见", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ss_deal/set_seller_student_status",{
                    "test_lesson_subject_id" : opt_data.test_lesson_subject_id,
                    "seller_student_status" : $seller_student_status.val()
                });
            }
        });

    });



    $(".opt-telphone-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen('/tq/get_list?phone=' + opt_data.phone);
    });

    $("#id_add").on("click",function(){
        var $phone=$("<input/>");
        var $subject=$("<select/>");
        var $grade=$("<select/>");
        var $origin=$("<input/>");
        var arr=[
            ["电话",  $phone],
            ["年级",  $grade],
            ["科目",  $subject],
            ["渠道",  $origin],
        ];
        Enum_map.append_option_list("grade",$grade, true);
        Enum_map.append_option_list("subject",$subject, true);

        $.show_key_value_table("新增申请", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                $.do_ajax("/ss_deal/add_ss",{
                    "tmk_flag" : 1,
                    "phone" : $.trim($phone.val()) ,
                    "subject" : $subject.val() ,
                    "grade" : $grade.val() ,
                    "origin" : $.trim($origin.val())

                });
            }
        });


    });

    var action=$.get_action_str();
    if (action=="tmk_student_list") {
        $(".opt-telphone").hide();
        $(".opt-publish-flag").hide();
    }


    $(".opt-jump").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/seller_student_new/tmk_assign_sub_adminid_list?userid="+ opt_data.userid );
    });

    $(".show_phone").on("click",function(){
        var phone = $(this).data("phone");
        BootstrapDialog.alert(phone);
    });
});
