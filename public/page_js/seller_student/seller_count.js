/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-seller_count.d.ts" />

function load_data(){
    $.reload_self_page ( {
	    order_by_str: g_args.order_by_str,
        origin_ex : $("#id_origin_ex").val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		groupid:	$('#id_groupid').val()
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
   
	$('#id_groupid').val(g_args.groupid);
    $('#id_origin_ex').val(g_args.origin_ex);

	$('.opt-change').set_input_change_event(load_data);

    $(".opt-comments").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var start_date=$("#id_start_date").val();
        var end_date=$("#id_end_date").val();
        $(this).admin_select_dlg_ajax({

            "opt_type" :  "list", // or "list"
            width:800,
            
            select_no_select_value  :   0, // 没有选择是，设置的值 
            select_no_select_title  :   '未设置', // "未设置"
            select_primary_field : "",
            select_display       : "",
            
            "url"          : "/user_deal/get_comment_list_js",
            //其他参数
            "args_ex" : {
                account    : opt_data.account ,
                start_time : start_date,
                end_time   : end_date
            },
            
            //字段列表
            'field_list' :[
                {
                    title:"时间",
                    width :180,
                    field_name:"revisit_time"
                },{
                    title:"学生电话",
                    width :50,
                    field_name:"phone"

                },{
                    title:"内容",
                    //width :50,
                    render:function(val,item) {
                        return item.operator_note ;
                    }
                }
            ] ,
            //查询列表
            filter_list:[
                /*
                [
                    {
                        size_class: "col-md-4" ,
                        title :"性别",
                        type  : "select" ,
                        'arg_name' :  "gender"  ,
                        select_option_list: [ {
                            value : -1 ,
                            text :  "全部" 
                        },{
                            value :  1 ,
                            text :  "男" 
                        },{
                            value :  2 ,
                            text :  "女" 
                            
                        }]
                    },{
                        size_class: "col-md-8" ,
                        title :"姓名/电话",
                        'arg_name' :  "nick_phone"  ,
                        type  : "input" 
                    }

                ] 
                */
            ],
            
            "auto_close"       : true,
            //选择
            "onChange" : function(val) {
                $.do_ajax( "/seller_student/set_test_lesson_st_arrange_lessonid",{
                    "st_arrange_lessonid" :  val ,
                    "phone" :  phone
                });
            },
            //加载数据后，其它的设置
            "onLoadData"       : null
        });
    });


});


    


