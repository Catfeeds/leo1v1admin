/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_join-index.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }
    $(".id_date").datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
        "onChangeDateTime" : function() {
        }

	});
    $("#id_phone").attr({readonly:"true"});
    $("#id_save").on("click",function(){
        var get_education_info=function($item) {
            var input_list=$item.find("input");
            var v=$(input_list[0]).val();
            var s=$(input_list[1]).val();
            var a=$(input_list[2]).val();
            var b=$(input_list[3]).val();
            var c=$(input_list[4]).val();
            var d=$(input_list[5]).val();
            var e=$(input_list[6]).val();

            return {"start_time":v,"end_time":s,"school":a,"major":b,"education":c,"degree":d,"nature":e
                };
        };
        var education_info=[];
        $(".education_info").each(function(i ) {
            education_info .push( get_education_info( $(this)));
        });
        var get_work_info=function($item) {
            var input_list=$item.find("input");
            var v=$(input_list[0]).val();
            var s=$(input_list[1]).val();
            var a=$(input_list[2]).val();
            var b=$(input_list[3]).val();
            var c=$(input_list[4]).val();
            var d=$(input_list[5]).val();
            var e=$(input_list[6]).val();
            var f=$(input_list[7]).val();

            return {"start_time":v,"end_time":s,"company":a,"post":b,"reason":c,"salary":d,"voucher":e,"voucher_phone":f
                };
        };
        var work_info=[];
        $(".work_info").each(function(i ) {
            work_info.push( get_work_info( $(this)));
        });
        var get_family_info=function($item) {
            var input_list=$item.find("input");
            var v=$(input_list[0]).val();
            var s=$(input_list[1]).val();
            var a=$(input_list[2]).val();
            var b=$(input_list[3]).val();
            var c=$(input_list[4]).val();
            return {"name":v,"relation":s,"company":a,"post":b,"phone":c
                };
        };
        var family_info=[];
        $(".family_info").each(function(i ) {
            family_info.push( get_family_info( $(this)));
        });
        $.ajax({
			type     :"post",
			url      :"/admin_join/update_info",
			dataType :"json",
			data     :{                   
                "name" : $("#id_name").val(),
                "phone" : $("#id_phone").val(),
                "education" : $("#id_education").val(),
                "residence" : $("#id_residence").val(),
                "gender" : $("#id_gender").val(),
                "birth" : $("#id_birth").val(),
                "english" : $("#id_english").val(),
                "polity" : $("#id_polity").val(),
                "carded" : $("#id_carded").val(),
                "marry" : $("#id_marry").val(),
                "child" : $("#id_child").val(),
                "email" : $("#id_email").val(),
                "post" : $("#id_post").val(),
                "dept" : $("#id_dept").val(),
                "address" : $("#id_address").val(),
                "ccb_card" : $("#id_ccb_card").val(),
                "strong" : $("#id_strong").val(),
                "interest" : $("#id_interest").val(),
                "non_compete" : $("#id_non_compete").val(),
                "is_labor" : $("#id_is_labor").val(),
                "is_fre" : $("#id_is_fre").val(),
                "fre_name" : $("#id_fre_name").val(),
                "minor" : $("#id_minor").val(),
                "height" : $("#id_height").val(),
                "birth_type" : $("#id_birth_type").val(),
                "gra_school" : $("#id_gra_school").val(),
                "gra_major" : $("#id_gra_major").val(),
                "health_condition" : $("#id_health_condition").val(),
                "postcodes" : $("#id_postcodes").val(),
                "residence_type" : $("#id_residence_type").val(),
                "is_insured" : $("#id_is_insured").val(),
                "join_time" : $("#id_join_time").val(),
                "emergency_contact_nick" : $("#id_emergency_contact_nick").val(),
                "emergency_contact_address" : $("#id_emergency_contact_address").val(),
                "emergency_contact_phone" : $("#id_emergency_contact_phone").val(),
                "native_place" : $("#id_native_place").val(),
                "education_info" :JSON.stringify( education_info),
                "work_info" : JSON.stringify(  work_info),
                "family_info" : JSON.stringify(  family_info)
            },

			success  : function(data){
                if(data.ret  != 0){
                    alert(data.info);
                }else{
                    alert("提交成功");
                    window.location.reload();
                }
            }
        });
    });


});

