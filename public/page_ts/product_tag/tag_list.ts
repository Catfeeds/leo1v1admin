/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/product_tag-tag_list.d.ts" />

$(function(){
    //实例化一个plupload上传对象
    var uploader = $.plupload_Uploader({
        browse_button : 'id_xls', //触发文件选择对话框的按钮，为那个元素id
        url : '/product_tag/add_tag_by_xls', //服务器端的上传页面地址
        flash_swf_url : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : '/js/qiniu/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        filters: {
            mime_types : [ //只允许上传图片和zip文件
                { title : "xls files", extensions : "xls" },
                { title : "xlsx files", extensions : "xlsx" }
            ],
            max_file_size : '40m', //最大只能上传400kb的文件
            prevent_duplicates : true //不允许选取重复文件
        }
    });
    uploader.init();
    uploader.bind('FilesAdded',function(up, files) {
        uploader.start();
    });
    uploader.bind('FileUploaded',function(response,responseHeaders,status){
        if(status.status == 200)
            alert('添加成功!');
        location.reload();
    })

    function load_data(){
        $.reload_self_page ( {
            tag_l1_sort : $('#id_tag_l1_sort').find("option:selected").text(),
            tag_l2_sort : $('#id_tag_l2_sort').find("option:selected").text(),
            tag_l3_sort : $('#id_tag_l3_sort').find("option:selected").text(),
            tag_name:	$('#id_tag_name').val()
        });
    }

    $('#id_tag_name').val(g_args.tag_name);
    //--三级联动下拉框--begin--
    var tag_l1_sort = $('#id_tag_l1_sort');
    var tag_l2_sort = $('#id_tag_l2_sort');
    var tag_l3_sort = $('#id_tag_l3_sort');
    var old_tag_l1_sort = g_args.tag_l1_sort;
    if(old_tag_l1_sort == ''){
        old_tag_l1_sort="标签一级分类";
    }

    var old_tag_l2_sort = g_args.tag_l2_sort;
    if(old_tag_l2_sort == ''){
        old_tag_l2_sort="标签二级分类";
    }
    var old_tag_l3_sort = g_args.tag_l3_sort;
    if(old_tag_l3_sort == ''){
        old_tag_l3_sort="标签三级分类";
    }

    var preTag_l1_sort = "<option value=\"\">"+old_tag_l1_sort+"</option>";  
    var preTag_l2_sort = "<option value=\"\">"+old_tag_l2_sort+"</option>";  
    var preTag_l3_sort = "<option value=\"\">"+old_tag_l3_sort+"</option>";  
    //初始化  
    tag_l1_sort.html(preTag_l1_sort);  
    tag_l2_sort.html(preTag_l2_sort);  
    tag_l3_sort.html(preTag_l3_sort);
    
    //文档加载完毕:即从tag_l1_sort_tag_l2_sort_select_Info.xml获取数据,成功之后采用  
    //func_suc_getXmlProvice进行 省的 解析  
    $.ajax({  
        type : "GET",  
        url : "/tag_library.xml",  
        success : func_suc_getXmlProvice  
    });  
    
    //省 下拉选择发生变化触发的事件  
    tag_l1_sort.change(function() {  
        //tag_l1_sort.val()  : 返回是每个省对应的下标,序号从0开始  
        if (tag_l1_sort.val() != "") {  
            if(g_args.tag_l1_sort != tag_l1_sort.find("option:selected").text()){
                var preTag_l2_sort = "<option value=\"\">标签二级分类</option>";  
                var preTag_l3_sort = "<option value=\"\">标签三级分类</option>";   
            }
            tag_l2_sort.html(preTag_l2_sort);  
            tag_l3_sort.html(preTag_l3_sort);  
            
            //根据下拉得到的省对于的下标序号,动态从从tag_l1_sort_tag_l2_sort_select_Info.xml获取数据,成功之后采用  
            //func_suc_getXmlProvice进行省对应的市的解析  
            $.ajax({  
                type : "GET",  
                url : "/tag_library.xml",  
                success : func_suc_getXmlTag_l2_sort  
            });  
            
        }  
    });  
    
    //市 下拉选择发生变化触发的事件  
    tag_l2_sort.change(function() {  
        if(g_args.tag_l2_sort != tag_l2_sort.find("option:selected").text()){  
            var preTag_l3_sort = "<option value=\"\">标签三级分类</option>";   
        } 

        tag_l3_sort.html(preTag_l3_sort);  
        $.ajax({  
            type : "GET",  
            url : "/tag_library.xml",  
            
            //根据下拉得到的省、市对于的下标序号,动态从从tag_l1_sort_tag_l2_sort_select_Info.xml获取数据,成功之后采用  
            //func_suc_getXmlTag_l3_sort进行省对应的市对于的区的解析  
            success : func_suc_getXmlTag_l3_sort  
        });  
    });  
    
    //解析获取xml格式文件中的prov标签,得到所有的省,并逐个进行遍历 放进下拉框中  
    function func_suc_getXmlProvice(xml) {  
        //jquery的查找功能  
        var l1 = $(xml).find("tag_l1_sort"); 
        
        //jquery的遍历与查询匹配 eq功能,并将其放到下拉框中  
        l1.each(function(i) {  
            tag_l1_sort.append("<option value=" + i + ">"  
                               + l1.eq(i).attr("text") + "</option>");  
        });
    }

    function func_suc_getXmlTag_l2_sort(xml) {
        var xml_l1 = $(xml).find("tag_l1_sort");
        var pro_num = parseInt(tag_l1_sort.val());
        var xml_l2 = xml_l1.eq(pro_num).find("tag_l2_sort");
        xml_l2.each(function(j) {
            tag_l2_sort.append("<option  value=" + j + ">"
                               + xml_l2.eq(j).attr("text") + "</option>");
        });
    }

    function func_suc_getXmlTag_l3_sort(xml) {  
        var xml_l1 = $(xml).find("tag_l1_sort");  
        var pro_num = parseInt(tag_l1_sort.val());  
        var xml_l2 = xml_l1.eq(pro_num).find("tag_l2_sort");  
        var tag_l2_sort_num = parseInt(tag_l2_sort.val());  
        var xml_l3 = xml_l2.eq(tag_l2_sort_num).find("tag_l3_sort");  
        xml_l3.each(function(k) {  
            tag_l3_sort.append("<option  value=" + k + ">"  
                               + xml_l3.eq(k).attr("text") + "</option>");  
        });  
    }
    //--三级联动下拉框--end--

    $("#id_search").on("click",load_data);
    // $('.opt-change').set_input_change_event(load_data);

    $("#id_add").on("click",function(){
        var tag_l1_sort= $("<select />" );
        var tag_l2_sort= $("<select />" );
        var tag_l3_sort= $("<select />" );
        var tag_name = $("<input />")
        var tag_desc = $("<textarea/>")
        var tag_object= $("<select />" );
        Enum_map.append_option_list("tag_object", tag_object);
        var tag_weight = $("<input type='number'/><span class='note'>数值越大，优先展示</span>")

        //--三级联动下拉框--begin--
        var opt_data = '';
        var old_tag_l1_sort = opt_data.tag_l1_sort;
        if(old_tag_l1_sort == null){
            old_tag_l1_sort="标签一级分类";
        }

        var old_tag_l2_sort = opt_data.tag_l2_sort;
        if(old_tag_l2_sort == null){
            old_tag_l2_sort="标签二级分类";
        }
        var old_tag_l3_sort = opt_data.tag_l3_sort;
        if(old_tag_l3_sort == null){
            old_tag_l3_sort="标签三级分类";
        }
       
        

        var preTag_l1_sort = "<option value=\"\">"+old_tag_l1_sort+"</option>";  
        var preTag_l2_sort = "<option value=\"\">"+old_tag_l2_sort+"</option>";  
        var preTag_l3_sort = "<option value=\"\">"+old_tag_l3_sort+"</option>";  
        
        //初始化  
        tag_l1_sort.html(preTag_l1_sort);  
        tag_l2_sort.html(preTag_l2_sort);  
        tag_l3_sort.html(preTag_l3_sort);
        
        //文档加载完毕:即从tag_l1_sort_tag_l2_sort_select_Info.xml获取数据,成功之后采用  
        //func_suc_getXmlProvice进行 省的 解析  
        $.ajax({  
            type : "GET",  
            url : "/tag_library.xml",  
            success : func_suc_getXmlProvice  
        });  
        
        //省 下拉选择发生变化触发的事件  
        tag_l1_sort.change(function() {  
            //tag_l1_sort.val()  : 返回是每个省对应的下标,序号从0开始  
            if (tag_l1_sort.val() != "") {  
                if(opt_data.tag_l1_sort != tag_l1_sort.find("option:selected").text()){
                    var preTag_l2_sort = "<option value=\"\">标签二级分类</option>";  
                    var preTag_l3_sort = "<option value=\"\">标签三级分类</option>";   
                }
                tag_l2_sort.html(preTag_l2_sort);  
                tag_l3_sort.html(preTag_l3_sort);  
                
                //根据下拉得到的省对于的下标序号,动态从从tag_l1_sort_tag_l2_sort_select_Info.xml获取数据,成功之后采用  
                //func_suc_getXmlProvice进行省对应的市的解析  
                $.ajax({  
                    type : "GET",  
                    url : "/tag_library.xml",  
                    success : func_suc_getXmlTag_l2_sort  
                });  
                
            }  
        });  
        
        //市 下拉选择发生变化触发的事件  
        tag_l2_sort.change(function() {  
            if(opt_data.tag_l2_sort != tag_l2_sort.find("option:selected").text()){  
                var preTag_l3_sort = "<option value=\"\">标签三级分类</option>";   
            } 

            tag_l3_sort.html(preTag_l3_sort);  
            $.ajax({  
                type : "GET",  
                url : "/tag_library.xml",  
                
                //根据下拉得到的省、市对于的下标序号,动态从从tag_l1_sort_tag_l2_sort_select_Info.xml获取数据,成功之后采用  
                //func_suc_getXmlTag_l3_sort进行省对应的市对于的区的解析  
                success : func_suc_getXmlTag_l3_sort  
            });  
        });  
        
        //解析获取xml格式文件中的prov标签,得到所有的省,并逐个进行遍历 放进下拉框中  
        function func_suc_getXmlProvice(xml) {  
            //jquery的查找功能  
            var l1 = $(xml).find("tag_l1_sort"); 
            
            //jquery的遍历与查询匹配 eq功能,并将其放到下拉框中  
            l1.each(function(i) {  
                tag_l1_sort.append("<option value=" + i + ">"  
                                + l1.eq(

i).attr("text") + "</option>");  
            });  
        }  
        
        function func_suc_getXmlTag_l2_sort(xml) {  
            var xml_l1 = $(xml).find("tag_l1_sort");  
            var pro_num = parseInt(tag_l1_sort.val());  
            var xml_l2 = xml_l1.eq(pro_num).find("tag_l2_sort");  
            xml_l2.each(function(j) {  
                tag_l2_sort.append("<option  value=" + j + ">"  
                            + xml_l2.eq(j).attr("text") + "</option>");  
            });  
        }  
        
        function func_suc_getXmlTag_l3_sort(xml) {  
            var xml_l1 = $(xml).find("tag_l1_sort");  
            var pro_num = parseInt(tag_l1_sort.val());  
            var xml_l2 = xml_l1.eq(pro_num).find("tag_l2_sort");  
            var tag_l2_sort_num = parseInt(tag_l2_sort.val());  
            var xml_l3 = xml_l2.eq(tag_l2_sort_num).find("tag_l3_sort");  
            xml_l3.each(function(k) {  
                tag_l3_sort.append("<option  value=" + k + ">"  
                            + xml_l3.eq(k).attr("text") + "</option>");  
            });  
        }
        //--三级联动下拉框--end--

        var arr=[
            ["<span style='color:red;'>*</span>标签一级分类" ,tag_l1_sort  ],
            ["<span style='color:red;'>*</span>标签二级分类" ,tag_l2_sort  ],
            ["标签三级分类" ,tag_l3_sort  ],
            ["<span style='color:red;'>*</span>标签名称" ,tag_name ],
            ["<span style='color:red;'>*</span>标签定义" ,tag_desc  ],
            ["<span style='color:red;'>*</span>设定对象" ,tag_object  ],
            ["<span style='color:red;'>*</span>权重系数" ,tag_weight  ],
        ] ;

        
        $.show_key_value_table("添加标签", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                if(tag_l1_sort.val() == ''){
                    alert('一级标签为必填项!');
                    return false;
                }
                if(tag_l2_sort.val() == ''){
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
                if(tag_weight.val() < 0 || tag_weight.val() > 99){
                    alert('权重系数范围为0~99!');
                    return false;
                }
                $.do_ajax("/product_tag/tag_add",{
                    "tag_l1_sort" : tag_l1_sort.find("option:selected").text(),
                    "tag_l2_sort" : tag_l2_sort.find("option:selected").text(),
                    "tag_l3_sort" : tag_l3_sort.find("option:selected").text(),
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
        var tag_l2_sort= $("<select/>" );
        var tag_l3_sort= $("<select/>" );
        var tag_name = $('<input/>')
        var tag_desc = $('<textarea/>')
        var tag_object= $("<select/>" );
        Enum_map.append_option_list("tag_object", tag_object);
        var tag_weight = $("<input type='number'/><span class='note'>数值越大，优先展示</span>")
        //--三级联动下拉框--begin--
        var old_tag_l1_sort = opt_data.tag_l1_sort;
        if(old_tag_l1_sort == null){
            old_tag_l1_sort="标签一级分类";
        }

        var old_tag_l2_sort = opt_data.tag_l2_sort;
        if(old_tag_l2_sort == null){
            old_tag_l2_sort="标签二级分类";
        }
        var old_tag_l3_sort = opt_data.tag_l3_sort;
        if(old_tag_l3_sort == null){
            old_tag_l3_sort="标签三级分类";
        }
       
        

        var preTag_l1_sort = "<option value=\"\">"+old_tag_l1_sort+"</option>";  
        var preTag_l2_sort = "<option value=\"\">"+old_tag_l2_sort+"</option>";  
        var preTag_l3_sort = "<option value=\"\">"+old_tag_l3_sort+"</option>";  
        
        //初始化  
        tag_l1_sort.html(preTag_l1_sort);  
        tag_l2_sort.html(preTag_l2_sort);  
        tag_l3_sort.html(preTag_l3_sort);
        
        //文档加载完毕:即从tag_l1_sort_tag_l2_sort_select_Info.xml获取数据,成功之后采用  
        //func_suc_getXmlProvice进行 省的 解析  
        $.ajax({  
            type : "GET",  
            url : "/tag_library.xml",  
            success : func_suc_getXmlProvice  
        });  
        
        //省 下拉选择发生变化触发的事件  
        tag_l1_sort.change(function() {  
            //tag_l1_sort.val()  : 返回是每个省对应的下标,序号从0开始  
            if (tag_l1_sort.val() != "") {  
                if(opt_data.tag_l1_sort != tag_l1_sort.find("option:selected").text()){
                    var preTag_l2_sort = "<option value=\"\">标签二级分类</option>";  
                    var preTag_l3_sort = "<option value=\"\">标签三级分类</option>";   
                }
                tag_l2_sort.html(preTag_l2_sort);  
                tag_l3_sort.html(preTag_l3_sort);  
                
                //根据下拉得到的省对于的下标序号,动态从从tag_l1_sort_tag_l2_sort_select_Info.xml获取数据,成功之后采用  
                //func_suc_getXmlProvice进行省对应的市的解析  
                $.ajax({  
                    type : "GET",  
                    url : "/tag_library.xml",  
                    success : func_suc_getXmlTag_l2_sort  
                });  
                
            }  
        });  
        
        //市 下拉选择发生变化触发的事件  
        tag_l2_sort.change(function() {  
            if(opt_data.tag_l2_sort != tag_l2_sort.find("option:selected").text()){  
                var preTag_l3_sort = "<option value=\"\">标签三级分类</option>";   
            } 

            tag_l3_sort.html(preTag_l3_sort);  
            $.ajax({  
                type : "GET",  
                url : "/tag_library.xml",  
                
                //根据下拉得到的省、市对于的下标序号,动态从从tag_l1_sort_tag_l2_sort_select_Info.xml获取数据,成功之后采用  
                //func_suc_getXmlTag_l3_sort进行省对应的市对于的区的解析  
                success : func_suc_getXmlTag_l3_sort  
            });  
        });  
        
//解析获取xml格式文件中的prov标签,得到所有的省,并逐个进行遍历 放进下拉框中  
        function func_suc_getXmlProvice(xml) {  
            //jquery的查找功能  
            var l1 = $(xml).find("tag_l1_sort"); 
            
            //jquery的遍历与查询匹配 eq功能,并将其放到下拉框中  
            l1.each(function(i) {  
                tag_l1_sort.append("<option value=" + i + ">"  
                                + l1.eq(i).attr("text") + "</option>");  
            });  
        }  
        
        function func_suc_getXmlTag_l2_sort(xml) {  
            var xml_l1 = $(xml).find("tag_l1_sort");  
            var pro_num = parseInt(tag_l1_sort.val());  
            var xml_l2 = xml_l1.eq(pro_num).find("tag_l2_sort");  
            xml_l2.each(function(j) {  
                tag_l2_sort.append("<option  value=" + j + ">"  
                            + xml_l2.eq(j).attr("text") + "</option>");  
            });  
        }  
        
        function func_suc_getXmlTag_l3_sort(xml) {  
            var xml_l1 = $(xml).find("tag_l1_sort");  
            var pro_num = parseInt(tag_l1_sort.val());  
            var xml_l2 = xml_l1.eq(pro_num).find("tag_l2_sort");  
            var tag_l2_sort_num = parseInt(tag_l2_sort.val());  
            var xml_l3 = xml_l2.eq(tag_l2_sort_num).find("tag_l3_sort");  
            xml_l3.each(function(k) {  
                tag_l3_sort.append("<option  value=" + k + ">"  
                            + xml_l3.eq(k).attr("text") + "</option>");  
            });  
        }
        //--三级联动下拉框--end--


        var arr=[
            ["<span style='color:red;'>*</span>标签一级分类" ,tag_l1_sort  ],
            ["<span style='color:red;'>*</span>标签二级分类" ,tag_l2_sort  ],
            ["标签三级分类" ,tag_l3_sort  ],
            ["<span style='color:red;'>*</span>标签名称" ,tag_name ],
            ["<span style='color:red;'>*</span>标签定义" ,tag_desc  ],
            ["<span style='color:red;'>*</span>设定对象" ,tag_object  ],
            ["<span style='color:red;'>*</span>权重系数" ,tag_weight],
        ] ;
        tag_name.val(opt_data.tag_name);
        tag_desc.val(opt_data.tag_desc);
        tag_object.val(opt_data.tag_object);
        tag_weight.val(opt_data.tag_weight);


        $.show_key_value_table("修改标签库", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                if(tag_l1_sort.find("option:selected").text() == ''){
                    alert('一级标签为必填项!');
                    return false;
                }
                if(tag_l2_sort.find("option:selected").text() == ''){
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
                if(tag_weight.val() < 0 || tag_weight.val() > 99){
                    alert('权重系数范围为0~99!');
                    return false;
                }

                $.do_ajax("/product_tag/tag_update",{
                    "id" : opt_data.tag_id,
                    "tag_l1_sort" : tag_l1_sort.find("option:selected").text(),
                    "tag_l2_sort" : tag_l2_sort.find("option:selected").text(),
                    "tag_l3_sort" : tag_l3_sort.find("option:selected").text(),
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
