/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/info_support-get_info.d.ts" />


$(function(){
    Enum_map.append_option_list("subject", $("#id_subject"),false,[1,2,3,4,5,6,7,8,9,10,11]);
    $("#id_subject").val(g_args.subject);
    var provice_pro = "<option value='0'>未设置</option>";
    $.each(ChineseDistricts[86],function(i,val){
        provice_pro = provice_pro + '<option value='+i+'>'+val+'</option>'
    });
    $(".province").html(provice_pro);

    var city_pro = "<option value='0'>未设置</option>";
    if(g_args.province > 0 ){
        $.each(ChineseDistricts[g_args.province],function(i,val){
            city_pro = city_pro + '<option value='+i+'>'+val+'</option>'
        });
    }
    $(".city").html(city_pro);

    $('.opt-change').set_input_change_event(load_data);
    
 
    $('.opt_add').on('click',function(){
        
    })

    $(".opt_edit").on("click",function(){
        
    });
});

function load_data(){

    $.reload_self_page ( {
        subject       : $('#id_subject').val(),
        province         : $('#id_province').val(),
        city       : $('#id_city').val(),
    });
}
    
function choose_type(oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var obj = $(target).parents(".tag_con");
    if($(target).text()){
        var resource_id = $(target).attr('resource_id');
        var index = 0;
        $(obj).find(".resource_son").each(function(i){
            if($(this).attr('resource_id') == resource_id){
                $(this).removeClass("hide");
                index = i;
            }else{
                $(this).addClass("hide");
            }
        });
        console.log(index);
        $(obj).find(".resource_fa span:eq('"+index+"')").addClass("resource_check").siblings().removeClass("resource_check");
    }
}

function check_type(oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    $(target).addClass("resource_check").siblings().removeClass("resource_check");

}

function get_books_val(obj,id_obj){
 
}
