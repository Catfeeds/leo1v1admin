
/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-assistant_info_new2.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			is_part_time:	$('#id_is_part_time').val(),
			rate_score:	$('#id_rate_score').val(),
			assistantid:	$('#id_assistantid').val()
        });
    }


	$('#id_is_part_time').val(g_args.is_part_time);
	$.enum_multi_select( $('#id_is_part_time'), 'assistant_type', function(){load_data();} )
	$('#id_rate_score').val(g_args.rate_score);
	$.enum_multi_select( $('#id_rate_score'), 'star_level', function(){load_data();} )
	$('#id_assistantid').val(g_args.assistantid);


	$('.opt-change').set_input_change_event(load_data);
});

