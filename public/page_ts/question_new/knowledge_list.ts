/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question_new-knowledge_list.d.ts" />
/// <reference path="../../ztree/jquery.ztree.all.min.js" />
/// <reference path="../../ztree/jquery.ztree.core.js" />
/// <reference path="../../ztree/jquery.ztree.exhide.min.js" />
/// <reference path="../../js/MathJax/MathJax.js" />
/// <reference path="../../page_js/question_edit_new.js" />


var setting = {
    view: {
        showIcon: false,
        addHoverDom: addHoverDom,       // 用于当鼠标移动到节点上时，显示用户自定义控件。务必与 setting.view.removeHoverDom 同时使用  
        removeHoverDom: removeHoverDom, // 用于当鼠标移出节点时，隐藏用户自定义控件。务必与 addHoverDom 同时使用          
        selectedMulti: false
    },
    // check: {  
    //     enable: true,  
    //     chkStyle: "checkbox",  
    //     chkboxType: { "Y": "ps", "N": "ps" }  
    // },
    edit: {
        enable: true,
        removeTitle:'删除',
        renameTitle:'重命名',
        showRenameBtn:showRenameBtn,
        showRemoveBtn:showRemoveBtn
    },
    data: {
        simpleData: {
            enable: true
        }
    },
    callback: {
        //onClick: zTreeOnClick,
        beforeDrag: beforeDrag,
        beforeEditName:beforeEditName,
        beforeRemove:beforeRemove,//点击删除时触发，用来提示用户是否确定删除
        beforeRename: beforeRename,//点击编辑时触发，用来判断该节点是否能编辑
        onRemove:onRemove,//删除节点后触发，用户后台操作
        onRename:onRename,//编辑后触发，用于操作后台
    }

}
function beforeDrag(){
    return false;
}

function beforeRemove(treeId,treeNode){
    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
    zTree.selectNode(treeNode);
    return confirm("确认删除 节点 -- " + treeNode.name + " 吗？"); 
}

function beforeEditName(treeId, treeNode) {
    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
    edit_know(treeId, treeNode);
    tree_edit_id = treeId;
    tree_edit_node = treeNode;
    return false;
}

var tree_edit_id;
var tree_edit_node;

function edit_know(treeId, treeNode){
    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
    $('.id_mathjax_content').val(treeNode.name);
    $(".knowledge_background").show();
    Cquestion_editor.preview_update(null,$('#id_mathjax_content_0'),$('#MathPreview_0'),'MathPreview_0');
}

function close_know(){
    tree_edit_id = null;
    tree_edit_node = null;
    $('.id_mathjax_content').val('');
    $(".knowledge_background").hide();
}

function save_know(){
    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
    //zTree.selectNode(tree_edit_node);
    tree_edit_node.name = $('.id_mathjax_content').val();
    zTree.editName(tree_edit_node);
    zTree.cancelEditName(tree_edit_node.name);
    close_know();
}
function beforeRename(treeId,treeNode,newName){
    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
    return true;
    
}

function onRemove(e,treeId,treeNode){
    alert('remove'); 
}

function onRename(e,treeId,treeNode){
    console.log('onrename');
}

//是否显示编辑button
function  showRenameBtn(treeId, treeNode){
    //获取节点所配置的noEditBtn属性值
    if(treeNode.noEditBtn != undefined && treeNode.noEditBtn){
        return false;
    }else
    {
        return true;
    }
}
//是否显示删除button
function showRemoveBtn(treeId, treeNode){
    //获取节点所配置的noRemoveBtn属性值
    if(treeNode.noRemoveBtn != undefined && treeNode.noRemoveBtn){
        return false;
    }else
    {
        return true;
    }
}
var newCount = 1;

function addHoverDom(treeId, treeNode) {
    //给节点加入"新增"button
    var sObj = $("#" + treeNode.tId + "_span");
    if (treeNode.editNameFlag || $("#addBtn_"+treeNode.id).length>0) return;
    var addStr = "<span class='button add' id='addBtn_" + treeNode.id
        + "' title='add node' onfocus='this.blur();'></span>";
    sObj.after(addStr);
    var btn = $("#addBtn_"+treeNode.id);
    if (btn) btn.bind("click", function(){
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        zTree.addNodes(treeNode, {id:(100 + newCount), pId:treeNode.id, name:"new node" + (newCount++)});
        return false;
    });
    

};
function removeHoverDom(treeId, treeNode) {
    $("#addBtn_"+treeNode.id).unbind().remove();
};

$(function(){
    //var zNodes = $('#zNodes').val();
    $.fn.zTree.init($("#treeDemo"), setting, zNodes);


    $("#show_all_knowledge").click(function(){
        var treeObj = $.fn.zTree.getZTreeObj('treeDemo');
        treeObj.expandAll(true); 
    });
    
    Enum_map.append_option_list("subject", $("#id_subject"),true,[1,2,3,4,5,6,7,8,9,10,11]);
 
    $("#id_subject").val(g_args.id_subject);
    $('.opt-change').set_input_change_event(load_data);
    
    function load_data(){

        var data = {
            id_subject : $("#id_subject").val(),
        };

        $.reload_self_page(data);
    }


    //进入知识点列表页面
    $('#question_list').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open('/question_new/question_list');
    });

    //添加根部知识点
    $('#id_add_knowledge').on('click',function(){
        var level = 0;
        var father_id = 0;
        var editType = 1;
        var father_subject = $('#id_subject').val();
        window.open('/question_new/knowledge_edit?level='+level+'&father_id='+father_id+'&editType='+editType+'&father_subject='+father_subject);
    });

    //添加子知识点
    $('.add_son').on('click',function(){
        var opt_data = $(this).get_opt_data();
        var level = parseInt(opt_data.level) + 1;
        var father_id = opt_data.knowledge_id;
        var father_subject = $('#id_subject').val();
        var editType = 1;
        window.open('/question_new/knowledge_edit?level='+level+'&father_id='+father_id+'&editType='+editType+'&father_subject='+father_subject);
    });

    //进入知识点编辑页面
    $('.opt-set').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var knowledge_id = opt_data.knowledge_id;
        var father_subject = $('#id_subject').val();
        var editType = 2;
        window.open('/question_new/knowledge_edit?knowledge_id='+knowledge_id+'&editType='+editType+'&father_subject='+father_subject);
    });

    //删除知识点
    $('.opt-del').on('click',function(){
        var opt_data = $(this).get_opt_data();

        var knowledge_id = opt_data.knowledge_id;
        var title = "你确定删除本知识点,标题为" + opt_data.title + "？";
        var data = {
            'knowledge_id':knowledge_id
        };

        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/question_new/knowledge_dele",data);
            }
        });

    })

    //初始化每个公式显示框
    $('.MathPreview').each(function(){
        var mathId = $(this).attr('id');
        var id_index = get_content_id(mathId);
        var id_mathjax_content = $('#id_mathjax_content_'+id_index);
        var MathPreview = $('#'+mathId);
        mathId = document.getElementById(mathId);
        Cquestion_editor.init_mathjax(mathId);
    });

    var id_question_type = null;
    //失去光标事件
    $('.id_mathjax_content').blur(function(){
        var id_mathjax = $(this).attr('id');
        var id_mathjax_content = $('#'+id_mathjax);
        var id_index = get_content_id(id_mathjax);
        var mathId = 'MathPreview_'+id_index;
        var MathPreview = $('#'+mathId);
        var val = $(this).val();
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

})
function get_content_id(mathId){
    var mathId_index = mathId.lastIndexOf('_')+1;
    return mathId.substr(mathId_index);
}
