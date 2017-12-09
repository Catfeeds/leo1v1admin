/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-resource_frame_new.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
function add_book(obj){

}


$(function(){



    var get_next_info = function(obj){
        var info_str = obj.attr('info_str');
        var level = obj.attr('level');
        do_ajax( "/resource/get_next_info_js",{
            'info_str' : info_str,
        },function(ret){
            // console.log(ret);
            if(ret.ret==0){
                var ret_data = ret.data, add_str = '',select = ret.select;
                $.each(ret_data, function(i,val){
                    var new_info = info_str+'-'+val[select];
                    var next_level = parseInt(level)+1;
                    var tr_str = get_add_tr(next_level, new_info, val[select+'_str'], info_str);
                    add_str = add_str + tr_str;
                });
                obj.after(add_str);

            } else {
                alert(ret.info);
            }
        });
    }

    var get_add_tr = function(level, info_str, td_text, key){
        var tr_str = '<tr level='+level+' info_str='+info_str+' key='+key+'>';
        var resource = parseInt( key );
        var re_arr = [1,2,3,4,5,9];
        if( ($.inArray(resource, re_arr) > -1) && level == 3){
            for(var i=0;i<7;i++){
                if( i==(level-1) ){
                    tr_str = tr_str+'<td>'+td_text+'</td>';
                } else if(i==level){
                    tr_str = tr_str+'<td onclick="add_book(this)">添加教材</td>';
                } else {
                    tr_str = tr_str+'<td></td>';
                }
            }
        } else {
            for(var i=0;i<7;i++){
                if( i==(level-1) ){
                    tr_str = tr_str+'<td>'+td_text+'</td>';
                } else {
                    tr_str = tr_str+'<td></td>';
                }
            }

        }
        tr_str = tr_str + '</tr>';
        $(tr_str).contextify(options);

        return tr_str;
    }

    $('table').on('click','tr',function(){
        if( $(this).hasClass('get_mark') ){
            var hide_info = $(this).attr('info_str');
            if( $(this).hasClass('hide_mark') ){
                $(this).removeClass('hide_mark');
                $('tr[key='+hide_info+']').show();
            } else {
                $(this).addClass('hide_mark');
                $('tr[info_str^='+hide_info+'-]').hide();
            }
        } else {
            $(this).addClass('get_mark')
            get_next_info($(this));
        }
    });


    var data_str = '';
    //右键自定义
    var options = {items:[
        {text: '启用', onclick: function() {
            $('#contextify-menu').hide();
            ajax_submit(data_str,'use');
        },class:'menu_use'},
        {text: '禁用',onclick: function() {
            $('#contextify-menu').hide();
            ajax_submit(data_str,'ban');
        },class:'menu_ban'},
        {text: '选择教材版本', onclick: function() {
            $('#contextify-menu').hide();
            add_or_del_or_edit(data_str,'add');
        },class:'menu_select hide'},
        {text: '删除', onclick: function() {
            $('#contextify-menu').hide();
            ajax_submit(data_str,'del');
        },class:'menu_del hide'},
    ],before:function(one, two){
        console.log(one)
        console.log(two)
        // data_str = $(this).parent().attr('info_str');
        // td_index = $(this).attr('index');
        // is_ban   = $(this).parent().data('is_ban');
    },onshow:function(){
        // if(td_index == 4 ){
        //     $('.menu_select,.menu_del').removeClass('hide');
        // }
        // if(is_ban == 0){
        //     $('.menu_use').text('启用√');
        // } else if(is_ban == 1) {
        //     $('.menu_ban').text('禁用√');
        // }
    }};


    // $('table').contextify(options);
    var menu_hide = function(){
        $('#contextify-menu').hide();
        return $('#contextify-menu');
    };

    $('body').click(function(){
        menu_hide();
    });

    $('.opt-change').set_input_change_event(load_data);
});
