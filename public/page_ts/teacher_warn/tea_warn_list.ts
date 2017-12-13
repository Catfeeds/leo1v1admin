/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_warn-tea_warn_list.d.ts" />

$(function(){
function load_data(){
    $.reload_self_page ( {
		    date_type_config:	$('#id_date_type_config').val(),
		    date_type:	$('#id_date_type').val(),
		    opt_date_type:	$('#id_opt_date_type').val(),
		    start_time:	$('#id_start_time').val(),
		    end_time:	$('#id_end_time').val(),
        teacher: $('#id_teacher').val(),
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


    $('#id_teacher').val(g_args.teacher);
    //$.admin_select_user( $("#id_teacher"), "teacher",load_data);

	  $('.opt-change').set_input_change_event(load_data);

    $('.opt-detail').on('click', function() {
        var teacherid = $(this).attr('data_teacher');
        $.do_ajax('/teacher_warn/get_teacher_detail', {
            'teacherid':teacherid
        }, function(res) {
            var arr;
            if (res.data) {
                data = res.data
                arr = [
                    ['老师ID', data.teacherid],
                    ['老师呢称', data.nick],
                ];
            } else {
                arr = [
                    ['', '此老师不存在']
                ]
            }
            $.show_key_value_table("老师详细信息",arr);
        });
    });

    $(".opt-telphone").on("click",function(){
        var teacherid = $(this).parent().attr('data_teacher');

        $.do_ajax("/teacher_warn/get_phone_for_teacherid", {
            'teacherid': parseInt(teacherid)
        }, function(res) {
            if (res.data) {
                console.log(res.data);
                $.do_ajax_t("/ss_deal/call_ytx_phone", {
                    "phone": res.data
                });
            }
        });
        // $(me).parent().find(".opt-edit-new_new").click();
    });

    $('.opt-return-back').on('click', function() {
        var teacherid = $(this).parent().attr('data_teacher');
        $.do_ajax("/teacher_warn/get_return_back_info", {
            'teacherid' : teacherid
        }, function(res) {
            var add_time = '<div>未回访</div>';
            var acc = '<div></div>';
            if (res.data) {
                add_time = '<div>' + res.data.add_time + '</div>';
                acc = '<div>' + res.data.acc + '</div>';
            }
            //r ret_status = $("<select><option value=1>未回访</option><option value=2>已回访</option></select>");
            var record_info = $("<textarea id='record_info'></textarea>");
            var arr = [
                //['回访状态', ret_status],
                ['上次回访时间', add_time],
                ['上次回访人', acc],
                ['回访备注', record_info]
            ];

            $.show_key_value_table("添加回访", arr ,{
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    var re_info = $('#record_info').val();
                    if (!re_info) {
                        alert('回访备注不能为空');
                        location.reload();
                    } else {
                        $.do_ajax('/teacher_warn/add_record_data', {
                            'teacherid':teacherid,
                            'record_info':re_info
                        });
                    }
                }
            });
        });

    });

    $('.opt-return-back-list').on('click', function() {
        var teacherid = $(this).parent().attr('data_teacher');
        $.do_ajax('/teacher_warn/get_return_back_info', {
            'teacherid':teacherid,
            'type':1
        }, function(res) {
            var html_str=$("<div id=\"div_table\"><table class = \"table table-bordered table-striped\"  > <tr><th> 回访时间  <th> 回访内容 <th> 回访人 </tr> </table></div>");
            $.each( res.data ,function(i,item){
                var html = "<tr><td>" + item['add_time'] + "</td><td>" + item['record_info'] + "</td><td>" + item['acc'] + "</td></tr>";
                html_str.find('table').append(html);
            });

            var dlg = BootstrapDialog.show({
                title    : '回访记录',
                message  : html_str ,
                closable : true,
                buttons  : [{
                    label  : '返回',
                    action : function(dialog) {
                        dialog.close();
                    }
                }]
            });
           
        });
    });
});
