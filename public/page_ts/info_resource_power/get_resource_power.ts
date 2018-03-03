/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/info_resource_power-get_resource_power.d.ts" />


$(function(){
    //添加资源分类
    $('#opt_add_resource').on('click',function(){

    })

    //右键自定义
    var options = {items:[
        {text: '编辑资源名字', onclick: function(event) {
            edit_resource_name(event);
        }},
        {text: '添加细分类型',onclick: function() {
            add_type();
        }},
        {text: '删除资源类型',onclick: function(event) {
            dele_resource(event);
        }},
    ],before:function(){
 
    },onshow:function(){}};

    $('.right-menu td:eq(0)').contextify(options);

});
    
function edit_resource_name(event){
    
}

function add_type(){
    
}

function dele_resource(event){
    
}
