/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-sub_grade_book_tag.d.ts" />

$(function(){
    Enum_map.append_option_list("subject", $("#id_subject"),false,[1,2,3,4,5,6,7,8,9,10,11]);
    Enum_map.append_option_list("grade", $("#id_grade"),false,[101,102,103,104,105,106,201,202,203,301,302,303]);
    Enum_map.append_option_list("region_version", $("#id_book"),false,book);
    Enum_map.append_option_list("resource_type", $("#id_resource_type"));
    if(resource_type == 1 ){
        Enum_map.append_option_list("resource_season", $("#id_season_id"));
        $("#id_season_id").val(g_args.season_id);
    }
    $("#id_subject").val(g_args.subject);
    $("#id_grade").val(g_args.grade);
    $("#id_book").val(g_args.bookid);
    $("#id_resource_type").val(g_args.resource_type);
    // $("#id_book option").each(function(){
    //     if($(this).text() == g_args.textbook){
    //         console.log($(this).val());
    //         $(this).attr("selected",true);
    //         return false;
    //     }
    // })

    $('.opt-change').set_input_change_event(load_data);

    //添加活动
    $('#tag_add').on('click',function(e){

        var arr =  get_public_arr();
        var id_tag = $('<input style="width:80%" onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);"  />');

        arr.push(["学科化标签",id_tag]);

        $.show_key_value_table("添加学科化标签", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
               
                if(!checkPut()) return;

                var tag = id_tag.val();
                if(!tag){
                    BootstrapDialog.alert("学科化标签必填");
                    return false;
                }

                var data = {
                    'grade':$("#check_grade").val(),
                    'subject':$("#check_subject").val(),
                    'bookid':$("#check_book").val(),
                    'resource_type':$("#check_resource_type").val(),
                    'season_id':$("#check_season").val(),
                    'tag':tag,
                }

                $.ajax({
                    type     :"post",
                    url      :"/resource/add_sub_grade_tag",
                    dataType :"json",
                    data     :data,
                    success : function(res){
                        console.log(res);
                        //BootstrapDialog.alert(res.msg);
                        window.location.reload();
                    }
                });
            }
        },function(){
            if( g_args.resource_type != 1 ){
                $("#check_season").val(0);
                $("#check_season").parents('tr').hide();
            }
        },false,800)

    })

    //批量添加
    $('#batach_add').on('click',function(){
        var arr =  get_public_arr();
        var id_tag_1 = $('<input id="tag_1" style="width:80%"  onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);"  />');
        var id_tag_2 = $('<input id="tag_2" style="width:80%"  onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);" />');
        var id_tag_3 = $('<input id="tag_3" style="width:80%"  onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);" />');
        var id_tag_4 = $('<input id="tag_4" style="width:80%"  onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);"  />');
        var id_tag_5 = $('<input id="tag_5" style="width:80%"  onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);" />');
        var id_tag_6 = $('<input id="tag_6" style="width:80%"  onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);" />');
        var id_tag_7 = $('<input id="tag_7" style="width:80%"  onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);" />');
        var id_tag_8 = $('<input id="tag_8" style="width:80%"  onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);" />');
        var id_tag_9 = $('<input id="tag_9" style="width:80%"  onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);" />');
        var id_tag_10 = $('<input id="tag_10" style="width:80%"  onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);" />');

        arr.push(["学科化标签1",id_tag_1]);
        arr.push(["学科化标签2",id_tag_2]);
        arr.push(["学科化标签3",id_tag_3]);
        arr.push(["学科化标签4",id_tag_4]);
        arr.push(["学科化标签5",id_tag_5]);

        arr.push(["学科化标签6",id_tag_6]);
        arr.push(["学科化标签7",id_tag_7]);
        arr.push(["学科化标签8",id_tag_8]);
        arr.push(["学科化标签9",id_tag_9]);
        arr.push(["学科化标签10",id_tag_10]);

        $.show_key_value_table("批量添加标签", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                //checkPut();
                if(!checkPut()) return;

                var tag_arr = [
                    id_tag_1.val(),id_tag_2.val(),id_tag_3.val(),id_tag_4.val(),id_tag_5.val(),
                    id_tag_6.val(),id_tag_7.val(),id_tag_8.val(),id_tag_9.val(),id_tag_10.val(),
                ];
                var has = 0;
                for(var x in tag_arr){
                    if(tag_arr[x] != ''){
                        has = 1;
                    }
                }
                if(has == 0){
                    BootstrapDialog.alert("学科化标签至少填写1个");
                    return false;
                }
                var data = {
                    'grade':$("#check_grade").val(),
                    'subject':$("#check_subject").val(),
                    'bookid':$("#check_book").val(),
                    'resource_type':$("#check_resource_type").val(),
                    'season_id':$("#check_season").val(),
                    'tag_arr':tag_arr,
                }
                //console.log(tag_arr);
                $.ajax({
                    type     :"post",
                    url      :"/resource/batch_add_sub_grade_tag",
                    dataType :"json",
                    data     :data,
                    success : function(res){
                        console.log(res);
                        BootstrapDialog.alert(res.msg);
                        window.location.reload();
                    }
                });
            }
        },function(){
            if( g_args.resource_type != 1 ){
                $("#check_season").val(0);
                $("#check_season").parents('tr').hide();
            }

        },false,800)

    })


    //删除活动
    $('.opt-del').on('click',function(){
        var opt_data = $(this).get_opt_data();

        var id = opt_data.id;
        var title = "你确定删除,标题为" + opt_data.tag + "？";
        var data = {
            'id':id
        };

        BootstrapDialog.confirm(title,function(val ){
            if (val) {
                $.do_ajax("/resource/dele_sub_grade_tag",data);
            }
        });

    })

    $(".opt-set").on('click',function(){
        var opt_data = $(this).get_opt_data();
        var id_subject = $("<select id='check_subject'/>");
        Enum_map.append_option_list("subject",id_subject,true,[1,2,3,4,5,6,7,8,9,10,11]);
        id_subject.val(opt_data.subject);

        var id_grade = $("<select id='check_grade' onchange='get_book(this.options[this.options.selectedIndex].value)'/>");
        Enum_map.append_option_list("grade",id_grade,true,[101,102,103,104,105,106,201,202,203,301,302,303]);
        id_grade.val(opt_data.grade);

        var id_textbook = $("<select id='check_book'/>");
        id_textbook.html($('#id_book').clone().html());
        id_textbook.val(opt_data.bookid);

        var id_resource_type = $("<select id='check_resource_type'  onchange='is_show_season(this.options[this.options.selectedIndex].value)'/>");
        Enum_map.append_option_list("resource_type",id_resource_type,true);
        id_resource_type.val(opt_data.resource_type);

        var id_season = $("<select id='check_season'/>");
        Enum_map.append_option_list("resource_season",id_season,true);
        id_season.val(opt_data.season_id);

        var id_tag = $('<input style="width:80%"  onkeydown="return banInputSapce(event);" onKeyup="return inputSapceTrim(event,this);" />');
        id_tag.val(opt_data.tag);

        var arr =  [
            ["科目", id_subject ],
            ["年级", id_grade ],
            ["教材版本", id_textbook ],
            ["资源类型", id_resource_type ],
            ["春暑秋寒", id_season ],
            ["学科化标签",id_tag],
        ]

        $.show_key_value_table("编辑学科化标签", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
               
                if(!checkPut()) return;

                var tag = id_tag.val();
                if(!tag){
                    BootstrapDialog.alert("学科化标签必填");
                    return false;
                }
                var data = {
                    'id' : opt_data.id,
                    'grade':$("#check_grade").val(),
                    'subject':$("#check_subject").val(),
                    'bookid':$("#check_book").val(),
                    'resource_type':$("#check_resource_type").val(),
                    'season_id':$("#check_season").val(),
                    'tag':tag,
                }

                $.ajax({
                    type     :"post",
                    url      :"/resource/edit_sub_grade_tag",
                    dataType :"json",
                    data     :data,
                    success : function(res){
                        console.log(res);
                        //BootstrapDialog.alert(res.msg);
                        window.location.reload();
                    }
                });
            }
        },function(){

            if( opt_data.resource_type != 1 ){
                $("#check_season").val(0);
                $("#check_season").parents('tr').hide();
            }

        },false,800)
        
    })

    $("#batach_dele").on('click',function(){
        var dele_id_str = '';
        $.each($('.id_str'),function(i,item){
            if($(this).prop('checked')){
                dele_id_str += $.trim($(this).parent().next().text()) + ',';
            }
        });

        if(dele_id_str == ''){
            BootstrapDialog.alert("请选择删除的id!");
            return false;
        }

        dele_id_str = dele_id_str.substr(0,dele_id_str.length-1);

        var data =  {
            "id_str" : dele_id_str,
        };

        console.log(data);

        BootstrapDialog.confirm("你确认删除这些",function(ret){
            if(ret){
                $.do_ajax("/resource/batch_dele_sub_grade_tag",data);
            }
        })
 
    })

    $('.fa-long-arrow-up').on('click',function(){

        var opt_data = $(this).get_opt_data(); 
        var $pre_item = $(this).parents('tr').prev().find('.fa-long-arrow-up');
        var pre_data = $pre_item.get_opt_data();
        //console.log($pre_item);
        if($pre_item.length < 1){
            BootstrapDialog.alert("本条目是本页面第一条,可以显示更多的页面条数来上移排序操作");
            return false;
        }
        if( opt_data.grade != pre_data.grade){
            BootstrapDialog.alert("排序的两个条目必须是相同年级");
            return false;
        }
        if( opt_data.subject != pre_data.subject){
            BootstrapDialog.alert("排序的两个条目必须是相同科目");
            return false;
        }
        if( opt_data.bookid != pre_data.bookid){
            BootstrapDialog.alert("排序的两个条目必须是相同的教材版本");
            return false;
        }

        var data = {
            'up_id':pre_data.id,
            'up_tag':pre_data.tag,
            'down_id':opt_data.id,
            'down_tag':opt_data.tag
        };
        BootstrapDialog.confirm("你确认将本条标签上移",function(ret){
            if(ret){
                $.do_ajax("/resource/order_sub_grade_tag",data);
            }
        })
        
    })

    $('.fa-long-arrow-down').on('click',function(){
        var opt_data = $(this).get_opt_data(); 
        var $next_item = $(this).parents('tr').next().find('.fa-long-arrow-up');
        var next_data = $next_item.get_opt_data();
        //console.log($pre_item);
        if($next_item.length < 1){
            BootstrapDialog.alert("本条目是本页面最后一条,可以显示更多的页面条数来下移排序操作");
            return false;
        }
        if( opt_data.grade != next_data.grade){
            BootstrapDialog.alert("排序的两个条目必须是相同年级");
            return false;
        }
        if( opt_data.subject != next_data.subject){
            BootstrapDialog.alert("排序的两个条目必须是相同科目");
            return false;
        }
        if( opt_data.bookid != next_data.bookid){
            BootstrapDialog.alert("排序的两个条目必须是相同的教材版本");
            return false;
        }

        var data = {
            'up_id':opt_data.id,
            'up_tag':opt_data.tag,
            'down_id':next_data.id,
            'down_tag':next_data.tag
        };

        BootstrapDialog.confirm("你确认将本条标签往下移",function(ret){
            if(ret){
                $.do_ajax("/resource/order_sub_grade_tag",data);
            }
        })

    })

});
    
function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    var season_id =  $('#id_season_id').val()
    if( season_id == null ){
        season_id = -1;
    }
    var data = {
        subject: $('#id_subject').val(),
        grade:        $('#id_grade').val(),
        bookid:        $('#id_book').val(),
        resource_type :$("#id_resource_type").val(),
        season_id : season_id
    };
  
    $.reload_self_page (data);
}

function get_public_arr(){

    var id_subject = $("<select id='check_subject'/>");
    Enum_map.append_option_list("subject",id_subject,true,[1,2,3,4,5,6,7,8,9,10,11]);

    var id_grade = $("<select id='check_grade' onchange='get_book(this.options[this.options.selectedIndex].value)'/>");
    Enum_map.append_option_list("grade",id_grade,true,[101,102,103,104,105,106,201,202,203,301,302,303]);

    var id_textbook = $("<select id='check_book'/>");
    id_textbook.html($('#id_book').clone().html());
    id_textbook.val(g_args.bookid);

    var id_resource_type = $("<select id='check_resource_type'  onchange='is_show_season(this.options[this.options.selectedIndex].value)'/>");
    Enum_map.append_option_list("resource_type",id_resource_type,true);

    var id_season = $("<select id='check_season'/>");
    Enum_map.append_option_list("resource_season",id_season,true);
    id_season.val(g_args.season_id);

    if( g_args.subject < 1){
        id_subject.val(1);
    }else{
        id_subject.val(g_args.subject);
    }

    if( g_args.grade < 1){
        id_grade.val(101);
    }else{
        id_grade.val(g_args.grade);
    }

    if( g_args.resource_type < 1){
        id_resource_type.val(1);
    }else{
        id_resource_type.val(g_args.resource_type);
    }

    var arr =  [
        ["科目", id_subject ],
        ["年级", id_grade ],
        ["教材版本", id_textbook ],
        ["资源类型", id_resource_type ],
        ["春暑秋寒", id_season ],
    ]

    return arr;
}

function get_book(val){
    var grade = $('#check_grade').val();
    
    $.ajax({
        type     : "post",
        url      : "/resource/get_book_by_grade_sub",
        dataType : "json",
        data : {
            'resource_type' : -1,
            'subject'       : val,
            'grade'         : grade,
        } ,
        success   : function(result){
            if(result.ret == 0){
                $('.tag_one').empty();
                $('.tag_one').next().remove();
                var agree_book = result.book;
                if(agree_book.length == 0) {
                    $('.tag_one').after('<p style="color:red;">该资源类型、科目、年级下暂无开放的教材版本!</p>');
                } else {
                    Enum_map.append_option_list("region_version",$('#check_book'),true,agree_book);
                }
            } else {
                alert(result.info);
            }
        }
    });

}

function is_show_season(val){
    if( val != 1 ){
        $("#check_season").val(0);
        $("#check_season").parents('tr').hide();
    }else{
        $("#check_season").val(1);
        $("#check_season").parents('tr').show();
    }
}

//检查必填
var checkPut = function(){

    var subject = $("#check_subject").val();
    var grade = $("#check_grade").val();
    var book = $("#check_book").val();
    var resource_type = $("#check_resource_type").val();
    var season = $("#check_season").val();

    if( subject == '' || subject == -1){
        BootstrapDialog.alert("科目必选");
        return false;
    }

    if( grade == '' || grade == -1){
        BootstrapDialog.alert("年级必选");
        return false;
    }

    if( book == '' || book == -1){
        BootstrapDialog.alert("教材必选");
        return false;
    }

    if( resource_type == '' || resource_type == -1 ){
        BootstrapDialog.alert("资源类型必选");
        return false;
    }

    if( resource_type == 1 &&　season == ''){
        BootstrapDialog.alert("春暑秋寒必选");
        return false;
    }
    return true;
}

/** 
 * 空格输入去除 
 * @param e 
 * @returns {Boolean} 
 */ 
function inputSapceTrim(e,this_temp){ 
    this_temp.value = Trim(this_temp.value,"g"); 
    var keynum; 
    if(window.event){ 
        keynum = e.keyCode 
    }else if(e.which){ 
        keynum = e.which 
    } 
    if(keynum == 32){ 
        return false; 
    } 
    return true; 
}

/** 
 * 是否去除所有空格 
 * @param str 
 * @param is_global 如果为g或者G去除所有的 
 * @returns 
 */ 
function Trim(str,is_global){ 
    var result; 
    result = str.replace(/(^\s+)|(\s+$)/g,""); 
    if(is_global.toLowerCase()=="g"){ 
        result = result.replace(/\s/g,""); 
    } 
    return result; 
} 

/** 
 * 禁止空格输入 
 * @param e 
 * @returns {Boolean} 
 */ 
function banInputSapce(e) { 
    var keynum; 
    if(window.event){ 
        keynum = e.keyCode 
    }else if(e.which){ 
        keynum = e.which 
    } 
    if(keynum == 32){ 
        return false; 
    } 
    return true; 
} 
