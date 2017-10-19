/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/proto-cmd_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
      project:	$('#id_project').val(),
      tag:	$('#id_tag').val(),
      query_str:	$('#id_query_str').val()
        });
    }


  $('#id_project').val(g_args.project);
  $('#id_tag').val(g_args.tag);
  $('#id_query_str').val(g_args.query_str);

    $("#id_all_cmd_desc").on("click",function(){
        $.wopen("/proto/cmd_desc?project="+ g_args.project );
    });


    $(".opt-show").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/proto/cmd_desc?project="+ g_args.project +"&cmdid="+ opt_data.cmdid );
    });


  $('.opt-change').set_input_change_event(load_data);
});
