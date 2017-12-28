/// <reference path="../common.d.ts" />
/// <reference path="../../page_js/question_edit_new.js" />
/// <reference path="../../js/MathJax/MathJax.js" />
/// <reference path="../g_args.d.ts/question_new-question_edit.d.ts" />
/// <reference path="../../ztree/jquery.ztree.all.min.js" />

var setting = {
    view: {
        showIcon: false,
    },
    check: {  
        enable: true,  
        chkStyle: "checkbox",  
        chkboxType: { "Y": "", "N": "" }  
    },
    data: {
        simpleData: {
            enable: true
        }
    },
    callback: {
       onClick: zTreeOnClick, 
    }
}

function open_know(){
    if( $("#knowledge_exits").find('span').length > 0 ){
        var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
        $('#knowledge_exits span').each(function(){
            var node = treeObj.getNodeByParam("id", $(this).attr('knowledge_id'), null);
            treeObj.checkNode(node, true, true);
        })
    }
    $('.knowledge_all').show();
}
function close_know(){
    var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
    treeObj.checkAllNodes(false);
    $('.knowledge_all').hide();
}

function save_know(){
    var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
    var nodes = treeObj.getCheckedNodes();
    //var checkNode = '';
    var html = '';
    if(nodes.length>0){
        for(var i = 0;i<nodes.length;i++){
            //checkNode += nodes[i].id + ",";
            html += "<span knowledge_id='"+nodes[i].id+"'>" + nodes[i].name + "</span>";
        }
        //checkNode = checkNode.substring(0, checkNode.length-1);
    }

    $('#knowledge_exits').html(html);
    close_know();
}

function zTreeOnClick(event, treeId, treeNode){
    var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
    treeObj.expandNode(treeNode, true, true, true);
}

$(function(){
    $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    $("#show_all_knowledge").click(function(){
        var treeObj = $.fn.zTree.getZTreeObj('treeDemo');
        treeObj.expandAll(true); 
    });

    var id_question_type;
    var domain = 'http://7u2f5q.com2.z0.glb.qiniucdn.com/';

    Enum_map.append_option_list("question_difficult_new",$("#question_difficult"),true,[1,2,3,4,5]);
    Enum_map.append_option_list("subject", $("#id_subject"),false,[1,2,3,4,5,6,7,8,9,10,11]);
    Enum_map.append_option_list("boolean", $("#id_open_flag"),true);
    Enum_map.append_option_list("question_resource_type", $("#id_question_resource_type"),true);

    $.each($("textarea"), function(i, n){
        Cquestion_editor.autoTextarea($(n)[0]);
    });

    $("#id_subject").val(g_args.subject);
    $('#id_open_flag').val(1);
    if(g_args.editType == 2){
        var editData = $('#editData').val();
        editData = JSON.parse(editData);
        $("#id_open_flag").val(editData.open_flag);
        $("#question_difficult").val(editData.difficult);
        $("#id_score").val(editData.score);
        $("#id_mathjax_content_0").val(editData.title);

        var detail = editData.detail;
        detail = detail.replace(/<br\/>/g,'\n');
        detail = detail.replace(/&nbsp/g, ' ');

        detail = detail.replace(/<img src=\'/g,'![](');
        detail = detail.replace(/\'\/>/g,')[]&');

        $("#id_mathjax_content_1").val(detail);
        $('#question_type').val(editData.question_type);
        $('#id_question_resource_name').val(editData.question_resource_name);
        $('#id_question_resource_type').val(editData.question_resource_type);

        id_question_type = $("#question_type").val();

        Cquestion_editor.preview_update(null,$("#id_mathjax_content_0"),$("#MathPreview_0"),'MathPreview_0');
        Cquestion_editor.preview_update(id_question_type,$("#id_mathjax_content_1"),$("#MathPreview_1"),'MathPreview_1');
    }

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
        id_question_type = $("#question_type").val();
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
        id_question_type = $("#question_type").val();
        var id_mathjax = $(this).attr('id');
        var id_mathjax_content = $('#'+id_mathjax);
        var id_index = get_content_id(id_mathjax);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    }); 

    $('#id_mathjax_q_A,#id_mathjax_q_B,#id_mathjax_q_C,#id_mathjax_q_D').bind('input propertychange',function(){
        id_question_type = $("#question_type").val();
        var id_mathjax_content = $('#id_mathjax_content_1');
        var mathId = 'MathPreview_1';
        var MathPreview = $('#MathPreview_1');
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);

    });

    var current_input;
    var can_input = new Array('id_mathjax_content_0','id_mathjax_content_1','id_mathjax_q_A','id_mathjax_q_B','id_mathjax_q_C','id_mathjax_q_D');
    //console.log(can_input);
    document.onclick = function (e) {
        var current_id = e.target.getAttribute("id");
        if( jQuery.inArray(current_id, can_input) > 0){
            current_input = $('#'+current_id);
            //console.log(current_input);
        }
    }

    //点击传符号
    $('.dropdown-menu li button').click(function(){
        id_question_type = $("#question_type").val();
        var navbar = '$'+$(this).find('script[type="math/tex"]').html()+'$';
        var id = $(this).parents('.navbar-nav').attr('id');
        var id_index = get_content_id(id);
        var id_mathjax_content = $('#id_mathjax_content_'+id_index);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);

        current_input.insertAtCaret(navbar);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    })

    //插入括号
    $('.add_kuo_hao').click(function(){
        id_question_type = $("#question_type").val();
        var kuohao = '(  )';
        var id = $(this).attr('id');
        var id_index = get_content_id(id);
        var id_mathjax_content = $('#id_mathjax_content_'+id_index);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);
        current_input.insertAtCaret(kuohao);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);
    })

    //添加横线
    $('.add_under_line').click(function(){
        id_question_type = $("#question_type").val();
        var kuohao = '____';
        var id = $(this).attr('id');
        var id_index = get_content_id(id);
        var id_mathjax_content = $('#id_mathjax_content_'+id_index);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);

        current_input.insertAtCaret(kuohao);
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);

    })

    //编辑答案
    $('#eidt_answer').click(function(){
        var question_id = $('#question_id').val();
        window.open('/question_new/answer_edit?question_id='+question_id);
    });

    //保存题目
    $("#save_know").click(function(){

        //旧的知识点
        var knowledge_old = $('#knowledge_old').val();
        //新的知识点
        var knowledge_new = '';
        if( $("#knowledge_exits").find('span').length > 0 ){
            $('#knowledge_exits span').each(function(){
                knowledge_new += $(this).attr('knowledge_id') + ',';
            })
                knowledge_new = knowledge_new.substring(0, knowledge_new.length-1);
        }
        //console.log(knowledge_new);
        var detail = $('#id_mathjax_content_1').val();
        detail = detail.replace(/\n/g, '<br/>');
        detail = detail.replace(/[ ]/g, '&nbsp');
        detail = detail.replace(/(\!\[\]\()/g,"<img src='");
        detail = detail.replace(/(\)\[\]\&)/ig,"'/>");

        var option_A = $("#id_mathjax_q_A").val();
        option_A = option_A.replace(/\n/g, '<br/>');
        var option_A_id = $("#id_mathjax_q_A").attr("option_id");

        var option_B = $("#id_mathjax_q_B").val();
        option_B = option_B.replace(/\n/g, '<br/>');
        var option_B_id = $("#id_mathjax_q_B").attr("option_id");

        var option_C = $("#id_mathjax_q_C").val();
        option_C = option_C.replace(/\n/g, '<br/>');
        var option_C_id = $("#id_mathjax_q_C").attr("option_id");

        var option_D = $("#id_mathjax_q_D").val();
        option_D = option_D.replace(/\n/g, '<br/>');
        var option_D_id = $("#id_mathjax_q_D").attr("option_id");
        
        var data = {
            'editType':g_args.editType,
            'question_id':g_args.question_id,
            'option_A':option_A,
            'option_B':option_B,
            'option_C':option_C,
            'option_D':option_D,
            'option_A_id':option_A_id,
            'option_B_id':option_B_id,
            'option_C_id':option_C_id,
            'option_D_id':option_D_id,
            'score':$('#id_score').val(),
            'difficult':$('#question_difficult').val(),
            'title':$('#id_mathjax_content_0').val(),
            'detail':detail,
            'open_flag':$('#id_open_flag').val(),
            'subject':$('#id_subject').val(),
            'question_type':$('#question_type').val(),
            'question_resource_name':$('#id_question_resource_name').val(),
            'question_resource_type':$('#id_question_resource_type').val(),
            'knowledge_old':knowledge_old,
            'knowledge_new':knowledge_new
        };

        // console.log(data);
        // return false;
        
        $.ajax({
            type : "post",
            url : "/question_new/question_add",
            dataType : "json",
            data:data,
            success : function(res){
                if( res.status == 200 ){                  
                    BootstrapDialog.alert(res.msg);
                    var subject = $('#id_subject').val();
                    var question_id = res.question_id;
                    window.location = '/question_new/question_edit?editType=2&question_id='+question_id+'&subject='+subject;
                }else if( res.status == 400){
                    BootstrapDialog.alert(res.msg);
                }else{
                    window.location.reload();
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
