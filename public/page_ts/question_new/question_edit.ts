/// <reference path="../common.d.ts" />
/// <reference path="../../page_js/question_edit_new.js" />
/// <reference path="../../js/MathJax/MathJax.js" />
/// <reference path="../g_args.d.ts/question_new-question_edit.d.ts" />

$(function(){

    Enum_map.append_option_list("question_difficult_new",$("#question_difficult"),true,[1,2,3,4,5]);
    Enum_map.append_option_list("subject", $("#id_subject"),false,[1,2,3,4,5,6,7,8,9,10,11]);
    Enum_map.append_option_list("boolean", $("#id_open_flag"),true);
    $("#id_subject").val(g_args.subject);
    $('#id_open_flag').val(1);
    if(g_args.editType == 2){
        var editData = $('#editData').val();
        editData = JSON.parse(editData);
        $("#id_open_flag").val(editData.open_flag);
        $("#question_difficult").val(editData.difficult);
        $("#id_score").val(editData.score);
        $("#id_mathjax_content_0").val(editData.title);
        $("#id_mathjax_content_1").val(editData.detail);


        Cquestion_editor.preview_update(null,$("#id_mathjax_content_0"),$("#MathPreview_0"),'MathPreview_0');
        Cquestion_editor.preview_update(null,$("#id_mathjax_content_1"),$("#MathPreview_1"),'MathPreview_1');
    }

    var id_question_type = null;
    var domain = 'http://7u2f5q.com2.z0.glb.qiniucdn.com/';


    //初始化每个公式显示框
    $('.MathPreview').each(function(){
        var mathId = $(this).attr('id');
        var id_index = get_content_id(mathId);
        var id_mathjax_content = $('#id_mathjax_content_'+id_index);
        var MathPreview = $('#'+mathId);
        mathId = document.getElementById(mathId);
        Cquestion_editor.init_mathjax(mathId);
        //上传图片
        Cquestion_editor.custom_upload( $('#id_mathjax_add_pic_'+id_index)[0],$('#id_mathjax_add_pic_div_'+id_index)[0],domain,null,id_mathjax_content,MathPreview,mathId); 
    });

    //失去光标事件
    $('.id_mathjax_content').blur(function(){
        var id_mathjax = $(this).attr('id');
        var id_mathjax_content = $('#'+id_mathjax);
        var id_index = get_content_id(id_mathjax);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);
        var val = $(this).val();
        if (!val.match(/\$/)) {
            //$(this).val(Cquestion_editor.reset_latex_str(val));
        }
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    });

    //输入事件
    $('.id_mathjax_content').bind('input propertychange',function(){
        var id_mathjax = $(this).attr('id');
        var id_mathjax_content = $('#'+id_mathjax);
        var id_index = get_content_id(id_mathjax);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    }); 

    //点击传符号
    $('.dropdown-menu li button').click(function(){
        var navbar = '$'+$(this).find('script[type="math/tex"]').html()+'$';
        var id = $(this).parents('.navbar-nav').attr('id');
        var id_index = get_content_id(id);
        var id_mathjax_content = $('#id_mathjax_content_'+id_index);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);

        id_mathjax_content.insertAtCaret(navbar);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    })

    //插入括号
    $('.add_kuo_hao').click(function(){
        var kuohao = '(  )';
        var id = $(this).attr('id');
        var id_index = get_content_id(id);
        var id_mathjax_content = $('#id_mathjax_content_'+id_index);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);
        id_mathjax_content.insertAtCaret(kuohao);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    })

    //添加横线
    $('.add_under_line').click(function(){
        var kuohao = '____';
        var id = $(this).attr('id');
        var id_index = get_content_id(id);
        var id_mathjax_content = $('#id_mathjax_content_'+id_index);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);

        id_mathjax_content.insertAtCaret(kuohao);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);

    })

    //保存题目
    $("#save_know").click(function(){
        var data = {
            'editType':g_args.editType,
            'question_id':g_args.question_id,
            'score':$('#id_score').val(),
            'difficult':$('#question_difficult').val(),
            'title':$('#id_mathjax_content_0').val(),
            'detail':$('#id_mathjax_content_1').val(),
            'open_flag':$('#id_open_flag').val(),
            'subject':$('#id_subject').val()
        };
        $.ajax({
            type : "post",
            url : "/question_new/question_add",
            dataType : "json",
            data:data,
            success : function(res){
                BootstrapDialog.alert(res.msg);
                if( res.status = 200 ){
                    //window.close();
                }
            },
            error:function(){
                BootstrapDialog.alert('取出错误');
            }
        });

    })
})

function get_content_id(mathId){
    var mathId_index = mathId.lastIndexOf('_')+1;
	  return mathId.substr(mathId_index);
}
