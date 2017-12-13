/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-resource_frame.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){

    // $(".common-table").tbody_scroll_table(500);
    $(".common-table").table_group_level_more_init(8);

    $('.l-3 .key4').each(function(i){
        var r_type = $(this).parent().data('resource_type');
        if(r_type < 6 || r_type == 9){
            $(this).css({
                color: "#3c8dbc",
                cursor:"pointer"
            });
            $(this).on('click',function(){
                var info_str = $(this).prev().data('class_name');
                add_or_del_or_edit(info_str,'add');
            });
        }
    });

    var add_or_del_or_edit = function(info_str,do_type){
        var id_book = $("<select />");
        Enum_map.append_option_list("region_version",id_book,true);
        var arr= [
            ["添加教材版本：", id_book],
        ];

        $.show_key_value_table('添加教材版本', arr,{
            label    : '确认',
            cssClass : 'btn-info',
            action   : function() {

                if(id_book.val() > 0){
                    ajax_submit(info_str,do_type,id_book.val());
                } else {
                    alert('请选择教材版本!');
                }

            }
        },function(){},600);

    };

    var ajax_submit = function(info_str,do_type,id_book){
        $.ajax({
            type     : "post",
            url      : "/resource/add_or_del_or_edit",
            dataType : "json",
            data : {
                'info_str' : info_str,
                'region'   : id_book,
                'do_type'  : do_type,
            } ,
            success : function(result){
                if(result.ret == 0){
                    window.location.reload();
                } else {
                    alert(result.info);
                }
            }
        });
    };
    var data_str = '',td_index =0,is_ban=0;
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
    ],before:function(){
        data_str = $(this).attr('class_name');
        td_index = $(this).attr('index');
        // is_ban   = $(this).parent().data('is_ban');
    },onshow:function(){
        if(td_index == 4 ){
            $('.menu_select,.menu_del').removeClass('hide');
        }
        // if(is_ban == 0){
        //     $('.menu_use').text('启用√');
        // } else if(is_ban == 1) {
        //     $('.menu_ban').text('禁用√');
        // }
    }};

    $('[data-is_ban]').each(function(){
        if($(this).data('is_ban') == 1){
            console.log($(this))
            $(this).css('color','red');
        }
    });

    $('.right-menu').each(
        function(){
            var info = $(this).data('class_name');
            if(info != ''){
                $(this).contextify(options);
            };
        }
    );

    var menu_hide = function(){
        $('#contextify-menu').hide();
        return $('#contextify-menu');
    };

    $('body').click(function(){
        menu_hide();
    });




    $('.opt-change').set_input_change_event(load_data);
});
