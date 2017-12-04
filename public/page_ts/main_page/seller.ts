/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-seller.d.ts" />
$(function(){
    function load_data(groupid ){
        if (!groupid) {
            groupid= -1;
        }
        $.reload_self_page ( {
            date_type      : $('#id_date_type').val(),
            opt_date_type  : $('#id_opt_date_type').val(),
            start_time     : $('#id_start_time').val(),
            end_time       : $('#id_end_time').val(),
            groupid        : groupid,
            test_seller_id : $("#id_test_seller_id").val(),
            order_by_str   : $('#id_order_by_str').val(),
        });
    }
    $('#id_date_range').select_date_range({
        'date_type'     : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data(0);
        }
    });
    $("#id_test_seller_id").val(g_args.test_seller_id);

    $.admin_select_user($('#id_test_seller_id'),"seller_group", load_data ,false, {
        "main_type": 2, //分配用户
        select_btn_config: [{
            "label": "所有非销售",
            "value":  -3
        },{
            "label": "所有销售",
            "value":  -2
        }]
    });

    // alert($("#id_seller_new").children('input').val());
    // $("#id_seller_new").children('input').val(seller_account);

    if(group_type == 0){
        $('#id_seller_new').attr('style','display:none');
    }

    function show_top( $person_body_list) {

        $($person_body_list[0]).find("td").css(
            {
                "color" :"red"
            }
        );
        $($person_body_list[1]).find("td").css(
            {
                "color" :"orange"
            }
        );

        $($person_body_list[2]).find("td").css(
            {
                "color" :"blue"
            }
        );

    }


    show_top( $("#id_person_body > tr")) ;
    show_top( $("#id_group_body > tr") ) ;
    show_top( $("#id_group_self_body > tr") ) ;

    $(".show-group").css( {
        cursor: "pointer",
        "text-decoration": "underline"
    });
    $(".show-group").on("click",function(){
        var groupid=$(this).data("groupid");
        /*$.wopen("/main_page/seller?groupid=" +groupid,true );*/
        if(g_args.self_groupid == 0){
            load_data(groupid);
        }else{
            var   show_flag=false;
            if ( g_account =="jim"  || g_account =="leowang"  ) {
                show_flag=true;
            }
            if(groupid == g_args.self_groupid   ){
                show_flag=true;
            }else{
            }
            if (show_flag) {
                load_data(groupid);
            }else{
                alert("不可查看");
            }
        }

    });

    $(".show-group").each(function(){
        var groupid=$(this).data("groupid");
        if(g_args.self_groupid != 0 && groupid != g_args.self_groupid && g_args.is_group_leader_flag  == 0 ){
            $(this).parent().children(".all_count,.all_price").text("");
        }
    });


    $(".table").css({
        "text-align": "center"
    });

    $("#id_show_fail_lesson_list").on("click",function(){


        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" : "list", // or "list"
            "url"      : "/ajax_deal/tongji_fail_lesson_list_js",
            //其他参数
            "args_ex" : {
                "adminid":g_adminid,
                "start_time": g_args.start_time,
                "end_time": g_args.end_time
            },


            //字段列表
            'field_list' :[
                {
                    title:"课程时间",
                    field_name:"lesson_time"
                },{
                    title:"学生",
                    field_name: "student_nick"
                },{
                    title:"老师",
                    field_name:"teacher_nick"
                },{
                    title:"审批状态",
                    field_name:"flow_status_str"

                },{
                    title:"操作",
                    render: function( val, row ) {
                        return $("<a href=\"/tea_manage/lesson_list_seller?lessonid="+ row.lessonid +" \" target=\"_blank\">跳转 </a> " );
                    }
                }
            ] ,
            //查询列表
            filter_list:[
            ],
            "auto_close" : true,
            "onChange"   : function( val) {
            },
            "onLoadData" : null
        });
    });
    $('#id_order_by_all_count').click(function(){
        $('#id_order_by_str').val('count(*) desc');
        load_data();
    });
    $('#id_order_by_all_price').click(function(){
        $('#id_order_by_str').val('sum(price) desc');
        load_data();
    });
    $('#id_order_by_finish_per').click(function(){
        $('#id_order_by_str').val('if(sum(price)>0 and month_money<>0,sum(price)/month_money,0) desc');
        load_data();
    });

    //强制弹窗
    var is_force = $('.opt-no-order').data('flag');
    var user_num = $('.opt-user').length;
    if (user_num == 0){
        $('.opt-user-list').empty();
        $('.opt-user-list').append('<tr><td style="font-size:20px"><b>本月首周组员均存在开单！</b></td></tr>');
    }
    if( is_force ) {

        if(user_num != 0){
            $('#is_force').on('click', function(){
                return false;
            });
            $('.close').on('click', function(){
                return false;
            });

            $('#is_force').text(15);
            $('.opt-no-order').click();
            var time_id = setInterval(function(){
                var cur_num = parseInt( $('#is_force').text() );
                var next_num = cur_num - 1;

                if ( next_num == 0 ) {
                    clearTimeout(time_id)
                    $('#is_force').text( '确定' );
                    $('#is_force').on('click', function(){
                        $('.opt-no-order').click();
                    });
                    $('.close').on('click', function(){
                        $('.opt-no-order').click();
                    });

                    $.ajax({
                        url : '/main_page/update_alert_time',
                        type: 'post',
                    });


                } else {
                    $('#is_force').text( next_num );
                }

            },1000);

        } else {
            $('.opt-no-order').click();
        }
    }
});
