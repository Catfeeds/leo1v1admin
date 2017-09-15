/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-teacher_train_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
        	date_type     : $('#id_date_type').val(),
            opt_date_type : $('#id_opt_date_type').val(),
            start_time    : $('#id_start_time').val(),
            end_time      : $('#id_end_time').val(),
            train_type    : $('#id_train_type').val(),
            subject       : $('#id_subject').val(),
            status        : $('#id_status').val(),
        });
    }


    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        // date_type_config : JSON.parse( g_args.date_type_config), 
        onQuery          : function() {
            load_data();
        }
    });

    Enum_map.append_option_list("train_type",$("#id_train_type"),false,[0,20,21,22,23]);
    Enum_map.append_option_list("subject",$("#id_subject"));
    Enum_map.append_option_list("train_status",$("#id_status"));

    $("#id_train_type").val(g_args.train_type);
    $("#id_subject").val(g_args.subject);
    $("#id_status").val(g_args.status);

    $("#id_add_train_info").on("click", function(){
        var opt_data = $(this).get_opt_data();
        var teacherid         = $("<input />");  //teacherid
        var subject           = $("<select />");  //科目
        var train_type        = $("<select />");  //培训类型

        Enum_map.append_option_list("subject",subject,true);
        Enum_map.append_option_list("train_type",train_type,true,[0,20,21,22,23]);

        var arr = [
            ["姓名", teacherid],
            ["科目", subject],
            ["培训类型", train_type],
        ];
        $.show_key_value_table("添加老师培训信息", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                if(teacherid.val() === ''){
                    alert("请选择培训老师");
                    return;
                }
                if(subject.val() === ''){
                    alert("请选择培训科目");
                    return;
                }
                if(train_type.val() <= 0){
                    alert("请选择培训类型");
                    return;
                }
                $.do_ajax("/ajax_deal2/add_train_info",{
                    "teacherid"          : teacherid.val(),
                    'subject'            : subject.val(),
                    'train_type'         : train_type.val(),
                });
            }
        },function(){
            $.admin_select_user(teacherid, "teacher" );
        });
    });


    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();

        BootstrapDialog.confirm("要删除用户是["+opt_data.teacher_nick+"]的培训信息吗?",function(val){
            if(val){
                $.do_ajax("/ajax_deal2/del_train_info",{
                    "id" : opt_data.id
                });
            }
        });
    });


    $(".opt-edit").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var teacherid         = $("<input />");  //teacherid
        var subject           = $("<select />");  //科目
        var train_type        = $("<select />");  //培训类型
        var status            = $("<select />");  //培训状态

        Enum_map.append_option_list("subject",subject,true);
        Enum_map.append_option_list("train_type",train_type,true,[0,20,21,22,23]);
        Enum_map.append_option_list("train_status",status,true);
        teacherid.val(opt_data.teacher_nick);
        subject.val(opt_data.subject);
        train_type.val(opt_data.train_type);
        status.val(opt_data.status);
        var arr = [
            ["姓名", teacherid],
            ["科目", subject],
            ["培训类型", train_type],
            ["培训状态",status],
        ];

      
        $.show_key_value_table("修改老师培训信息", arr, {
            label    :  "确认",
            cssClass :  'btn-waring',
            action   :   function(dialog){
                if(teacherid.val() === ''){
                    alert("请选择培训老师");
                    return;
                }
                if(subject.val() === ''){
                    alert("请选择培训科目");
                    return;
                }
                if(train_type.val() <= 0){
                    alert("请选择培训类型");
                    return;
                }

                $.do_ajax("/ajax_deal2/edit_train_info",{
                    "id"             : opt_data.id,
                    'subject'        : subject.val(),
                    'train_type'     : train_type.val(),
                    'status'         : status.val(),
                });
            }
        },function(){
        });
    }) ;

	$('.opt-change').set_input_change_event(load_data);
});
