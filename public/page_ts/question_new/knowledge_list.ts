/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question_new-knowledge_list.d.ts" />
/// <reference path="../../ztree/jquery.ztree.all.min.js" />
/// <reference path="../../ztree/jquery.ztree.core.js" />
/// <reference path="../../ztree/jquery.ztree.exhide.min.js" />
/// <reference path="../../js/MathJax/MathJax.js" />
/// <reference path="../../page_js/question_edit_new.js" />
/// <reference path="../../js/MathJax/MathJax.js" />
/// <reference path="../g_args.d.ts/question_new-knowledge_edit.d.ts" />


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
    return confirm("确认删除 节点 -- " + treeNode.name + "及下面所有的子节点吗？"); 
}

function onRemove(e,treeId,treeNode){
    var idstr ='' ;
    idstr = getAllChildrenNodes(treeNode,idstr);
    idstr = treeNode.id + idstr;
    var data = {
        'idstr':idstr
    };

    console.log(idstr);

    $.ajax({
        type : "post",
        url : "/question_new/knowledge_dele",
        dataType : "json",
        data:data,
        success : function(res){
            if( res.status == 200 ){
                
                //window.location.reload();
            }else{
                BootstrapDialog.alert('更新出错，请刷新网页');
            }
        },
        error:function(){
            BootstrapDialog.alert('取出错误');
        }
    });

}

function getAllChildrenNodes(treeNode,result){
    if (treeNode.isParent) {
        var childrenNodes = treeNode.children;
        if (childrenNodes) {
            for (var i = 0; i < childrenNodes.length; i++) {
                result += ',' + childrenNodes[i].id;
                result = getAllChildrenNodes(childrenNodes[i], result);
            }
        }
    }
    return result;
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

//编辑或添加子知识点
function edit_know(treeId, treeNode){
    var zTree = $.fn.zTree.getZTreeObj("treeDemo");
    $('.id_mathjax_content').val(treeNode.name);
    $(".knowledge_background").show();
    Cquestion_editor.preview_update(null,$('#id_mathjax_content_0'),$('#MathPreview_0'),'MathPreview_0');
}

//添加根部知识点
function add_knowledge(){
    $(".knowledge_background").show();
}

function close_know(){
    tree_edit_id = null;
    tree_edit_node = null;
    $('.id_mathjax_content').val('');
    $(".knowledge_background").hide();
}

function save_know(){

    //编辑或添加子知识点
    if( tree_edit_node != null ){        
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        //zTree.selectNode(tree_edit_node);
        tree_edit_node.name = $('.id_mathjax_content').val();
        var data = {
            'editType':tree_edit_node.editType,
            'knowledge_id':tree_edit_node.id,
            'father_id':tree_edit_node.pId,
            'title':tree_edit_node.name,
            'subject':$('#id_subject').val()
        }
        console.log(tree_edit_node);
        $.ajax({
            type : "post",
            url : "/question_new/knowledge_add",
            dataType : "json",
            data:data,
            success : function(res){
                if( res.status == 200 ){
                    //新增
                    console.log(tree_edit_node);
                    tree_edit_node.id = parseInt(res.knowledge_id);
                    zTree.editName(tree_edit_node);
                    zTree.cancelEditName(tree_edit_node.name);
                    close_know();
                }else if( res.status == 201 ){
                    //更新
                    console.log(tree_edit_node);
                    zTree.editName(tree_edit_node);
                    zTree.cancelEditName(tree_edit_node.name);
                    close_know();
                }else{
                    BootstrapDialog.alert('更新出错，请刷新网页');
                }
            },
            error:function(){
                BootstrapDialog.alert('取出错误');
            }
        });
    }else{
        var rootData = {
            'editType':1,
            'knowledge_id':'',
            'father_id':0,
            'title':$('.id_mathjax_content').val(),
            'subject':$('#id_subject').val()
        };
        $.ajax({
            type : "post",
            url : "/question_new/knowledge_add",
            dataType : "json",
            data:rootData,
            success : function(res){
                if( res.status == 200 ){
                    //新增
                    window.location.reload();
                }else{
                    BootstrapDialog.alert('更新出错，请刷新网页');
                }
            },
            error:function(){
                BootstrapDialog.alert('取出错误');
            }
        });

    }
}

function beforeRename(treeId,treeNode,newName){
    return true;  
}

function onRename(e,treeId,treeNode){
   return true;
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

    //初始化数学公式
    var mathId = document.getElementById("mathview");
    Cquestion_editor.init_mathjax(mathId);

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


    //进入题目列表页面
    $('#question_list').on('click',function(){
        var subject = $('#id_subject').val();
        window.open('/question_new/question_list?id_open_flag=1&id_subject='+subject);
    });

    //进入知识点显示
    $('#knowledge_pic').on('click',function(){
        var subject = $('#id_subject').val();
        window.open('/question_new/knowledge_get?subject='+subject);
    });

    //教材知识点
    $('#text_book_knowledge').on('click',function(){
        var subject = $('#id_subject').val();
        window.open('/question_new/textbook_knowledge_list?subject='+subject);
    });


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
