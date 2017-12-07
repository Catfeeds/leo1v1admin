/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/question-knowledge_list.d.ts" />

$(function(){
    Enum_map.append_option_list("subject", $("#id_subject"));

    //进入知识点列表页面
    $('#question_list').on('click',function(){
        var opt_data=$(this).get_opt_data();
        window.open('/question/question_list');
    });

    //添加知识点
    $('#id_add_knowledge').on('click',function(){
        var id_title = $("<input/>");
        var id_subject = $("<select/>");
        var id_detail = $("<textarea></textarea>");

        Enum_map.append_option_list("subject",id_subject,true,[1,2,3,4,5,6,7,8,9,10,11]);

        var arr=[
            ["知识点标题", id_title ],
            ["知识点科目", id_subject ],
            ["知识点详情解读", id_detail ],
        ];

        $.show_key_value_table("添加知识点", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var title = id_title.val();
                var subject = id_subject.val();
                if( title == '' || subject ==''){
                    BootstrapDialog.alert('标题或者科目必填');
                    return false;
                }
 
                var data = {
                    'title': title,
                    'subject':subject,
                    'detail':id_detail.val(),
                }
              
                $.ajax({
                    type     :"post",
                    url      :"/question/knowledge_add",
                    dataType :"json",
                    data     :data,
                    success : function(result){
                        BootstrapDialog.alert(result['msg']);
                        if(result['status'] == 200){
                            window.location.reload();
                        }
                    }
                });
            }
        })

    });

    //进入编辑页面
    $('.opt-set').on('click',function(){
        var opt_data=$(this).get_opt_data();
        var id_title = $("<input/>");
        var id_subject = $("<select/>");
        var id_detail = $("<textarea></textarea>");

        Enum_map.append_option_list("subject",id_subject,true,[1,2,3,4,5,6,7,8,9,10,11]);

        id_title.val(opt_data.title);
        id_subject.val(opt_data.subject);
        id_detail.val(opt_data.detail);
        var arr=[
            ["知识点标题", id_title ],
            ["知识点科目", id_subject ],
            ["知识点详情解读", id_detail ],
        ];

        $.show_key_value_table("编辑知识点", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var title = id_title.val();
                var subject = id_subject.val();
                if( title == '' || subject ==''){
                    BootstrapDialog.alert('标题或者科目必填');
                    return false;
                }
 
                var data = {
                    'knowledge_id':opt_data.knowledge_id,
                    'title': title,
                    'subject':subject,
                    'detail':id_detail.val(),
                }
          
                $.ajax({
                    type     :"post",
                    url      :"/question/knowledge_edit",
                    dataType :"json",
                    data     :data,
                    success : function(result){
                        BootstrapDialog.alert(result['msg']);
                        if(result['status'] == 200){
                            window.location.reload();
                        }
                    }
                });
            }
        })

    });

    //删除活动
    $('.opt-del').on('click',function(){
        var opt_data = $(this).get_opt_data();

        var knowledge_id = opt_data.knowledge_id;
        var title = "你确定删除本知识点,标题为" + opt_data.title + "？";
        var data = {
            'knowledge_id':knowledge_id
        };

        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/question/knowledge_dele",data);
            }
        });

    })

})
