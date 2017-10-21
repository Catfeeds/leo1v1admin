$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			      end_time:	$('#id_end_time').val(),
            history_data: $('#id_history_data').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
    $('#id_history_data').val(g_args.history_data);

    //$('#id_history_data').val(g_args.history_data);
    $('#download_data').on("click", function(){
        var list_data = [ 
            ["科目",'面试通过人数','培训参训率','培训参训新师人数','培训合格率','培训合格新师人数','模拟试听总排课率','模拟试听总排课人数','模拟试听总上课率','模拟试听总上课人数','模拟试听总通过率','模拟试听总通过人数','入职总人数'],
        ];

        // 总计
        var total = g_data.total;
        var data_line = ['总计', total.sum];
        if (total.sum != 0) {
            data_line.push(((total.train_tea_sum/total.sum ).toFixed(2) * 100) + '%');
        } else {
            data_line.push(0);
        }
        data_line.push(total.train_tea_sum);
        if (total.sum != 0) {
            data_line.push(((total.train_qual_sum/total.train_tea_sum ).toFixed(2) * 100) + '%');
        } else {
            data_line.push(0);
        }
        data_line.push(total.train_qual_sum);
        if (total.sum != 0) {
            data_line.push(((total.imit_sum/total.train_qual_sum ).toFixed(2) * 100) + '%');
        } else {
            data_line.push(0);
        }
        data_line.push(total.imit_sum);
        if (total.imit_sum != 0) {
            data_line.push(((total.attend_sum/total.imit_sum ).toFixed(2) * 100) + '%');
        } else {
            data_line.push(0);
        }
        data_line.push(total.attend_sum)
        if (total.attend_sum != 0) {
            data_line.push(((total.adopt_sum/total.attend_sum ).toFixed(2) * 100) + '%');
        } else {
            data_line.push(0);
        }
        data_line.push(total.adopt_sum, total.adopt_sum);
        
        list_data.push(data_line);
        var info = g_data.info;
        for(var i = 0; i < info.length; i ++) {
            var data_line = [];
            data_line.push(info[i].grade_str + info[i].subject_str, info[i].sum);
            if (info[i].sum != 0) {
                data_line.push(((info[i].train_tea_sum/info[i].sum ).toFixed(2) * 100) + '%');
            } else {
                data_line.push(0);
            }
            data_line.push(info[i].train_tea_sum);
            if (total.sum != 0) {
                data_line.push(((info[i].train_qual_sum/info[i].train_tea_sum ).toFixed(2) * 100) + '%');
            } else {
                data_line.push(0);
            }
            data_line.push(info[i].train_qual_sum);
            if (total.sum != 0) {
                data_line.push(((info[i].imit_sum/info[i].train_qual_sum ).toFixed(2) * 100) + '%');
            } else {
                data_line.push(0);
            }
            data_line.push(info[i].imit_sum);
            if (info[i].imit_sum != 0) {
                data_line.push(((info[i].attend_sum/info[i].imit_sum ).toFixed(2) * 100) + '%');
            } else {
                data_line.push(0);
            }
            data_line.push(info[i].attend_sum)
            if (info[i].attend_sum != 0) {
                data_line.push(((info[i].adopt_sum/info[i].attend_sum ).toFixed(2) * 100) + '%');
            } else {
                data_line.push(0);
            }
            data_line.push(info[i].adopt_sum, info[i].adopt_sum);
            //list_data.push.apply(list_data, line);
            list_data.push(data_line);
        }
        list_data.push([]);

        list_data.push(["老师类型",'面试通过人数','培训参训率','培训参训新师人数','培训合格率','培训合格新师人数','模拟试听总排课率','模拟试听总排课人数','模拟试听总上课率','模拟试听总上课人数','模拟试听总通过率','模拟试听总通过人数','入职总人数']);
        // 总计
        var total = g_data.t_total;
        var data_line = ['总计', total.sum];
        if (total.sum != 0) {
            data_line.push(((total.train_tea_sum/total.sum ).toFixed(2) * 100) + '%');
        } else {
            data_line.push(0);
        }
        data_line.push(total.train_tea_sum);
        if (total.sum != 0) {
            data_line.push(((total.train_qual_sum/total.train_tea_sum ).toFixed(2) * 100) + '%');
        } else {
            data_line.push(0);
        }
        data_line.push(total.train_qual_sum);
        if (total.sum != 0) {
            data_line.push(((total.imit_sum/total.train_qual_sum ).toFixed(2) * 100) + '%');
        } else {
            data_line.push(0);
        }
        data_line.push(total.imit_sum);
        if (total.imit_sum != 0) {
            data_line.push(((total.attend_sum/total.imit_sum ).toFixed(2) * 100) + '%');
        } else {
            data_line.push(0);
        }
        data_line.push(total.attend_sum)
        if (total.attend_sum != 0) {
            data_line.push(((total.adopt_sum/total.attend_sum ).toFixed(2) * 100) + '%');
        } else {
            data_line.push(0);
        }
        data_line.push(total.adopt_sum, total.adopt_sum);
        
        list_data.push(data_line);

        var info = g_data.t_info;
        for(var i = 0; i < info.length; i ++) {
            var data_line = [];
            data_line.push(info[i].identity_str, info[i].sum);
            if (info[i].sum != 0) {
                data_line.push(((info[i].train_tea_sum/info[i].sum ).toFixed(2) * 100) + '%');
            } else {
                data_line.push(0);
            }
            data_line.push(info[i].train_tea_sum);
            if (total.sum != 0) {
                data_line.push(((info[i].train_qual_sum/info[i].train_tea_sum ).toFixed(2) * 100) + '%');
            } else {
                data_line.push(0);
            }
            data_line.push(info[i].train_qual_sum);
            if (total.sum != 0) {
                data_line.push(((info[i].imit_sum/info[i].train_qual_sum ).toFixed(2) * 100) + '%');
            } else {
                data_line.push(0);
            }
            data_line.push(info[i].imit_sum);
            if (info[i].imit_sum != 0) {
                data_line.push(((info[i].attend_sum/info[i].imit_sum ).toFixed(2) * 100) + '%');
            } else {
                data_line.push(0);
            }
            data_line.push(info[i].attend_sum)
            if (info[i].attend_sum != 0) {
                data_line.push(((info[i].adopt_sum/info[i].attend_sum ).toFixed(2) * 100) + '%');
            } else {
                data_line.push(0);
            }
            data_line.push(info[i].adopt_sum, info[i].adopt_sum);
            //list_data.push.apply(list_data, line);
            list_data.push(data_line);
        }

        $.do_ajax ( "/page_common/upload_xls_data",{
            xls_data :  JSON.stringify(list_data )
        },function(data){
            window.location.href= "/common_new/download_xls";
        });
    });

	$('.opt-change').set_input_change_event(load_data);
});

