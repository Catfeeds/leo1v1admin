/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question_new-answer_type_list.d.ts" />


$(function(){

    Enum_map.append_option_list("subject", $("#id_subject"),true);
    Enum_map.append_option_list("boolean", $("#id_open_flag"),true,[1,0]);

    $("#id_subject").val(g_args.id_subject);
    $("#id_open_flag").val(g_args.id_open_flag);

    $('.opt-change').set_input_change_event(load_data);
    
    function load_data(){
        var data = {
            id_subject : $("#id_subject").val(),
            id_open_flag : $("#id_open_flag").val(),
        };
        //console.log(data);
        $.reload_self_page(data);
    }

    //编辑
    $('.edit_textbook').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var id_name = $("<input style='width:100%'/>");
        var id_answer_type_no = $("<input/>");
        var id_subject =$("<select/>");
        Enum_map.append_option_list("subject", id_subject,true);
        id_subject.val(opt_data.subject);
        id_name.val(opt_data.name);
        id_answer_type_no.val(opt_data.answer_type_no);
        var arr=[
            ["答案步骤科目", id_subject ],
            ["答案步骤名字", id_name ],
            ["答案步骤序号", id_answer_type_no ],
        ];
        $.show_key_value_table("编辑答案步骤", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var subject = id_subject.val();
                var name = id_name.val();
                var answer_type_no = id_answer_type_no.val();
                var data = {
                    'id':opt_data.id,
                    'subject':subject,
                    'name':name,
                    'answer_type_no':answer_type_no,
                    'editType':2,
                }
                if(!name){
                    BootstrapDialog.alert("教材必填");
                    return false;
                }   

                if(!answer_type_no){
                    BootstrapDialog.alert("答案步骤序号必填");
                    return false;
                }   

                $.ajax({
                    type     :"post",
                    url      :"/question_new/answer_type_add",
                    dataType :"json",
                    data     :data,
                    success : function(res){
                        console.log(res);
                        BootstrapDialog.alert(res.msg);
                        if( res.status == 200){
                            window.location.reload();
                        }
                    }
                });
            }
        })

    });

    $('#add_textbook').on('click',function(){
        var id_name = $("<input style='width:100%'/>");
        var id_subject =$("<select/>");
        var id_answer_type_no = $("<input/>");
        Enum_map.append_option_list("subject", id_subject,true);
        id_subject.val($("#id_subject").val());
        var arr=[
            ["答案步骤科目", id_subject ],
            ["答案步骤名字", id_name ],
            ["答案步骤序号", id_answer_type_no ],
        ];
        $.show_key_value_table("添加答案步骤", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var subject = id_subject.val();
                var name = id_name.val();
                var answer_type_no = id_answer_type_no.val();
                var data = {
                    'subject':subject,
                    'name':name,
                    'answer_type_no':answer_type_no,
                    'editType':1,
                }
                if(!name){
                    BootstrapDialog.alert("答案步骤名字必填");
                    return false;
                }   

                if(!answer_type_no){
                    BootstrapDialog.alert("答案步骤序号必填");
                    return false;
                }   

                $.ajax({
                    type     :"post",
                    url      :"/question_new/answer_type_add",
                    dataType :"json",
                    data     :data,
                    success : function(res){
                        console.log(res);
                        BootstrapDialog.alert(res.msg);
                        if( res.status == 200){
                            window.location.reload();
                        }
                    }
                });
            }
        })

    });

    $('.lock_question_know').click(function(){
        var opt_data=$(this).get_opt_data();
        var data = {
            'id':opt_data.id,
            'subject':opt_data.subject,
            'name':opt_data.name,
            'answer_type_no':opt_data.answer_type_no,
            'open_flag':0,
            'editType':2,
        };
        publicAjax(data);
    })

    $('.unlock_question_know').click(function(){
        var opt_data=$(this).get_opt_data();
        var data = {
            'id':opt_data.id,
            'subject':opt_data.subject,
            'name':opt_data.name,
            'answer_type_no':opt_data.answer_type_no,
            'open_flag':1,
            'editType':2,
        };
        publicAjax(data);

    })

})
function publicAjax(data){
    $.ajax({
        type     :"post",
        url      :"/question_new/answer_type_add",
        dataType :"json",
        data     :data,
        success : function(res){
            console.log(res);
            BootstrapDialog.alert(res.msg);
            if( res.status == 200){
                window.location.reload();
            }
        }
    });

}
