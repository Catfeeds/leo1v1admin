/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/company_wx-dissimil_users.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){

    $('.opt-edit').on('click', function() {
        var name = $(this).parent().parent().find('.name').text();
        var phone = $(this).parent().parent().find('.phone').text();

        var e_input = $('<input type=text name=phone value="'+ phone +'">');
        var arr=[
            ["手机号" , e_input],
        ] ;

        $.show_key_value_table("修改", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {
                //var $def_time = $('input[name="def_time"]').val();
                var phone = $('input[name="phone"]').val();
                $.do_ajax('/company_wx/update_phone_data', {
                    'name':name,
                     'phone':phone
                });
            }
        });

        // $.do_ajax('/company_wx/update_phone_data', {
        //     'name':name,
        //     'phone':phone
        // });
    });

	$('.opt-change').set_input_change_event(load_data);
});
