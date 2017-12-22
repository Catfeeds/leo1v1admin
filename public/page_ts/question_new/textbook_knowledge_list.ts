/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question_new-textbook_knowledge_list.d.ts" />
/// <reference path="../../ztree/jquery.ztree.all.min.js" />
/// <reference path="../../ztree/jquery.ztree.core.js" />
/// <reference path="../../ztree/jquery.ztree.exhide.min.js" />


var setting = {
    view: {
        showIcon: false,
        selectedMulti: false
    },
    // check: {  
    //     enable: true,  
    //     chkStyle: "checkbox",  
    //     chkboxType: { "Y": "ps", "N": "ps" }  
    // },
    edit: {
        enable: false,
    },
    data: {
        simpleData: {
            enable: true
        }
    },
    callback: {
        onClick:zExitOnClick,
        beforeDrag: beforeDrag,
    }
}

var setting2 = {
    view: {
        showIcon: false,
        selectedMulti: true
    },
    check: {  
        enable: true,  
        chkStyle: "checkbox",  
        chkboxType: { "Y": "ps", "N": "ps" }  
    },
    edit: {
        enable: false,
    },
    data: {
        simpleData: {
            enable: true
        }
    },
    callback: {
        onClick: zTreeOnClick,
        beforeDrag: beforeDrag,
    }
}

function beforeDrag(){
    return false;
}

function add_knowledge(){
    $(".knowledge_background").show();
}

function close_know(){
    $(".knowledge_background").hide();
}

function save_know(){

    //编辑或添加子知识点
     
    var treeObj = $.fn.zTree.getZTreeObj("all_knowledge");
    var nodes = treeObj.getCheckedNodes();
    var checkNode = '';
    if(nodes.length>0){
        for(var i = 0;i<nodes.length;i++){
            checkNode += nodes[i].id + ",";
        }
        checkNode = checkNode.substring(0, checkNode.length-1);
    }

    var data = {
        'knowledge_old':$('#knowledge_old').val(),
        'knowledge_new':checkNode,
        'subject' : $("#id_subject").val(),
        'grade' : $("#id_grade").val(),
        'textbook_id' : $("#id_textbook").val(),
    }
    
    $.ajax({
        type : "post",
        url : "/question_new/textbook_knowledge_add",
        dataType : "json",
        data:data,
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

function zTreeOnClick(event, treeId, treeNode){
    var treeObj = $.fn.zTree.getZTreeObj("all_knowledge");
    treeObj.expandNode(treeNode, true, true, true);
}

function zExitOnClick(event, treeId, treeNode){
    var treeObj = $.fn.zTree.getZTreeObj("exit_knowledge");
    treeObj.expandNode(treeNode, true, true, true);
}

$(function(){
    //console.log(zExitKnow);

    $.fn.zTree.init($("#exit_knowledge"), setting, zExitKnow);
    $.fn.zTree.init($("#all_knowledge"), setting2, zAllKnow);

    $("#show_all_knowledge").click(function(){
        var treeObj = $.fn.zTree.getZTreeObj('all_knowledge');
        treeObj.expandAll(true); 
    });

    $("#hide_all_knowledge").click(function(){
        var treeObj = $.fn.zTree.getZTreeObj('all_knowledge');
        treeObj.expandAll(false); 
    });

    $("#show_exit_knowledge").click(function(){
        var treeObj = $.fn.zTree.getZTreeObj('exit_knowledge');
        treeObj.expandAll(true); 
    });

    $("#hide_exit_knowledge").click(function(){
        var treeObj = $.fn.zTree.getZTreeObj('exit_knowledge');
        treeObj.expandAll(false); 
    });

    Enum_map.append_option_list("subject", $("#id_subject"),true,[1,2,3,4,5,6,7,8,9,10,11]);
    Enum_map.append_option_list("grade", $("#id_grade"),true,[101,102,103,104,105,106,201,202,203,301,302,303]);

    $("#id_subject").val(g_args.id_subject);
    $("#id_grade").val(g_args.id_grade);
    $("#id_textbook").val($('#defaule_textbook_id').val());
    $('.opt-change').set_input_change_event(load_data);
    
    function load_data(){
        var data = {
            id_subject : $("#id_subject").val(),
            id_grade : $("#id_grade").val(),
            id_textbook : $("#id_textbook").val(),
        };
        //console.log(data);
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

    //进入知识点列表页面
    $('#get_all_knowledge').on('click',function(){
        var subject = $('#id_subject').val();
        window.open('/question_new/knowledge_list?subject='+subject);
    });

    //添加教材
    $('#add_textbook').on('click',function(){
        var subject = $('#id_subject').val();
        window.open('/question_new/textbook_list?subject='+subject);
    });
})
