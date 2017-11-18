/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/rule_txt-rule_detail.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        rule_id:	$('#id_rule_id').val()
    });
}
$(function(){
    var rule_id = $('#rule').data('id');

    $('#id_rule_id').val(g_args.rule_id);

    $('.opt-add').on('click',function(){
        edit_info();
    });

    $('.opt-edit').on('click',function(){
        var opt_data=$(this).get_opt_data();
        edit_info(opt_data);
    });

    $('.opt-del').on('click',function(){
        var opt_data=$(this).get_opt_data();
        if(confirm('确定要删除？')){
        $.ajax({
            type     : "post",
            url      : "/rule_txt/del_rule_detail",
            dataType : "json",
            data : {
                'detail_id' : opt_data.detail_id,
            } ,
            success : function(result){
                window.location.reload();
            }
        });

        }
    });


    var edit_info = function(data=''){
        var id_name = $("<input/>");
        var id_level = $("<select/>");
        var id_content = $("<textarea class=\"textarea\" style=\"height:300px\"/>");
        var id_deduct_marks = $("<select/>");
        var id_punish_type = $("<input/>");
        var id_add_punish = $("<textarea class=\"textarea2\" style=\"height:200px\"/>");

        Enum_map.append_option_list("rule_level",id_level,true);
        Enum_map.append_option_list("deduct_marks",id_deduct_marks,true);
        var modal_title = '添加规则';

        if (data == ''){
            var detail_id = 0;
            var rank_num = 0;
        } else {
            var detail_id = data.detail_id;
            var rank_num = data.rank_num;
            id_name.val(data.name);
            id_level.val(data.level);
            id_content.val(data.content);
            id_deduct_marks.val(data.deduct_marks);
            id_punish_type.val(data.punish_type);
            id_add_punish.val(data.add_punish);
        }
        var arr= [
            ["规则名称：", id_name],
            ["规则等级：", id_level],
            ["规则明细：", id_content],
            ["质检扣分：", id_deduct_marks],
            ["处罚方式：", id_punish_type],
            ["附加处罚：", id_add_punish],
        ];

        $.show_key_value_table(modal_title, arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function() {

                if(id_name.val() =='' || id_content.val() == '' || id_punish_type.val() == '' ){
                    alert('请把信息填写完整！');
                } else {
                    $.ajax({
                    type     : "post",
                    url      : "/rule_txt/add_or_update_rule_detail",
                    dataType : "json",
                    data : {
                        'detail_id'    : detail_id,
                        'rule_id'      : rule_id,
                        'rank_num'     : rank_num,
                        'name'         : id_name.val(),
                        'level'        : id_level.val(),
                        'content'      : id_content.val(),
                        'deduct_marks' : id_deduct_marks.val(),
                        'punish_type'  : id_punish_type.val(),
                        'add_punish'   : id_add_punish.val(),
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
            }
        },function(){
            $('.textarea').wysihtml5()
            $('.textarea2').wysihtml5()
        },false,900);
    };

    $('.opt-up').on('click',function(){
        var opt_data=$(this).get_opt_data();
        up_or_down('up',opt_data);
    });

    $('.opt-down').on('click',function(){
        var opt_data=$(this).get_opt_data();
        up_or_down('down',opt_data);
    });
    var up_or_down = function(opt_type, data){
        $.ajax({
                type     : "post",
                url      : "/rule_txt/up_or_down",
                dataType : "json",
                data : {
                    'detail_id' : data.detail_id,
                    'rule_id'   : rule_id,
                    'rank_num'  : data.rank_num,
                    'level'     : data.level,
                    'type'      : opt_type,
                } ,
                success : function(result){
                    window.location.reload();
                }
            });
    }

    $('.opt-punish').on('click',function(){
        var punish = $(this).data('punish');
        BootstrapDialog.alert(punish);
    });
    $('.opt-change').set_input_change_event(load_data);
});
