/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/rule_txt-process_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        process_id:	$('#id_process_id').val()
    });
}
$(function(){

    $('.opt-edit').on('click',function(){

        var opt_data=$(this).get_opt_data();

        var id_name = $("<input style='width:90%'/>");
        var id_department = $("<input id='id_department' style='width:90%'/>");
        var id_fit_range = $("<input style='width:90%'/>");
        var id_attention = $("<textarea class=\"textarea\" style=\"height:300px\"/>");
        var id_pro_explain = $("<textarea class=\"textarea\" style=\"height:300px\"/>");
        var id_pro_img = $("<button class='btn' id='id_pro_img'/>");
        var id_img = $("<img src='"+opt_data.pro_img+"' style=\"width:100%\" id=\"img\"/>");

        var modal_title = '流程文档';

        var process_id = opt_data.process_id;
        id_name.val(opt_data.name);
        id_department.val(opt_data.department);
        id_fit_range.val(opt_data.fit_range);
        id_attention.val(opt_data.attention);
        id_pro_explain.val(opt_data.pro_explain);
        id_pro_img.text('上传图片');

        var arr= [
            ["流程名称：", id_name],
            ["职能部门：", id_department],
            ["适用范围：", id_fit_range],
            ["流程说明：", id_pro_explain],
            ["注意事项：", id_attention],
            ["流程图：", id_pro_img],
            ["", id_img],
        ];

        $.show_key_value_table(modal_title, arr,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function() {

                if(id_name.val() =='' || id_department.val() == '' || id_fit_range.val() == '' || id_pro_explain.val() == '' || id_attention.val() == '' ){
                    alert('请把信息填写完整！');
                } else {
                    $.ajax({
                    type     : "post",
                    url      : "/rule_txt/update_process",
                    dataType : "json",
                    data : {
                        'process_id'  : process_id,
                        'name'        : id_name.val(),
                        'department'  : id_department.val(),
                        'fit_range'   : id_fit_range.val(),
                        'attention'   : id_attention.val(),
                        'pro_img'     : $('#img').attr('src'),
                        'pro_explain' : id_pro_explain.val(),
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
            var my_fun = function(){
                $.enum_multi_select_new( $('#id_department'), 'account_role',function(){
                    console.log( $('#id_department').val() );
                    my_fun();
                });
            }
            my_fun();

            $('.textarea').wysihtml5();
            custom_upload_file('id_pro_img',1,function(up, file, info) {
                console.log(file)
                var res = $.parseJSON(file);
                if( res.key!='' ){
                    $('#img').attr('src', qiniu_pub+'/'+res.key);
                }
            }, [], ["jpg","png"],function(){});

        },false,900);
    });

    $('#id_process_id').val(g_args.process_id);

    $('.opt-change').set_input_change_event(load_data);
});
