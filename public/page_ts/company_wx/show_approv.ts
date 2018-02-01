/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/company_wx-show_approv.d.ts" />

$(function(){
function load_data(){
    $.reload_self_page ( {
		    date_type_config:	$('#id_date_type_config').val(),
		    date_type:	$('#id_date_type').val(),
		    opt_date_type:	$('#id_opt_date_type').val(),
		    start_time:	$('#id_start_time').val(),
		    end_time:	$('#id_end_time').val(),
    });
}

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery          : function() {
            load_data();
        }
    });

	  $('.opt-change').set_input_change_event(load_data);

    $('#id_pull_data').on('click', function() {
        $.do_ajax("/company_wx/pull_approve_data", {});
    });

    $('.opt-detail').on('click', function () {
        var id = $(this).parent().attr('data_id');
        $.do_ajax("/company_wx/get_approve_detail",{
            "id" : id,
        }, function(res) {
            if (res.data) {
                var data = res.data;
                var arr = [];
                
                for (var item in data) {
                    // if (item == 'spname') item = '审批名';
                    // if (item == 'apply_name') item = '申请人';
                    // if (item == 'apply_time') item = '申请时间';
                    // if (item == 'reason') item = '申请原因';
                    arr.push([item, data[item]]);
                }
                $.show_key_value_table("审批详情", arr ,{
                });

            } else {
                alert('没有数据');
            }
        });

    });

    $('.opt-edit').on('click', function () {
        var id = $(this).parent().attr('data_id');
        $.do_ajax("/company_wx/get_approve_detail",{
            "id" : id,
        }, function(res) {
            if (res.data) {
                var data = res.data;
                var arr = ['-', '提示内容'];
                for (var item in data) {
                    arr.push([item, data[item]]);
                }
                // stu_manage
                $.show_key_value_table("更新修改", arr ,{
                    label    : '确认',
                    cssClass : 'btn-warning',
                    action   : function(dialog) {
                        $.do_ajax("/company_wx/update_approval",{
                            "id"    : id,
                            "type"  : data.type
                        });
                    }

                });

            } else {
                alert('没有数据');
            }
        });

    });

});
