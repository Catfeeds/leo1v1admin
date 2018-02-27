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
    $('.opt-edit').on('click',function(){
        var opt_data = $(this).parents('tr').get_self_opt_data();
        var paper_id = opt_data.paper_id;
 
        do_ajax('/test_paper/get_paper',{'paper_id':paper_id},function(ret){
            console.log(ret);
            if(ret.ret == 0){
                if( ret.status == 200 ){
                    //console.log(ret);
                    var paper = $(".paper_edit").clone();
                    paper.removeClass("hide");

                    var dlg= BootstrapDialog.show({
                        title: "编辑试卷",
                        message : paper,
                        buttons: [{
                            label: '返回',
                            cssClass: 'btn-warning',
                            action: function(dialog) {
                                dialog.close();
                            }
                        }]
                    });
                    var info = ret.paper;
                    //题目
                    paper.find('.paper_id').val(info.paper_id);
                    paper.find('.paper_name').val(info.paper_name);
                    paper.find('.paper_grade').val(info.grade);
                    paper.find('.paper_subject').val(info.subject);
                    paper.find('.paper_volume').val(info.volume);
                    get_book(paper.find('.paper_book'),0,info.subject,info.grade);
                    paper.find('.paper_book').val(info.book);
                    var answer = info.answer;
                    if(answer != ''){
                        var answer_arr = $.parseJSON(answer);
                        for(var x in answer_arr){
                            var answer_tr = paper.find(".edit_answer:first").clone().removeClass("hide");
                            answer_tr.find("input:eq(0)").val(x);
                            answer_tr.find("input:eq(1)").val(answer_arr[x][0]);
                            answer_tr.find("input:eq(2)").val(answer_arr[x][1]);
                            answer_tr.find("input:eq(3)").val(answer_arr[x][2]);
                            paper.find(".paper_answer tbody tr.edit_answer:last").after(answer_tr);
                        }
                    }

                    //维度名称
                    if( info.dimension != ''){
                        var dimension_arr = $.parseJSON(info.dimension);
                        for(var x in dimension_arr){
                            var dimension_tr = paper.find(".edit_dimension:first").clone().removeClass("hide");
                            dimension_tr.find("td:eq(0)").text(x);
                            dimension_tr.find("td:eq(1) input").val(dimension_arr[x]);
                            paper.find(".paper_dimension tbody tr.edit_dimension:last").after(dimension_tr);
                        }
                    }
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

//点击tabq切换不同的页面
function edit_paper(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement; 
    var edit_index  = $(target).index();
    //console.log(edit_index);
    if(!$(target).hasClass("edit_have")){
        var answer_length = $(target).parents(".paper_edit").find(".edit_box:eq(0) .edit_answer").length;
        if( ( edit_index == 2 || edit_index == 3 ) && answer_length == 1 ){
            BootstrapDialog.alert("请先插入题目和维度并且保存！");
            return false;
        }

        var paper_id = $(target).parents('.paper_edit').find('.edit_box:eq(0) .paper_id').val();
        
        //绑定题目
        if( edit_index == 2 ){
            dimension_pub_bind(0,$(target).parents(".paper_edit").find(".edit_box:eq(2)") );
        }

        //绑定维度结果建议
        if( edit_index == 3 ){
            do_ajax('/test_paper/get_paper',{'paper_id':paper_id},function(ret){
                if( ret.ret == 0 && ret.status == 200){
                    $(target).parents(".paper_edit").find(".edit_box:eq(3) .suggestion_info tbody tr.suggest_item:gt(0)").remove();
                    //维度名称
                    if( ret.paper.dimension != ''){
                        var dimension_arr = $.parseJSON(ret.paper.dimension);
                        for(var x in dimension_arr){
                            var dimension_tr = $(target).parents(".paper_edit").find(".edit_box:eq(3) .suggestion_info tbody tr.suggest_item:first").clone().removeClass("hide");
                            dimension_tr.find("td:eq(0)").text(x);
                            dimension_tr.find("td:eq(1)").text(dimension_arr[x]);
                            $(target).parents(".paper_edit").find(".edit_box:eq(3) .suggestion_info tbody tr.suggest_item:last").after(dimension_tr);
                        }
                    }

                }
            });
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

//
function get_paper_book(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement; 
    var paper = $(target).parents(".paper_info");
    var subject = paper.find('.paper_subject').val();
    var grade = paper.find('.paper_grade').val();
    get_book($('.paper_book'),0,subject,grade);
}

//添加评测卷信息
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

//评测卷信息插入题目信息
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

//评测卷信息上移
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

//评测卷信息下移
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

//评测卷信息删除信息
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

//维度设置删除
function dimension_dele(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    $(target).parents("tr").remove();
}

//评卷测信息保存
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

//维度保存
function save_dimension(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var paper_id = $(target).parents('.paper_edit').find('.edit_box:eq(0) .paper_id').val();
    var cur_obj = $(target).parents('.edit_box');
    var could_answer = 1;
    var dimension = [];
    cur_obj.find("table tbody tr.edit_dimension:gt(0)").each(function(){
        var item_1 = $(this).find("td:eq(0)").text();
        var item_2 = $(this).find("td:eq(1) input").val();
        if( item_1 == "" || item_2 == "" ){
            could_answer = 0;
            return false;
        }
        var item = [item_1,item_2];
        dimension.push(item);
    });

    var data = {
        'paper_id'   : paper_id,
        "dimension"  : dimension,
        'save_type'  : 2,
    };

    console.log(data);

    if( could_answer == 1 ){
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

//获取每一个维度绑定的题目
function dimension_bind(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var dimension = $(target).parents("td").attr("dimension");
    $(target).parents(".edit_box").find(".dimension_item").val(dimension);
    dimension_pub_bind(dimension,$(target).parents(".edit_box"));
}

//点击select框获取每一个维度绑定的题目
function get_dimension(dimension,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    dimension_pub_bind(dimension,$(target).parents(".edit_box"));
}

function dimension_pub_bind(dimension,obj){
    var paper_id = obj.parents(".paper_edit").find(".edit_box:eq(0) .paper_id").val();
   
    if(parseInt(dimension) != 0){
        obj.find(".dimension_box").addClass("hide");
        obj.find(".dimension_bind").removeClass("hide");
        do_ajax('/test_paper/get_paper',{'paper_id':paper_id},function(ret){
            if( ret.ret == 0 && ret.status == 200){
                var answer_arr = ret.paper.answer;
                var dimension_arr = ret.paper.dimension;
                var bind_arr = ret.paper.question_bind;
                var have_bind = [];
                var have_dimension_name = "";
                if( dimension_arr != "" ){
                    var dimension_str = $.parseJSON(dimension_arr);
                    for(var x in dimension_str){
                        if( parseInt(x) == parseInt(dimension)){
                            have_dimension_name = dimension_str[x];
                            continue;
                        }
                    }
                }
                
                if( bind_arr != ""){
                    var bind_str = $.parseJSON(bind_arr);
                    for(var x in bind_str){
                        if( parseInt(x) == parseInt(dimension) ){
                            have_bind =  bind_str[x];
                            continue;
                        }
                    }
                }

                if( answer_arr != ""){
                    var answer_str = $.parseJSON(answer_arr);
                    obj.find(".dimension_answer:gt(0)").remove();
                    for(var x in answer_str){
                        var answer_tr = obj.find(".dimension_answer:first").clone().removeClass("hide");
                        answer_tr.find("td:eq(0) input").attr({"id":x});
                        if( have_bind.length > 0 && $.inArray(x,have_bind) >= 0 ){
                            answer_tr.find("td:eq(0) input").attr({"checked":"checked"});
                            answer_tr.find("td:eq(2)").html("<span>"+have_dimension_name+"</span>");
                        }else{
                            answer_tr.find("td:eq(2)").html("<span class='hide'>"+have_dimension_name+"</span>");
                        }
                        answer_tr.find("td:eq(1)").text(answer_str[x][0]);
                        
                        obj.find(".dimension_answer:last").after(answer_tr);
                    }
                }
            }
        });

    }else{
        obj.find(".dimension_box").removeClass("hide");
        obj.find(".dimension_bind").addClass("hide");
        do_ajax('/test_paper/get_paper',{'paper_id':paper_id},function(ret){
            if( ret.ret == 0 && ret.status == 200){
                var question_bind = ret.paper.question_bind;
                var answer = ret.paper.answer;
                var dimension = ret.paper.dimension;
                var bind_arr = [],answer_arr = [],dimension_arr = [];
                var option_str = "<option value='0'>全部</option>";
                if( answer != ""){
                    answer_arr = $.parseJSON(answer);
                }
                if( question_bind != ""){
                    bind_arr = $.parseJSON(question_bind);
                }
           
                if( dimension != ""){
                    dimension_arr = $.parseJSON(dimension);
                    obj.parents(".paper_edit").find(".edit_box:eq(2) .dimension_var:gt(0)").remove();
                    for(var x in dimension_arr){
                        option_str += "<option value='"+x+"'>"+dimension_arr[x]+"</option>";
                        var dimension_var = obj.parents(".paper_edit").find(".edit_box:eq(2) .dimension_var:first").clone().removeClass("hide");
                        dimension_var.find("td:eq(0)").text(dimension_arr[x]);

                        if( question_bind != "" ){
                            var bind_question = bind_arr[x];
                            //console.log(bind_question);
                            if( bind_question != undefined){
                                var bind_html = "";
                                for(var y in bind_question){
                                    bind_html += answer_arr[bind_question[y]][0] + "<br/>";
                                }
                                dimension_var.find("td:eq(1)").html(bind_html);
                            }else{
                                dimension_var.find("td:eq(1)").html("<span style='color:#999'>此维度未绑定题目</span>");
                            }
                            
                        }else{
                            dimension_var.find("td:eq(1)").html("<span style='color:#999'>此维度未绑定题目</span>");
                        }
                        dimension_var.find("td:eq(2)").attr({"dimension":x});
                        obj.parents(".paper_edit").find(".edit_box:eq(2) .dimension_var:last").after(dimension_var);
                    }
                    obj.parents(".paper_edit").find(".edit_box:eq(2) .dimension_item").html(option_str);
                }

            }
            
        })
    }
}

function click_dimension(oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var hasChk = $(target).is(':checked');
    if(hasChk){
        $(target).parents("tr").find("td:eq(2) span").removeClass("hide");
    }else{
        $(target).parents("tr").find("td:eq(2) span").addClass("hide");
    }
}

function save_bind(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var paper_id = $(target).parents(".paper_edit").find(".edit_box:eq(0) .paper_id").val();
    var cur_obj = $(target).parents('.edit_box');
    var could_save = 1;
    var item = [];
    var dimension_id = $(cur_obj).find(".dimension_item").val();
    cur_obj.find("table tbody tr.dimension_answer:gt(0)").each(function(){
        var checked_obj = $(this).find("td:eq(0) input");
        if( checked_obj.is(':checked') ){
            item.push(checked_obj.attr("id"));
        }
    });

    var data = {
        paper_id:paper_id,
        dimension_id:dimension_id,
        bind:item,
    };
    $.ajax({
        type     : "post",
        url      : "/test_paper/save_dimension_answer",
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

function suggest_set(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var paper_id = $(target).parents(".paper_edit").find(".edit_box:eq(0) .paper_id").val();
    var dimension_id = $(target).parents("tr").find("td:eq(0)").text();
    do_ajax('/test_paper/get_paper',{'paper_id':paper_id},function(ret){
        if( ret.ret == 0 && ret.status == 200){
            var question_bind = ret.paper.question_bind;
            var answer = ret.paper.answer;
            var dimension = ret.paper.dimension;
            var suggestion = ret.paper.suggestion;

            var bind_arr = [],answer_arr = [],dimension_arr = [],suggest_arr = [];
            if( answer == ""){
                BootstrapDialog.alert("先添加题目");
                return false;
            }
            if( question_bind == ""){
                BootstrapDialog.alert("先为维度绑定题目");
                return false;
            }
            
            answer_arr = $.parseJSON(answer);
            bind_arr = $.parseJSON(question_bind);
            dimension_arr = $.parseJSON(dimension);
            var score_total = 0;
            var dimension_name = dimension_arr[dimension_id];
            var binds = bind_arr[dimension_id];
            if( binds != undefined ){
                for(var x in binds){
                    score_total += parseInt(answer_arr[binds[x]][2]);

                }
            }
            var cur_obj = $(target).parents(".edit_box").find(".suggest_result");
            cur_obj.removeClass("hide");
            cur_obj.find(".suggest_dimension font").attr({"dimension":dimension_id});
            cur_obj.find(".suggest_dimension font").text(dimension_name);
            cur_obj.find(".score_total font").text(score_total);
            cur_obj.find(".suggest_score .score_min").val("");
            cur_obj.find(".suggest_score .score_max").val("");
            if( suggestion != ""){
                suggest_arr = $.parseJSON(suggestion);
                cur_obj.find("tbody tr.suggest_item:gt(0)").remove();
                if( suggest_arr[dimension_id] != undefined ){
                    console.log(suggest_arr);
                    for( var x in suggest_arr[dimension_id] ){
                        var sug_tr = cur_obj.find("tbody tr.suggest_item:first").clone().removeClass("hide");
                        //sug_tr.find("td:eq(0) input").attr({"id":x});                       
                        sug_tr.find("td:eq(0)").text(x);
                        sug_tr.find("td:eq(1)").text(suggest_arr[dimension_id][x]);
                        cur_obj.find("tbody tr.suggest_item:last").after(sug_tr);

                    }
                }
            }else{
                cur_obj.find("tbody tr.suggest_item:gt(0)").remove();
            }

        }else{
            BootstrapDialog.alert("先为维度绑定题目");
        }

    })
}

function save_suggest(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var paper_id = $(target).parents(".paper_edit").find(".edit_box:eq(0) .paper_id").val();
    var cur_obj = $(target).parents(".edit_box").find(".suggest_result");
    var dimension = cur_obj.find(".suggest_dimension font").attr("dimension");
    var score_total = parseInt(cur_obj.find(".score_total font").text());

    var score_min = cur_obj.find('.score_min').val();
    var score_max = cur_obj.find('.score_max').val();
    var suggestion = cur_obj.find(".suggest_supply textarea").val();
    if( score_min == ""){
        BootstrapDialog.alert("请填写最小得分");
        return false;
    }
    if( score_max == ""){
        BootstrapDialog.alert("请填写最大得分");
        return false;
    }

    if( suggestion == ""){
        BootstrapDialog.alert("请填写维度建议");
        return false;
    }

    if( parseInt(score_min) >= parseInt(score_max)){
        BootstrapDialog.alert("最小得分不能大于最大得分");
        return false;
    }
    if( parseInt(score_max) > score_total){
        BootstrapDialog.alert("最大得分不能大于总得分");
        return false;
    }
    var data = {
        paper_id : paper_id,
        dimension_id : dimension,
        score_min : score_min,
        score_max : score_max,
        suggestion : suggestion,
    };
    $.ajax({
        type     : "post",
        url      : "/test_paper/save_suggestion",
        dataType : "json",
        data : data,
        success   : function(result){
            if(result.ret == 0){
                BootstrapDialog.alert("保存成功");
                var is_edit = cur_obj.find(".suggest_supply").attr("edit");
                var score_range = score_min + "-" + score_max; 
                if(is_edit == undefined){
                    //新增维度结果建议                    
                    var sug_tr = cur_obj.find("tbody tr.suggest_item:first").clone().removeClass("hide");                  
                    sug_tr.find("td:eq(0)").text(score_range);
                    sug_tr.find("td:eq(1)").text($.trim(suggestion));
                    cur_obj.find("tbody tr.suggest_item:last").after(sug_tr);
                }else{
                    //编辑维度结果建议
                    var edit_str = cur_obj.find("tbody tr.suggest_item_edit");
                    edit_str.find("td:eq(0)").text(score_range);
                    edit_str.find("td:eq(1)").text($.trim(suggestion));
                    cur_obj.find(".suggest_supply").removeAttr("edit");
                    cur_obj.find("tbody tr.suggest_item_edit").removeClass("suggest_item_edit");
                }
                cur_obj.find(".suggest_score .score_min").val("");
                cur_obj.find(".suggest_score .score_max").val("");
                cur_obj.find(".suggest_supply textarea").val("");
            } else {
                alert(result.info);
            }
        }
    });

}

//编辑维度结果建议
function suggest_edit(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var paper_id = $(target).parents(".paper_edit").find(".edit_box:eq(0) .paper_id").val();
    var cur_obj = $(target).parents(".edit_box").find(".suggest_result");
    var score_range = $(target).parents("tr").find("td:eq(0)").text();
    var suggestion = $(target).parents("tr").find("td:eq(1)").text();
    var score_arr = score_range.split('-');
    var score_min = score_arr[0];
    var score_max = score_arr[1];
    $(target).parents("tr").addClass("suggest_item_edit");
    cur_obj.find(".suggest_supply").attr({"edit":1});
    cur_obj.find(".suggest_score .score_min").val(score_min);
    cur_obj.find(".suggest_score .score_max").val(score_max);
    cur_obj.find(".suggest_supply textarea").val(suggestion);
}

function suggest_dele(obj,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var paper_id = $(target).parents(".paper_edit").find(".edit_box:eq(0) .paper_id").val();
    var cur_obj = $(target).parents(".edit_box").find(".suggest_result");
    var dimension = cur_obj.find(".suggest_dimension font").attr("dimension");
    var score_range = $(target).parents("tr").find("td:eq(0)").text();
    var data = {
        paper_id : paper_id,
        dimension_id : dimension,
        score_range : score_range
    };

    $.ajax({
        type     : "post",
        url      : "/test_paper/dele_suggestion",
        dataType : "json",
        data : data,
        success   : function(result){
            if(result.ret == 0){
                BootstrapDialog.alert("删除成功！");
                $(target).parents("tr").remove();
            }else{
                alert(result.info);
            }
        }
    })
}
