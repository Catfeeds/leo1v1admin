/// <reference path="../common.d.ts" />
/// <reference path="../../page_js/question_edit_new.js" />
/// <reference path="../../js/MathJax/MathJax.js" />
/// <reference path="../g_args.d.ts/question_new-knowledge_edit.d.ts" />
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

function open_know(index){
    if( zNodes ==''){
        BootstrapDialog.alert('暂无知识点，请添加');
        return false;
    }
    var obj = $("#knowledge_exits_"+index);
    if( obj.find('span').length > 0 ){
        var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
        obj.find('span').each(function(){
            var node = treeObj.getNodeByParam("id", $(this).attr('knowledge_id'), null);
            treeObj.checkNode(node, true, true);
        })
    }
    $('#save_knowledge').attr({"answer_id":index});
    $('.knowledge_all').show();
}

function close_know(){
    var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
    treeObj.checkAllNodes(false);
    $('#save_knowledge').attr({"answer_id":""});
    $('.knowledge_all').hide();
}

function save_know(){
    var treeObj = $.fn.zTree.getZTreeObj("treeDemo");
    var nodes = treeObj.getCheckedNodes();
    //var checkNode = '';
    var html = '';
    if(nodes.length>0){
        for(var i = 0;i<nodes.length;i++){
            html += "<span knowledge_id='"+nodes[i].id+"'>" + nodes[i].name + "</span>";
        }
    }
    var index = $('#save_knowledge').attr('answer_id');
    $('#knowledge_exits_'+index).html(html);
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

    $("#hide_all_knowledge").click(function(){
        var treeObj = $.fn.zTree.getZTreeObj('treeDemo');
        treeObj.expandAll(false); 
    });

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
        Cquestion_editor.preview_update(id_question_type,id_mathjax_content,MathPreview,mathId);

        var answer_type = $(this).parents('.answer_step').find('.answer_type').attr('id');
        Enum_map.append_option_list("answer_type",$("#"+answer_type),true);
        if( answer_type != 'answer_type_0'){
            var answer_type_value = $(this).parents('.answer_step').find('.answer_type_value').val();
            //console.log(answer_type_value);
            $("#"+answer_type).val(answer_type_value);
        } 
    });

    // 为每一个textarea绑定事件使其高度自适应
    $.each($("textarea"), function(i, n){
        Cquestion_editor.autoTextarea($(n)[0]);
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

    //保存答案
    $(".answer-save").click(function(){
        var obj = $(this).parents('.answer_step');

        //旧的知识点
        var knowledge_old = obj.find('.knowledge_old').val();
        //新的知识点
        var knowledge_new = '';
        if( obj.find(".knowledge_exits").find('span').length > 0 ){
            obj.find('.knowledge_exits span').each(function(){
                knowledge_new += $(this).attr('knowledge_id') + ','
            })
            knowledge_new = knowledge_new.substring(0, knowledge_new.length-1);
        }

        var data = {
            'editType':obj.find('.editType').val(),
            'question_id':$('#question_id').val(),
            'answer_id':obj.find('.answer_id').val(),
            'step':obj.find('.step').val(),
            'answer_type':obj.find('.answer_type').val(),
            'detail':obj.find('.id_mathjax_content').val(),
            'score':obj.find('.answer_score').val(),
            'knowledge_new':knowledge_new,
            'knowledge_old':knowledge_old
        };
        
        $.ajax({
            type : "post",
            url : "/question_new/answer_add",
            dataType : "json",
            data:data,
            success : function(res){
                BootstrapDialog.alert(res.msg);
                if( res.status = 200 ){
                    window.location.reload();
                }
            },
            error:function(){
                BootstrapDialog.alert('取出错误');
            }
        });

    })

    //删除答案
    $('.answer-dele').click(function(){
        var obj = $(this).parents('.answer_step');
        var data = {
            'answer_id':obj.find('.answer_id').val(),
        };
        var title = "你确定删除本步骤";
       
        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/question_new/answer_dele",data);
            }
        });

    })
})

function get_content_id(mathId){
    var mathId_index = mathId.lastIndexOf('_')+1;
	  return mathId.substr(mathId_index);
}
