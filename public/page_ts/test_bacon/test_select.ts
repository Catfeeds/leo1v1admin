/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_bacon-test_select.d.ts" />


$(function(){
    

    $("#opt-test-paper").on("click",function(){
        var id_resource = $("#opt-test-paper");
        $.do_ajax("/test_bacon/get_books",{},function(response){
            var data_list   = [];
            $.each( response.data.list,function(){
                data_list.push([this['resource_id'], this["resource_type"] ]);                                               
            });

            $(this).admin_select_dlg_second({
                header_list     : [ "id","名称" ],
                data_list       : data_list,
                multi_selection : true,
                select_list     : [],
                onChange        : function( select_list,dlg) {
                    var str = '';
                    var str_id = '';
                    $('#id_body .warning').each(function(){
                        str += $(this).find('td:eq(1)').text() + ',';
                        str_id += $(this).find('td:eq(0)').text() + ',';
                    })

                        str_id != '' ? str_id = str_id.substring(0,str_id.length-1) : '' ;
                    str != '' ? str = str.substring(0,str.length-1) : '' ;

                    id_resource.val(str);
                    id_resource.attr({'resource':str_id});
                }
            });
        }) ;


    });

    $("#opt-test-enum").click(function(){
        var id_resource = $("#opt-test-enum");
        var books  = id_resource.attr('books');
        var select_list = [];
        if(books){
            var book_arr = books.split(',');
            for(var x in book_arr){
                select_list.push(book_arr[x]);
            };
        }
        console.log(select_list);
        $(this).admin_select_dlg_second({
            header_list     : [ "id","名称" ],
            data_list       : [],
            select_search   : id_resource.val(),
            enum_name   : "region_version",
            multi_selection : true,
            select_list     : select_list,
            onChange        : function( select_list,dlg) {
                var str = '';
                var str_id = '';
                $('#id_body .warning').each(function(){
                    str += $(this).find('td:eq(1)').text() + ',';
                    str_id += $(this).find('td:eq(0)').text() + ',';
                })

                    str_id != '' ? str_id = str_id.substring(0,str_id.length-1) : '' ;
                str != '' ? str = str.substring(0,str.length-1) : '' ;

                id_resource.val(str);
                id_resource.attr({'resource':str_id});
            }
        });

    })
});
    
