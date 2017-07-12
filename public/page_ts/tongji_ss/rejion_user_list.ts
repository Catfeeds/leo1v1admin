/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-rejion_user_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            origin_ex:	$('#id_origin_ex').val(),
            date_type_config:	$('#id_date_type_config').val(),
            date_type:	$('#id_date_type').val(),
            opt_date_type:	$('#id_opt_date_type').val(),
            start_time:	$('#id_start_time').val(),
            end_time:	$('#id_end_time').val(),
            seller_student_status:	$('#id_seller_student_status').val(),
            need_count:	$('#id_need_count').val()
        });
    }

  Enum_map.append_option_list("seller_student_status",$("#id_seller_student_status"));

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

    $('#id_origin_ex').val(g_args.origin_ex);
    $('#id_need_count').val(g_args.need_count);
  $('#id_seller_student_status').val(g_args.seller_student_status);


    $('.opt-change').set_input_change_event(load_data);

    //点击进入个人主页
    $('.opt-user').on('click', function () {
        var opt_data= $(this).get_opt_data();
        $.wopen('/stu_manage?sid=' + opt_data.userid);
    });



    $(".opt-post-info").on("click",function(){
        var opt_data=$(this).get_opt_data();

        $(this).admin_select_dlg_ajax({
            "opt_type" :  "list", // or "list"
            "url"          : "/ss_deal/get_seller_origin_list_js",
            //其他参数
            "args_ex" : {
                "userid": opt_data.userid
            },
            //字段列表
            'field_list' :[
                {
                    title:"报名时间",
                    render:function(val,item) {return item.add_time;}
                },{
                    title:"报名渠道",
                    render:function(val,item) {return item.origin;}

                },{
                    title:"科目",
                    render:function(val,item) {return item.subject_str;}

                },{
                    title:"状态",
                    render:function(val,item) {return item.seller_student_status_str;}
                }
            ] ,
            filter_list: [],

            "auto_close"       : true,
            //选择
            "onChange"         : null,
            //加载数据后，其它的设置
            "onLoadData"       : null,

        });
    });


});
