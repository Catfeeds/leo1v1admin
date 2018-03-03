/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/info_resource_power-get_resource_power.d.ts" />


$(function(){
    //右键自定义
    var options2 = {items:[
        {text: '编辑细分类型名字', onclick: function() {
            var data_obj = menu_hide();
            edit_resource_name(data_obj);
        }},
    ],before:function(){
        
    },onshow:function(){}};

    var color_id,color_res = 0,color_flag = 0;
    $('.right-menu').each(function(){
        $(this).find("td:eq(1)").contextify(options);
        //$(this).find("td:eq(2)").contextify(options2);
        if($(this).data('resource_id') == color_res){
            $(this).css('background',color_id );
        } else {
            color_res = $(this).data('resource_id');
            (color_flag == 0) ? color_flag = 1: color_flag = 0;
            (color_flag == 0) ? color_id = 'rgba(163, 223, 229, 0.25)' : color_id = 'rgba(191, 191, 191, 0.24)';
            $(this).css('background',color_id);
        }

    });

    $('body').click(function(){
        menu_hide();
        // $('.right-menu').each(function(){
        //     $(this).find("td:eq(0) input").attr("readonly","readonly");
        //     $(this).find("td:eq(1) input").attr("readonly","readonly");
        // });

    });

    //添加资源分类
    $('.opt_add_resource').on('click',function(){
        var $tr_obj = $(".power_table tbody .tr_case").clone().removeClass("hide");
        var tr_no = $(".power_table tbody tr").length;
        $tr_obj.find("td:eq(1)").removeAttr("data-contextify-id");
        $tr_obj.find("td:eq(1) input").removeAttr("readonly");
        //$tr_obj.find("td:eq(1)").contextify(options);
        $(".power_table tbody").append($tr_obj);
    })

});

var options = {items:[
    {text: '编辑资源分类名字', onclick: function() {
        var data_obj = menu_hide();
        edit_resource_name(data_obj);
    }},
    {text: '添加本资源分类下的细分类型',onclick: function() {
        var data_obj = menu_hide();
        add_type(data_obj);
    }},
    {text: '删除资源类型',onclick: function (){
        var data_obj = menu_hide();
        dele_resource(data_obj);
    }},
],before:function(){
    
},onshow:function(){}};

var menu_hide = function(){
    $('#contextify-menu').hide();
    return $('#contextify-menu');
};
    
function edit_resource_name(data_obj){
    var title = "一旦修改资源分类名字，相同的资源分类id的名字会统一变更，你确定修改吗？";
    var contextify_id = data_obj[0].dataset.contextifyid;
    var $obj = $("td[data-contextify-id='"+contextify_id+"']");

    var dlg= BootstrapDialog.show({
        title: "提示",
        message : title,
        buttons: [{
            label: '取消',
            cssClass: 'btn-warning btn-upload-look-error',
            action: function(dialog) {
                dialog.close();
            }
        },{
            label: '确定',
            cssClass: 'btn-primary btn-upload-look-error',
            action: function(dialog) {
                dialog.close();
                $obj.find("input").removeAttr("readonly");
                $obj.find("input").focus();

            }
        }],
    })
}

function add_type(data_obj){
    var could_add = 1;
    $('.right-menu:gt(0)').each(function(){
        if($(this).attr("power_id") == 0){
            could_add = 0;
        };
    });

    if(could_add == 0){
        BootstrapDialog.alert("有未编辑完的，请先编辑保存后，再添加新的");
        return false;
    }
    var contextify_id = data_obj[0].dataset.contextifyid;
    var $obj = $("td[data-contextify-id='"+contextify_id+"']").parents("tr");
    
    var $tr_obj = $(".power_table tbody .tr_case").clone().removeClass("hide");
    var tr_no = $(".power_table tbody tr").length;
    var resource_name = $obj.find("td:eq(1) input").val();
    var resource_id = $obj.find("td:eq(1) input").attr("resource_id");
    $tr_obj.find("td:eq(0)").text($obj.find("td:eq(0)").text());
    $tr_obj.find("td:eq(1) input").val(resource_name);
    $tr_obj.find("td:eq(1) input").attr({"resource_id":resource_id});
    $tr_obj.find("td:eq(1)").removeAttr("data-contextify-id");
    $tr_obj.find("td:eq(1)").contextify(options);
    $obj.after($tr_obj);

}

function dele_resource(data_obj){
    var contextify_id = data_obj[0].dataset.contextifyid;
    var $obj = $("td[data-contextify-id='"+contextify_id+"']").parents("tr");
    var resource_id = $obj.find("td:eq(1) input").attr("resource_id");
    var title = "删除后，本资源下所有细分类型，以及绑定在该细分类型下的文件一起删除，你确定？";
    BootstrapDialog.confirm(title,function(ret){
        if(ret){
            $.ajax({
                type    : "post",
                url     : "/info_resource_power/dele_resource",
                dataType: "json",
                data    : {
                    "resource_id"  : resource_id,
                },
                success : function(result){
                    if(result.ret == 0){
                        window.location.reload();
                    }
                }
            });
        }
    });
}

function save_type(oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement; 
    var obj = $(target).parents("tr");
    var id = obj.attr("power_id");
    var resource_id = obj.find(".resource_name").attr("resource_id");
    var resource_name = obj.find(".resource_name").val();
    var type_id = obj.find(".type_name").attr("type_id");
    var type_name = obj.find(".type_name").val();
    var consult_power =  obj.find(".consult_power input:checked").val();
    var assistant_power =  obj.find(".assistant_power input:checked").val();
    var market_power =  obj.find(".market_power input:checked").val();
    
    var data = {
        "id"  : id,
        "resource_id" : resource_id,
        "resource_name" : resource_name,
        "type_id" : type_id,
        "type_name"   : type_name,
        "consult_power" : consult_power,
        "assistant_power" : assistant_power,
        "market_power"  : market_power,
    };
    console.log(data);
    if( !consult_power ){
        BootstrapDialog.alert("请填写咨询部权限");
        return false;
    }

    if( !assistant_power ){
        BootstrapDialog.alert("请填写助教部权限");
        return false;
    }

    if( !market_power ){
        BootstrapDialog.alert("请填写市场部权限");
        return false;
    }

    if( resource_name == "" || type_name == ""){
        BootstrapDialog.alert("请填写资源分类名字或者细分类型名字");
        return false;
    }

    $.ajax({
        type     : "post",
        url      : "/info_resource_power/save_resource_power",
        dataType : "json",
        data : data ,
        success  : function(result){
            if(result.ret == 0){
                if(result.status == 200){
                    window.location.reload();
                }else{
                    BootstrapDialog.alert("编辑失败");
                }
               
            } else {
                alert("网络错误！");
            }
        }
    });

}

function dele_type(oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement; 
    var obj = $(target).parents("tr");
    var id = obj.attr("power_id");
    var title = "删除后，绑定在该标签下的文件一起删除，你确定？";
    console.log(id);
    BootstrapDialog.confirm(title,function(ret){
        if(ret){
            $.ajax({
                type    : "post",
                url     : "/info_resource_power/dele_type",
                dataType: "json",
                data    : {
                    "id"  : id,
                },
                success : function(result){
                    if(result.ret == 0){
                        window.location.reload();
                    }
                }
            });
        }
    });

}
