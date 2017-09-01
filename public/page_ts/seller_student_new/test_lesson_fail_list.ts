/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-test_lesson_fail_list.d.ts" />

$(function(){
    var show_name_key="stu_info_name_"+g_adminid;
    function load_data(){
        if ($.trim($("#id_phone_name").val()) != g_args.phone_name ) {
            $.do_ajax("/user_deal/set_item_list_add",{
                "item_key" :show_name_key,
                "item_name":  $.trim($("#id_phone_name").val())
            },function(){});
        }
        if ($.trim($("#id_user_info").val()) != g_args.user_info ) {
            $.do_ajax("/user_deal/set_item_list_add",{
                "item_key" :show_name_key,
                "item_name":  $.trim($("#id_user_info").val())
            },function(){});
        }

        $.reload_self_page ( {
            date_type     : $('#id_date_type').val(),
            opt_date_type : $('#id_opt_date_type').val(),
            start_time    : $('#id_start_time').val(),
            end_time      : $('#id_end_time').val(),
            grade         : $('#id_grade').val(),
            phone_name    : $('#id_phone_name').val(),
            user_info     : $('#id_user_info').val(),
            has_pad       : $('#id_has_pad').val(),
            subject       : $('#id_subject').val()
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

    $( "#id_user_info" ).autocomplete({
        source: "/user_deal/get_item_list?list_flag=1&item_key="+show_name_key,
        minLength: 0,
        select: function( event, ui ) {
            load_data();
        }
    });

    Enum_map.append_option_list("grade",$("#id_grade"));
    Enum_map.append_option_list("pad_type",$("#id_has_pad"));
    Enum_map.append_option_list("subject",$("#id_subject"));

    $('#id_grade').val(g_args.grade);
    $('#id_has_pad').val(g_args.has_pad);
    $('#id_phone_name').val(g_args.phone_name);
    $("#id_user_info").val(g_args.user_info);
    $('#id_subject').val(g_args.subject);


    $( "#id_phone_name" ).autocomplete({
        source: "/user_deal/get_item_list?list_flag=1&item_key="+show_name_key,
        minLength: 0,
        select: function( event, ui ) {
            $("#id_phone_name").val(ui.item.value);
            load_data();
        }
    });


    $(".opt-set-self").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/ss_deal/set_no_called_to_self",{
            "test_lesson_subject_id" : opt_data.test_lesson_subject_id,
            "free_flag" :1
        });

    });


    $(".opt-telphone").on("click",function(){
        //

        var opt_data= $(this).get_opt_data();
        var phone    = ""+ opt_data.phone;
        //opt_data.userid

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
        url: "http://admin.yb1v1.com:9501/pc_phone_noti_user_lesson_info",
        dataType: "text",
        data: {
        'username': g_account,
        "lesson_info": lesson_info
        }
        });
        */
        //
        $(this).parent().find(".opt-edit").click();
    });


    $('.opt-change').set_input_change_event(load_data);
});
