/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/grab_lesson-get_all_grab_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			      grabid:	$('#id_grabid').val(),
			      grab_lesson_link:	$('#id_grab_lesson_link').val(),
			      live_time:	$('#id_live_time').val(),
			      adminid:	$('#id_adminid').val(),
            date_type:	$('#id_date_type').val(),
			      opt_date_type:	$('#id_opt_date_type').val(),
			      start_time:	$('#id_start_time').val(),
			      end_time:	$('#id_end_time').val()

        });
    }


	  $('#id_grabid').val(g_args.grabid);
	  $('#id_grab_lesson_link').val(g_args.grab_lesson_link);
	  $('#id_live_time').val(g_args.live_time);
	  $('#id_adminid').val(g_args.adminid);

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


	  $('.opt-change').set_input_change_event(load_data);

    $(".opt-visit-list").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" :  "list", // or "list"
            "url"      : "/grab_lesson/get_list_by_grabid_js",
            //其他参数
            "args_ex" : {
                grabid:opt_data.grabid
            },
            //字段列表
            'field_list' :[
                {
                title:"visitid",
                render:function(val,item) {
                    return item.visitid;
                }
            },{
                title:"老师",
                render:function(val,item) {
                    return item.tea_nick ;
                }
            },{
                title:"访问时间",
                render:function(val,item) {
                    return item.visit_time ;
                }
            },{
                title:"是否抢课",
                //width :50,
                render:function(val,item) {
                    return $(item.operation_str );
                }
            },{
                title:"抢课时间",
                render:function(val,item) {
                    return item.grab_time ;
                }
            },{
                title:"是否成功",
                render:function(val,item) {
                    return $(item.success_flag_str) ;
                }
            },{
                title:"requireid",
                render:function(val,item) {
                    return item.requireid ;
                }
            },{
                title:"失败原因",
                render:function(val,item) {
                    return item.fail_reason ;
                }
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



/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grabid</span>
                <input class="opt-change form-control" id="id_grabid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grab_lesson_link</span>
                <input class="opt-change form-control" id="id_grab_lesson_link" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">live_time</span>
                <input class="opt-change form-control" id="id_live_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>
*/
