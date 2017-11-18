/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/rule_txt-get_all.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		    date_type_config:	$('#id_date_type_config').val(),
		    date_type:	$('#id_date_type').val(),
		    opt_date_type:	$('#id_opt_date_type').val(),
		    start_time:	$('#id_start_time').val(),
		    end_time:	$('#id_end_time').val()
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

    $('.opt-edit-rule').on('click',function(){
        var opt_data=$(this).get_opt_data();
        edit_info(opt_data);
    });
    $('.opt-add-rule').on('click', function(){
        edit_info();
    });
    var edit_info = function(data=''){
        var id_title = $("<input style='width:100%'/>");
        var id_tip = $("<textarea class='textarea' style='height:200px;'/>");
        var modal_title = '新建规则';

        if (data == ''){
            var id = 0;
        } else {
            var id = data.rule_id;
            id_title.val(data.title);
            id_tip.val(data.tip);
        }
        var arr= [
            ["标题：", id_title],
            ["重要提示：", id_tip],
        ];

        $.show_key_value_table(modal_title, arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function() {
                $.ajax({
                    type     : "post",
                    url      : "/rule_txt/add_or_update_title",
                    dataType : "json",
                    data : {
                        'id'    : id,
                        'title' : id_title.val(),
                        'tip'   : id_tip.val(),
                    } ,
                    success : function(result){
                        if(result.ret == 0){
                            window.location.reload();
                        } else {
                            alert(result.info);
                        }
                    }
                });
            }
        },function(){
            $('.textarea').wysihtml5()
        },false,600);
    };

    $('.opt-add-pro').on('click',function(){
        edit_pro();
    });
    $('.opt-edit-pro').on('click',function(){
        var opt_data=$(this).get_opt_data();
        edit_pro(opt_data);
    });

    var edit_pro = function(data=''){
        var id_name = $("<input style='width:100%'/>");
        var modal_title = '新建流程';

        if (data == ''){
            var id = 0;
        } else {
            var id = data.process_id;
            id_name.val(data.name);
        }
        var arr= [
            ["流程：", id_name],
        ];

        $.show_key_value_table(modal_title, arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function() {
                $.ajax({
                    type     : "post",
                    url      : "/rule_txt/add_or_update_name",
                    dataType : "json",
                    data : {
                        'process_id' : id,
                        'name'       : id_name.val(),
                    } ,
                    success : function(result){
                        if(result.ret == 0){
                            window.location.reload();
                        } else {
                            alert(result.info);
                        }
                    }
                });
            }
        },'',false,600);
    };


    $('.opt-rule-detail').on('click', function(){
        var rule_id = $(this).data('id');
        $.wopen('/rule_txt/rule_detail?rule_id='+rule_id);
    });
    $('.opt-pro').on('click', function(){
        var process_id = $(this).data('id');
        $.wopen('/rule_txt/process_info?process_id='+process_id);
    });

	  $('.opt-change').set_input_change_event(load_data);
});
