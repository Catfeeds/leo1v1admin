/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_paper-input_paper.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}

    $.reload_self_page ({
        paper_id:	$('#id_paper').val(),
        subject:	$('#id_subject').val(),
        grade:	$('#id_grade').val(),
        volume:	$('#id_volume').val(),
        book:	$('#id_book').val(),
        status:	$('#id_status').val(),

        date_type_config:   $('#id_date_type_config').val(),
        date_type:  $('#id_date_type').val(),
        opt_date_type:  $('#id_opt_date_type').val(),
        start_time: $('#id_start_time').val(),
        end_time:   $('#id_end_time').val(),
    });
}
$(function(){
	  $('#id_date_range').select_date_range({
        'date_type'     : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    $('#id_use_type').val(g_args.paper_id);

    Enum_map.append_option_list("subject", $("#id_subject"),false,[1,2,3,4,5,6,7,8,9,10,11]);
    Enum_map.append_option_list("grade", $("#id_grade"),false, [101,102,103,104,105,106,201,202,203,301,302,303]);
    Enum_map.append_option_list("resource_volume", $("#id_volume"));

    $('#id_subject').val(g_args.subject);
    $('#id_grade').val(g_args.grade);
    $('#id_volume').val(g_args.volume);    

    Enum_map.append_option_list("subject", $(".paper_subject"),false,[1,2,3,4,5,6,7,8,9,10,11]);
    Enum_map.append_option_list("grade", $(".paper_grade"),false, [101,102,103,104,105,106,201,202,203,301,302,303]);
    Enum_map.append_option_list("resource_volume", $(".paper_volume"));

    $('.paper_subject').val(g_args.subject);
    $('.paper_grade').val(g_args.grade);
    $('.paper_volume').val(g_args.volume);    

    if( parseInt(g_args.subject) < 0 || parseInt(g_args.grade) < 0 ){
        $("#id_book").append('<option value="-1">先选择科目和年级</option>');
    }else{         
        var obj = $("#id_book");
        var bookid = g_args.book;
        var subject = g_args.subject;
        var grade = g_args.grade;
        get_book(obj,bookid,subject,grade);
        get_book($('.paper_book'),bookid,subject,grade);
    }
       
    
    //预览讲义
    $('.opt-look').click(function(){
        var id = $(this).data('file_id');
        console.log(id);
        var newTab=window.open('about:blank');
        do_ajax('/resource/tea_look_resource',{'tea_res_id':id,'tea_flag':0},function(ret){
            console.log(ret);
            if(ret.ret == 0){
                $('.look-pdf').show();
                $('.look-pdf-son').mousedown(function(e){
                    if(e.which == 3){
                        return false;
                    }
                });
                console.log(ret.url);
                newTab.location.href = ret.url;
            } else {
                BootstrapDialog.alert(ret.info);
            }
        });
    })

    var opt_look = function(data_obj){
        var id = data_obj.data('file_id');
        var newTab=window.open('about:blank');
        do_ajax('/resource/tea_look_resource',{'tea_res_id':id,'tea_flag':0},function(ret){
            if(ret.ret == 0){
                $('.look-pdf').show();
                $('.look-pdf-son').mousedown(function(e){
                    if(e.which == 3){
                        return false;
                    }
                });
                console.log(ret.url);
                newTab.location.href = ret.url;
            } else {
                BootstrapDialog.alert(ret.info);
            }
        });
    };

    var add_file = function (resource_id, file, res, use_type){
        $.ajax({
            type     : "post",
            url      : "/resource/add_file",
            dataType : "json",
            data : {
                'resource_id'   : resource_id,
                'file_title'    : file.name,
                'file_type'     : file.type,
                'file_size'     : file.size,
                'file_hash'     : res.hash,
                'file_link'     : res.key,
                'file_use_type' : use_type,
            } ,

            success   : function(result){
                if(result.ret == 0){
                    window.onbeforeunload=function(){};
                    //window.location.reload();
                } else {
                    alert(result.info);
                }
            }
        });
    };

    var add_multi_file = function (data){
        $.ajax({
            type     : "post",
            url      : "/resource/add_multi_file",
            dataType : "json",
            data : data,
            success   : function(result){
                if(result.ret == 0){
                    window.onbeforeunload=function(){};
                    //window.location.reload();
                } else {
                    alert(result.info);
                }
            }
        });
    };

    var do_del = function(){
        var res_id_list = [],file_id_list = [];
        $('.opt-select-item').each(function(){
            if( $(this).iCheckValue()){
                res_id_list.push( $(this).data('id') );
                file_id_list.push( $(this).data('file_id') );
            }
        });

        if(res_id_list.length == 0) {
            BootstrapDialog.alert('请先选择文件！');
        } else {

            var res_id_info  = JSON.stringify(res_id_list);
            var file_id_info = JSON.stringify(file_id_list);
            if( confirm('若删除，则会同时删除与之相关联的其他文件,确定要删除？') ){
                $.ajax({
                    type    : "post",
                    url     : "/resource/del_or_restore_resource",
                    dataType: "json",
                    data    : {
                        "type"        : 3,
                        "res_id_str"  : res_id_info,
                        "file_id_str" : file_id_info,
                    },
                    success : function(result){
                        if(result.ret == 0){
                            window.location.reload();
                        }
                    }
                });
            };
        }
    };

    $('.opt-change').set_input_change_event(load_data);
});

var get_book = function(obj,bookid,subject,grade){
    $.ajax({
        type     : "post",
        url      : "/resource/get_resource_type_js",
        dataType : "json",
        data : {
            'resource_type' : 6,
            'subject'       : subject,
            'grade'         : grade,
        } ,
        success   : function(result){
            if(result.ret == 0){
                obj.empty();
                var agree_book = result.book;
                if(agree_book.length == 0) {
                    obj.html('<option value="-1">该科目、年级下暂无开放的教材版本!</option>');
                } else {
                    //console.log(bookid);
                    Enum_map.append_option_list("region_version",obj,true,agree_book);
                    if(bookid != 0 && bookid != -1){
                        obj.val(bookid);
                    }else{
                        obj.val(agree_book[0]);
                    }
                }
            } else {
                alert(result.info);
            }
        }
    });

}


function edit_paper(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement; 
    var edit_index  = $(target).index();
    //console.log(edit_index);
    if(!$(target).hasClass("edit_have")){
        $(target).addClass("edit_have").siblings().removeClass("edit_have");
        $(target).parents(".paper_edit").find(".edit_box").each(function(i,r){
            if(i == edit_index){
                $(this).removeClass("hide");
            }else{
                $(this).addClass("hide");
            }
        })
    }
}

function get_paper_book(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement; 
    var paper = $(target).parents(".paper_info");
    var subject = paper.find('.paper_subject').val();
    var grade = paper.find('.paper_grade').val();
    get_book($('.paper_book'),0,subject,grade);
}
