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

    $('.opt-change').set_input_change_event(load_data);

    //添加试卷
    $('.add_new_paper').on('click',function(){
        var paper = $(".paper_edit").clone();
        paper.removeClass("hide");
        var dlg= BootstrapDialog.show({
            title: "添加试卷",
            message : paper,
            buttons: [{
                label: '返回',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
        dlg.getModalDialog().css("width", "1030px");
    })

    //编辑试卷
    $('.opt-edit').onclick('click',function(){
        var opt_data = $(this).parents('tr').get_self_opt_data();
        var paper_id = opt_data.paper_id;
 
        do_ajax('/test_paper/get_paper',{'paper_id':paper_id},function(ret){
            console.log(ret);
            if(ret.ret == 0){
                if( ret.status == 200 ){
                    var paper = $(".paper_edit").clone();
                    paper.removeClass("hide");

                    var dlg= BootstrapDialog.show({
                        title: "添加试卷",
                        message : paper,
                        buttons: [{
                            label: '返回',
                            cssClass: 'btn-warning',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }]
                    });
                    dlg.getModalDialog().css("width", "1030px");
                }
            }
        });

    })
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
        var answer_length = $(target).parents(".paper_edit").find(".edit_box:eq(0) .edit_answer").length;
        if( ( edit_index == 2 || edit_index == 3 ) && answer_length == 1 ){
            BootstrapDialog.alert("请先插入题目！");
            return false;
        }

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

function add_answer(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var could_add = 1;
    $(target).parents(".paper_answer").find('.edit_answer:gt(0)').each(function(i){
        $(this).find("input").each(function(){
            if($(this).val() == ""){
                could_add = 0;
                return false;
            }
        });
    });

    if(could_add == 0 ){
        BootstrapDialog.alert("请先填写完整，再添加！");
        return false;
    }
    var answer_index = 1;
    if( $(target).parents(".paper_answer").find('.edit_answer').length>1 ){
        var pre_index = $(target).parents(".paper_answer").find('.edit_answer:last').find("input:eq(0)").val();
        answer_index = parseInt(pre_index) + 1;
    }
    var answer = $(target).parents(".paper_answer").find('.edit_answer:eq(0)').clone();
    answer.removeClass("hide");
    answer.find("input:eq(0)").val(answer_index);
    $(target).parents("tr").before(answer);
    
}

function answer_insert(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement; 
    var could_add = 1;
    $(target).parents(".paper_answer").find('.edit_answer:gt(0)').each(function(i){
        $(this).find("input").each(function(){
            if($(this).val() == ""){
                could_add = 0;
                return false;
            }
        });
    });

    if(could_add == 0 ){
        BootstrapDialog.alert("请先填写完整，再插入！");
        return false;
    }
    var cur_index = $(target).parents("tr").index();
    var cur_index_no = parseInt($(target).parents("tr").find("input:eq(0)").val());
    $(target).parents(".paper_answer").find('.edit_answer:gt(' + cur_index + ')').each(function(i){
        var now_index_no = parseInt( $(this).find("input:first").val());
        var new_index_no = now_index_no + 1;
        $(this).find("input:first").val(new_index_no)
        
    });

    var answer = $(target).parents(".paper_answer").find('.edit_answer:eq(0)').clone();
    answer.removeClass("hide");
    answer.find("input:eq(0)").val(cur_index_no+1);
    $(target).parents("tr").after(answer);

}

function answer_up(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement; 
    var cur_index = $(target).parents("tr").index();
    if( cur_index == 1 ){
        BootstrapDialog.alert("已经是顶部，不能上移！");
        return false;
    }
    var could_move = 1;
    $(target).parents("tr").find("input").each(function(){
        if($(this).val() == ""){
            could_move == 0;
            return false;
        };
    });

    $(target).parents("tr").prev().find("input").each(function(){
        if($(this).val() == ""){
            could_move == 0;
            return false;
        };
    });

    if(could_move == 0 ){
        BootstrapDialog.alert("请将本条目和上一个条目填写完整，再置换顺序！");
        return false;
    }
    var prev_obj = $(target).parents("tr").prev();
    $(target).parents("tr").after(prev_obj);
}

function answer_down(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement; 
    var cur_index = $(target).parents("tr").index();
    var whole_length = $(target).parents(".paper_answer").find('.edit_answer').length;
    if( cur_index == whole_length - 1 ){
        BootstrapDialog.alert("已经是底部，不能下移！");
        return false;
    }
    var could_move = 1;
    $(target).parents("tr").find("input").each(function(){
        if($(this).val() == ""){
            could_move == 0;
            return false;
        };
    });

    $(target).parents("tr").next().find("input").each(function(){
        if($(this).val() == ""){
            could_move == 0;
            return false;
        };
    });

    if(could_move == 0 ){
        BootstrapDialog.alert("请将本条目和下一个条目填写完整，再置换顺序！");
        return false;
    }

    var next_obj = $(target).parents("tr").next();
    $(target).parents("tr").before(next_obj);
    
}

function answer_dele(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement; 
    var cur_index = $(target).parents("tr").index();
    $(target).parents(".paper_answer").find('.edit_answer:gt(' + cur_index + ')').each(function(i){
        var now_index_no = parseInt( $(this).find("input:first").val());
        var new_index_no = now_index_no - 1;
        $(this).find("input:first").val(new_index_no)
        
    });
    $(target).parents("tr").remove();
}

function add_dimension(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var could_add = 1;
    $(target).parents(".paper_dimension").find('.edit_dimension:gt(0)').each(function(i){        
        if($(this).find("input").val() == ""){
            could_add = 0;
            return false;
        }        
    });

    if(could_add == 0 ){
        BootstrapDialog.alert("请先填写完整，再添加！");
        return false;
    }
    var answer_index = 1;
    if( $(target).parents(".paper_dimension").find('.edit_dimension').length > 1 ){
        var pre_index = $(target).parents(".paper_dimension").find('.edit_dimension:last').find("td:eq(0)").text();
        answer_index = parseInt(pre_index) + 1;
    }
    var answer = $(target).parents(".paper_dimension").find('.edit_dimension:eq(0)').clone();
    answer.removeClass("hide");
    answer.find("td:eq(0)").text(answer_index);
    $(target).parents("tr").before(answer);

}

function dimension_dele(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    $(target).parents("tr").remove();
}

function save_answer(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var cur_obj = $(target).parents('.edit_box');
    var paper_id = cur_obj.find(".paper_id").val();
    var paper_name = cur_obj.find(".paper_name").val();
    var subject = cur_obj.find(".paper_subject").val();
    var grade = cur_obj.find(".paper_grade").val();
    var volume = cur_obj.find(".paper_volume").val();
    var book = cur_obj.find(".paper_book").val();
    
    var answer = [];
    var could_save = 1;
    var could_answer = 1;
    cur_obj.find("table tbody tr.edit_answer:gt(0)").each(function(){
        var item_1 = $(this).find("input:eq(0)").val();
        var item_2 = $(this).find("input:eq(1)").val();
        var item_3 = $(this).find("input:eq(2)").val();
        var item_4 = $(this).find("input:eq(3)").val();
        if( item_1 == "" || item_2 == "" || item_3 == "" || item_4 == "" ){
            could_answer = 0;
            return false;
        }
        var item = [item_1,item_2,item_3,item_4];
        answer.push(item);
    });

    if( paper_id == "" || paper_name == "" ){
        BootstrapDialog.alert("试卷id，试卷名字填写完整");
        could_save == 0;
        return false;
    }
    
    if( subject == -1 || grade == -1 || volume == -1 || book == -1){
        BootstrapDialog.alert("年级，科目，上下册，教材填写完整");
        could_save == 0;
        return false;
    }

    if( could_answer == 0){
        BootstrapDialog.alert("将题目信息填写完整");
        return false;
    }

    var data = {
        'paper_id'   : paper_id,
        'paper_name' : paper_name,
        'subject'    : subject,
        'grade'      : grade,
        'volume'     : volume,
        'book'       : book,
        'answer'     : answer,
        'save_type'  : 1,
    };

    console.log(data);

    if( could_answer == 1 && could_save == 1){
        $.ajax({
            type     : "post",
            url      : "/test_paper/save_paper_answer",
            dataType : "json",
            data : data,
            success   : function(result){
                if(result.ret == 0){
                    BootstrapDialog.alert("保存成功");
                } else {
                    alert(result.info);
                }
            }
        });

    }
}

function save_dimension(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
}
