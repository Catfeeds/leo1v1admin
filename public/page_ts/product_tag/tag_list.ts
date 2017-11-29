/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/product_tag-tag_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            tag_l1_sort:	$('#id_tag_l1_sort').val(),
            tag_l2_sort:	$('#id_tag_l2_sort').val(),
            tag_name:	$('#id_tag_name').val()
        });
    }

    Enum_map.append_option_list("tag_l1_sort", $("#id_tag_l1_sort"));
    Enum_map.append_option_list("tag_l2_sort", $("#id_tag_l2_sort"));
    $('#id_tag_name').val(g_args.tag_name);
    $('#id_tag_l2_sort').val(g_args.tag_l2_sort > 0 ? g_args.tag_l2_sort:-1);
    $('#id_tag_l1_sort').val(g_args.tag_l1_sort  > 0 ? g_args.tag_l1_sort:-1);

    $('.opt-change').set_input_change_event(load_data);

    $("#id_add").on("click",function(){
        var tag_l1_sort= $("<select />" );
        Enum_map.append_option_list("tag_l1_sort", tag_l1_sort);
        var tag_l2_sort= $("<select />" );
        Enum_map.append_option_list("tag_l2_sort", tag_l2_sort);
        var tag_name = $("<input />")
        var tag_desc = $("<textarea class='opt-mandatory'/>")
        var tag_object= $("<select />" );
        Enum_map.append_option_list("tag_object", tag_object);
        var tag_weight = $("<input type='number'/><span class='note'>数值越大，优先展示</span>")

        var arr=[
            ["<span style='color:red;'>*</span>标签一级分类" ,tag_l1_sort  ],
            ["<span style='color:red;'>*</span>标签二级分类" ,tag_l2_sort  ],
            ["<span style='color:red;'>*</span>标签名称" ,tag_name ],
            ["<span style='color:red;'>*</span>标签定义" ,tag_desc  ],
            ["<span style='color:red;'>*</span>设定对象" ,tag_object  ],
            ["权重系数" ,tag_weight  ],
        ] ;

        
        $.show_key_value_table("添加标签", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                if(tag_l1_sort.val() <= 0){
                    alert('一级标签为必填项!');
                    return false;
                }
                if(tag_l2_sort.val() <= 0){
                    alert('二级标签为必填项!');
                    return false;
                }
                if(tag_name.val() == ''){
                    alert('标签名称为必填项!');
                    return false;
                }
                if(tag_desc.val() == ''){
                    alert('标签定义为必填项!');
                    return false;
                }
                if(tag_object.val() <= 0){
                    alert('设定对象为必填项!');
                    return false;
                }
                $.do_ajax("/product_tag/tag_add",{
                    "tag_l1_sort" : tag_l1_sort.val(),
                    "tag_l2_sort" : tag_l2_sort.val(),
                    "tag_name" : tag_name.val(),
                    "tag_desc" : tag_desc.val(),
                    "tag_object" : tag_object.val(),
                    "tag_weight" : tag_weight.val(),
                });
            }
        });

    });


    $(".opt-edit").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var tag_l1_sort= $("<select/>" );
        Enum_map.append_option_list("tag_l1_sort", tag_l1_sort);
        var tag_l2_sort= $("<select/>" );
        Enum_map.append_option_list("tag_l2_sort", tag_l2_sort);
        var tag_name = $('<input/>')
        var tag_desc = $('<textarea/>')
        var tag_object= $("<select/>" );
        Enum_map.append_option_list("tag_object", tag_object);
        var tag_weight = $("<input type='number'/><span class='note'>数值越大，优先展示</span>")

        var arr=[
            ["<span style='color:red;'>*</span>标签一级分类" ,tag_l1_sort  ],
            ["<span style='color:red;'>*</span>标签二级分类" ,tag_l2_sort  ],
            ["<span style='color:red;'>*</span>标签名称" ,tag_name ],
            ["<span style='color:red;'>*</span>标签定义" ,tag_desc  ],
            ["<span style='color:red;'>*</span>设定对象" ,tag_object  ],
            ["权重系数" ,tag_weight],
        ] ;
        tag_l1_sort.val(opt_data.tag_l1_sort);
        tag_l2_sort.val(opt_data.tag_l2_sort);
        tag_name.val(opt_data.tag_name);
        tag_desc.val(opt_data.tag_desc);
        tag_object.val(opt_data.tag_object);
        tag_weight.val(opt_data.tag_weight);


        $.show_key_value_table("修改标签库", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                if(tag_l1_sort.val() <= 0){
                    alert('一级标签为必填项!');
                    return false;
                }
                if(tag_l2_sort.val() <= 0){
                    alert('二级标签为必填项!');
                    return false;
                }
                if(tag_name.val() == ''){
                    alert('标签名称为必填项!');
                    return false;
                }
                if(tag_desc.val() == ''){
                    alert('标签定义为必填项!');
                    return false;
                }
                if(tag_object.val() <= 0){
                    alert('设定对象为必填项!');
                    return false;
                }

                $.do_ajax("/product_tag/tag_update",{
                    "id" : opt_data.tag_id,
                    "tag_l1_sort" : tag_l1_sort.val(),
                    "tag_l2_sort" : tag_l2_sort.val(),
                    "tag_name" : tag_name.val(),
                    "tag_desc" : tag_desc.val(),
                    "tag_object" : tag_object.val(),
                    "tag_weight" : tag_weight.val(),
                });
            }
        });


    });

    $(".opt-del").on("click",function(){
      var opt_data=$(this).get_opt_data();
        BootstrapDialog.confirm(
            "要删除名称为-" + opt_data.tag_name+"-的标签吗？"  ,
            function(val){
                if (val) {
                    $.do_ajax("/product_tag/tag_del",{
                        "tag_id" : opt_data.tag_id
                    });

                }
            });
    });
    

});
