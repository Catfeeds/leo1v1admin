/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_share-index.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            sign : g_args.sign ,
            dir : g_args.dir ,
        });

    }

    $(".opt-download ").each(function(){
        var opt_data=$(this).get_opt_data();
        var $file_name=$(this).closest("tr").find(".file_name");
        if  (opt_data.is_dir) {
            $file_name.html("<a href=\"/teacher_share/?sign="+encodeURIComponent(g_args.sign) +"&dir="+ opt_data.abs_path +"\" > "+ opt_data.file_name+" </a> ");
            $(this).hide();
        }

    });



    $(".opt-download").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.do_ajax("/teacher_share/get_download_url",{
            "file_path" : opt_data.abs_path,
            "sign" : g_args.sign

        },function(resp){
            $.wopen(resp.url);

        });
    });


});
