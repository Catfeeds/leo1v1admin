/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-get_lesson_list_new.d.ts" />

$(function () {
	 $("#pre-listen-1").click(function(){
		$(".error-message1").replaceWith("<span class='error-message1'></span>");
		$("#pre-listen-2").attr("checked",false);
		$("#pre-listen-1").attr("checked",true);
        $("#area1").replaceWith("<div id='area1'></div>");
        $(".listen1").css('background-position', '0px 0px');
        $(".listen2").css('background-position', '-40px 0px');
        $("#area1 input").css('border','solid 2px #e6e6e6');
	});

	$("#pre-listen-2").click(function(){
		$("#pre-listen-2").attr("checked",true);
        $("#pre-listen-1").attr("checked",false);
		$(".error-message1").replaceWith("<span class='error-message1'></span>");
	    $("#area1").replaceWith("<div id='area1'><input rows='1' cols='20' type='text' id='text_area1' name='text_area1'  style='resize:none;' placeholder='请输入未能完成的原因'></div>");
	    $("#area1").val("");
	    $(".listen1").css('background-position', '-40px 0px');
	    $(".listen2").css('background-position', '0px 0px');
	    $("#area1 input").css('border','solid 2px rgb(11, 206, 255)');
	});

	$("#attitude-1").click(function() {
		$(".error-message2").replaceWith("<span class='error-message2'></span>");
		$("#attitude-1").attr("checked",true);
        $("#attitude-2").attr("checked",false);
        $("#attitude-3").attr("checked",false);
        $("#attitude-4").attr("checked",false);

		$(".attitude1").css('background-position', '0px 0px');
		$(".attitude2").css('background-position', '-40px 0px');
		$(".attitude3").css('background-position', '-40px 0px');
		$(".attitude4").css('background-position', '-40px 0px');
	});

	$("#attitude-2").click(function() {
		$(".error-message2").replaceWith("<span class='error-message2'></span>");
		$("#attitude-1").attr("checked",false);
        $("#attitude-2").attr("checked",true);
        $("#attitude-3").attr("checked",false);
        $("#attitude-4").attr("checked",false);

		$(".attitude1").css('background-position', '-40px 0px');
		$(".attitude2").css('background-position', '0px 0px');
		$(".attitude3").css('background-position', '-40px 0px');
		$(".attitude4").css('background-position', '-40px 0px');
	});

	$("#attitude-3").click(function() {
		$(".error-message2").replaceWith("<span class='error-message2'></span>");
		$("#attitude-1").attr("checked",false);
        $("#attitude-2").attr("checked",false);
        $("#attitude-3").attr("checked",true);
        $("#attitude-4").attr("checked",false);

		$(".attitude1").css('background-position', '-40px 0px');
		$(".attitude2").css('background-position', '-40px 0px');
		$(".attitude3").css('background-position', '0px 0px');
		$(".attitude4").css('background-position', '-40px 0px');
	});

	$("#attitude-4").click(function() {
		$(".error-message2").replaceWith("<span class='error-message2'></span>");
		$("#attitude-1").attr("checked",false);
        $("#attitude-2").attr("checked",false);
        $("#attitude-3").attr("checked",false);
        $("#attitude-4").attr("checked",true);

		$(".attitude1").css('background-position', '-40px 0px');
		$(".attitude2").css('background-position', '-40px 0px');
		$(".attitude3").css('background-position', '-40px 0px');
		$(".attitude4").css('background-position', '0px 0px');
	});
    
    $("#element-1").click(function() {
		$(".error-message3").replaceWith("<span class='error-message3'></span>");
		$("#element-1").attr("checked",true);
        $("#element-2").attr("checked",false);
        $("#element-3").attr("checked",false);
        $("#element-4").attr("checked",false);

		$(".element1").css('background-position', '0px 0px');
		$(".element2").css('background-position', '-40px 0px');
		$(".element3").css('background-position', '-40px 0px');
		$(".element4").css('background-position', '-40px 0px');
	});

	$("#element-2").click(function() {
		$(".error-message3").replaceWith("<span class='error-message3'></span>");
		$("#element-1").attr("checked",false);
        $("#element-2").attr("checked",true);
        $("#element-3").attr("checked",false);
        $("#element-4").attr("checked",false);

		$(".element1").css('background-position', '-40px 0px');
		$(".element2").css('background-position', '0px 0px');
		$(".element3").css('background-position', '-40px 0px');
		$(".element4").css('background-position', '-40px 0px');
	});

	$("#element-3").click(function() {
		$(".error-message3").replaceWith("<span class='error-message3'></span>");
		$("#element-1").attr("checked",false);
        $("#element-2").attr("checked",false);
        $("#element-3").attr("checked",true);
        $("#element-4").attr("checked",false);

		$(".element1").css('background-position', '-40px 0px');
		$(".element2").css('background-position', '-40px 0px');
		$(".element3").css('background-position', '0px 0px');
		$(".element4").css('background-position', '-40px 0px');
	});

	$("#element-4").click(function() {
		$(".error-message3").replaceWith("<span class='error-message3'></span>");
		$("#element-1").attr("checked",false);
        $("#element-2").attr("checked",false);
        $("#element-3").attr("checked",false);
        $("#element-4").attr("checked",true);

		$(".element1").css('background-position', '-40px 0px');
		$(".element2").css('background-position', '-40px 0px');
		$(".element3").css('background-position', '-40px 0px');
		$(".element4").css('background-position', '0px 0px');
	});
    
    $("#advantage-1").click(function() {
    	var data =$(this).prop("checked");
    	if (data == true) {
            $(".error-message4").replaceWith("<span class='error-message4'></span>");
		    $("#advantage-1").prop('checked', true);

		    $(".advantage1").css('background-position', '0px 0px');
    	}else {
            $("#advantage-1").prop('checked', false);
            $(".advantage1").css('background-position', '-40px 0px');
    	}
	});

	$("#advantage-2").click(function() {
		var data =$(this).prop("checked");
    	if (data == true) {
            $(".error-message4").replaceWith("<span class='error-message4'></span>");
		    $("#advantage-2").prop('checked', true);

		    $(".advantage2").css('background-position', '0px 0px');
    	}else {
            $("#advantage-2").prop('checked', false);
            $(".advantage2").css('background-position', '-40px 0px');
    	}
	});

	$("#advantage-3").click(function() {
		var data =$(this).prop("checked");
    	if (data == true) {
            $(".error-message4").replaceWith("<span class='error-message4'></span>");
		    $("#advantage-3").prop('checked', true);

		    $(".advantage3").css('background-position', '0px 0px');
    	}else {
            $("#advantage-3").prop('checked', false);
            $(".advantage3").css('background-position', '-40px 0px');
    	}
	});

	$("#advantage-4").click(function() {
		var data =$(this).prop("checked");
    	if (data == true) {
            $(".error-message4").replaceWith("<span class='error-message4'></span>");
		    $("#advantage-4").prop('checked', true);

		    $(".advantage4").css('background-position', '0px 0px');
    	}else {
            $("#advantage-4").prop("checked",false);
            $(".advantage4").css('background-position', '-40px 0px');
    	}
	});
    
    $("#advantage-5").click(function() {
		var data =$(this).prop("checked");
    	if (data == true) {
            $(".error-message4").replaceWith("<span class='error-message4'></span>");
            $("#area2").replaceWith("<div id='area2'><input rows='1' cols='20' type='text' id='text_area2' name='text_area2' style='resize:none;' placeholder='请输入其他优点'></div>");
	        $("#area2").val("");

		    $("#advantage-5").prop('checked', true);
            
		    $(".advantage5").css('background-position', '0px 0px');
		    $("#area2 input").css('border','solid 2px rgb(11, 206, 255)');
    	}else {
    		$("#area2").replaceWith("<div id='area2'></div>");
            $("#advantage-5").prop('checked', false);
            $(".advantage5").css('background-position', '-40px 0px');
            $("#area2 input").css('border','solid 2px #e6e6e6');
    	}
	});

    $("#disadvantage-1").click(function() {
    	var data =$(this).prop("checked");
    	if (data == true) {
            $(".error-message5").replaceWith("<span class='error-message5'></span>");
		    $("#disadvantage-1").prop('checked', true);

		    $(".disadvantage1").css('background-position', '0px 0px');
    	}else {
            $("#disadvantage-1").prop('checked', false);
            $(".disadvantage1").css('background-position', '-40px 0px');
    	}
	});

	$("#disadvantage-2").click(function() {
		var data =$(this).prop("checked");
    	if (data == true) {
            $(".error-message5").replaceWith("<span class='error-message5'></span>");
		    $("#disadvantage-2").prop('checked', true);

		    $(".disadvantage2").css('background-position', '0px 0px');
    	}else {
            $("#disadvantage-2").prop('checked', false);
            $(".disadvantage2").css('background-position', '-40px 0px');
    	}
	});

	$("#disadvantage-3").click(function() {
		var data =$(this).prop("checked");
    	if (data == true) {
            $(".error-message5").replaceWith("<span class='error-message5'></span>");
		    $("#disadvantage-3").prop('checked', true);

		    $(".disadvantage3").css('background-position', '0px 0px');
    	}else {
            $("#disadvantage-3").prop('checked', false);
            $(".disadvantage3").css('background-position', '-40px 0px');
    	}
	});

	$("#disadvantage-4").click(function() {
		var data =$(this).prop("checked");
    	if (data == true) {
            $(".error-message5").replaceWith("<span class='error-message5'></span>");
		    $("#disadvantage-4").prop('checked', true);

		    $(".disadvantage4").css('background-position', '0px 0px');
    	}else {
            $("#disadvantage-4").prop("checked",false);
            $(".disadvantage4").css('background-position', '-40px 0px');
    	}
	});
    
    $("#disadvantage-5").click(function() {
		var data =$(this).prop("checked");
    	if (data == true) {
            $(".error-message5").replaceWith("<span class='error-message5'></span>");
            $("#area3").replaceWith("<div id='area3'><input rows='1' cols='20' type='text' id='text_area3' name='text_area3' style='resize:none;' placeholder='请输入未能完成的原因'></div>");
	        $("#area3").val("");

		    $("#disadvantage-5").prop('checked', true);
            
		    $(".disadvantage5").css('background-position', '0px 0px');
		    $("#area3 input").css('border','solid 2px rgb(11, 206, 255)');
    	}else {
    		$("#area3").replaceWith("<div id='area3'></div>");
            $("#disadvantage-5").prop('checked', false);
            $(".disadvantage5").css('background-position', '-40px 0px');
            $("#area3 input").css('border','solid 2px #e6e6e6');
    	}
	});

	$("#plan-1").click(function() {

		$(".error-message6").replaceWith("<span class='error-message6'></span>");
		$(".error-message7").replaceWith("<span class='error-message7'></span>");
		$(".error-message8").replaceWith("<span class='error-message8'></span>");
		$(".error-message9").replaceWith("<span class='error-message9'></span>");
		$("#plan-1").attr("checked",true);
        $("#plan-2").attr("checked",false);
        $("#plan-3").attr("checked",false);
        $("#plan-4").attr("checked",false);

		$(".plan1").css('background-position', '0px 0px');
		$(".plan2").css('background-position', '-40px 0px');
		$(".plan3").css('background-position', '-40px 0px');
		$(".plan4").css('background-position', '-40px 0px');

		$(".select1").css('background',"url('/images/tea_comment/icon_arr_blue.png') no-repeat scroll right center transparent");
		$(".select1").css('background-size','20px 14px');
		$(".select1").css('background-color','rgba(245,245,245,100)');
		$(".select1").css('border','solid 2px rgb(11, 206, 255)');

		$(".select2").css('background',"url('/images/tea_comment/icon_arr_blue.png') no-repeat scroll right center transparent");
		$(".select2").css('background-size','20px 14px');
		$(".select2").css('background-color','rgba(245,245,245,100)');
		$(".select2").css('border','solid 2px rgb(11, 206, 255)');
        
		$(".select3").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select3").css('background-size','20px 14px');
		$(".select3").css('background-color','rgba(245,245,245,100)');
		$(".select3").css('border','solid 2px #ccc');

		$(".select4").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select4").css('background-size','20px 14px');
		$(".select4").css('background-color','rgba(245,245,245,100)');
		$(".select4").css('border','solid 2px #ccc');

		$(".select5").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select5").css('background-size','20px 14px');
		$(".select5").css('background-color','rgba(245,245,245,100)');
		$(".select5").css('border','solid 2px #e6e6e6');

		$(".select6").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select6").css('background-size','20px 14px');
		$(".select6").css('background-color','rgba(245,245,245,100)');
		$(".select6").css('border','solid 2px #e6e6e6');
        
        $(".select1").attr('disabled', false);
        $(".select2").attr('disabled', false);
        $(".select3").attr('disabled', true);
        $(".select4").attr('disabled', true);
        $(".select5").attr('disabled', true);
        $(".select6").attr('disabled', true);

		$("#area4 input").css('border','none');
	});

	$("#plan-2").click(function() {
		$(".error-message6").replaceWith("<span class='error-message6'></span>");
		$(".error-message7").replaceWith("<span class='error-message7'></span>");
		$(".error-message8").replaceWith("<span class='error-message8'></span>");
		$(".error-message9").replaceWith("<span class='error-message9'></span>");
		$("#plan-1").attr("checked",false);
        $("#plan-2").attr("checked",true);
        $("#plan-3").attr("checked",false);
        $("#plan-4").attr("checked",false);

		$(".plan1").css('background-position', '-40px 0px');
		$(".plan2").css('background-position', '0px 0px');
		$(".plan3").css('background-position', '-40px 0px');
		$(".plan4").css('background-position', '-40px 0px');

		$(".select1").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select1").css('background-size','20px 14px');
		$(".select1").css('background-color','rgba(245,245,245,100)');
		$(".select1").css('border','solid 2px #ccc');

		$(".select2").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select2").css('background-size','20px 14px');
		$(".select2").css('background-color','rgba(245,245,245,100)');
		$(".select2").css('border','solid 2px #ccc');

		$(".select3").css('background',"url('/images/tea_comment/icon_arr_blue.png') no-repeat scroll right center transparent");
		$(".select3").css('background-size','20px 14px');
		$(".select3").css('background-color','rgba(245,245,245,100)');
		$(".select3").css('border','solid 2px rgb(11, 206, 255)');

		$(".select4").css('background',"url('/images/tea_comment/icon_arr_blue.png') no-repeat scroll right center transparent");
		$(".select4").css('background-size','20px 14px');
		$(".select4").css('background-color','rgba(245,245,245,100)');
		$(".select4").css('border','solid 2px rgb(11, 206, 255)');

		$(".select5").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select5").css('background-size','20px 14px');
		$(".select5").css('background-color','rgba(245,245,245,100)');
		$(".select5").css('border','solid 2px #ccc');

		$(".select6").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select6").css('background-size','20px 14px');
		$(".select6").css('background-color','rgba(245,245,245,100)');
		$(".select6").css('border','solid 2px #ccc');
        
        $(".select1").attr('disabled', true);
        $(".select2").attr('disabled', true);
        $(".select3").attr('disabled', false);
        $(".select4").attr('disabled', false);
        $(".select5").attr('disabled', true);
        $(".select6").attr('disabled', true);

		$("#area4 input").css('border','none');

	});

	$("#plan-3").click(function() {
		$(".error-message6").replaceWith("<span class='error-message6'></span>");
		$(".error-message7").replaceWith("<span class='error-message7'></span>");
		$(".error-message8").replaceWith("<span class='error-message8'></span>");
		$(".error-message9").replaceWith("<span class='error-message9'></span>");
		$("#plan-1").attr("checked",false);
        $("#plan-2").attr("checked",false);
        $("#plan-3").attr("checked",true);
        $("#plan-4").attr("checked",false);

		$(".plan1").css('background-position', '-40px 0px');
		$(".plan2").css('background-position', '-40px 0px');
		$(".plan3").css('background-position', '0px 0px');
		$(".plan4").css('background-position', '-40px 0px');

		$(".select1").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select1").css('background-size','20px 14px');
		$(".select1").css('background-color','rgba(245,245,245,100)');
		$(".select1").css('border','solid 2px #ccc');

		$(".select2").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select2").css('background-size','20px 14px');
		$(".select2").css('background-color','rgba(245,245,245,100)');
		$(".select2").css('border','solid 2px #ccc');

		$(".select3").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select3").css('background-size','20px 14px');
		$(".select3").css('background-color','rgba(245,245,245,100)');
		$(".select3").css('border','solid 2px #ccc');

		$(".select4").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select4").css('background-size','20px 14px');
		$(".select4").css('background-color','rgba(245,245,245,100)');
		$(".select4").css('border','solid 2px #ccc');

		$(".select5").css('background',"url('/images/tea_comment/icon_arr_blue.png') no-repeat scroll right center transparent");
		$(".select5").css('background-size','20px 14px');
		$(".select5").css('background-color','rgba(245,245,245,100)');
		$(".select5").css('border','solid 2px rgb(11, 206, 255)');

		$(".select6").css('background',"url('/images/tea_comment/icon_arr_blue.png') no-repeat scroll right center transparent");
		$(".select6").css('background-size','20px 14px');
		$(".select6").css('background-color','rgba(245,245,245,100)');
		$(".select6").css('border','solid 2px rgb(11, 206, 255)');
        
        $(".select1").attr('disabled', true);
        $(".select2").attr('disabled', true);
        $(".select3").attr('disabled', true);
        $(".select4").attr('disabled', true);
        $(".select5").attr('disabled', false);
        $(".select6").attr('disabled', false);

		$("#area4 input").css('border','none');
	});
    
    $("#plan-4").click(function() {
		$(".error-message6").replaceWith("<span class='error-message6'></span>");
		$(".error-message7").replaceWith("<span class='error-message7'></span>");
		$(".error-message8").replaceWith("<span class='error-message8'></span>");
		$(".error-message9").replaceWith("<span class='error-message9'></span>");
		$("#plan-1").attr("checked",false);
        $("#plan-2").attr("checked",false);
        $("#plan-3").attr("checked",false);
        $("#plan-4").attr("checked",true);

		$(".plan1").css('background-position', '-40px 0px');
		$(".plan2").css('background-position', '-40px 0px');
		$(".plan3").css('background-position', '-40px 0px');
		$(".plan4").css('background-position', '0px 0px');

		$(".select1").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select1").css('background-size','20px 14px');
		$(".select1").css('background-color','rgba(245,245,245,100)');
		$(".select1").css('border','solid 2px #ccc');

		$(".select2").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select2").css('background-size','20px 14px');
		$(".select2").css('background-color','rgba(245,245,245,100)');
		$(".select2").css('border','solid 2px #ccc');

		$(".select3").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select3").css('background-size','20px 14px');
		$(".select3").css('background-color','rgba(245,245,245,100)');
		$(".select3").css('border','solid 2px #ccc');

		$(".select4").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select4").css('background-size','20px 14px');
		$(".select4").css('background-color','rgba(245,245,245,100)');
		$(".select4").css('border','solid 2px #ccc');

		$(".select5").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select5").css('background-size','20px 14px');
		$(".select5").css('background-color','rgba(245,245,245,100)');
		$(".select5").css('border','solid 2px #ccc');

		$(".select6").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select6").css('background-size','20px 14px');
		$(".select6").css('background-color','rgba(245,245,245,100)');
		$(".select6").css('border','solid 2px #ccc');
        

        $(".select1").attr('disabled', true);
        $(".select2").attr('disabled', true);
        $(".select3").attr('disabled', true);
        $(".select4").attr('disabled', true);
        $(".select5").attr('disabled', true);
        $(".select6").attr('disabled', true);

		$("#area4 input").css('border','solid 2px rgb(11, 206, 255)');
	});
    
    $("#teach-1").click(function() {

		$(".error-message10").replaceWith("<span class='error-message10'></span>");
		$(".error-message11").replaceWith("<span class='error-message11'></span>");
		$("#teach-1").attr("checked",true);
        $("#teach-2").attr("checked",false);
        
		$(".teach1").css('background-position', '0px 0px');
		$(".teach2").css('background-position', '-40px 0px');

		$(".select7").css('background',"url('/images/tea_comment/icon_arr_blue.png') no-repeat scroll right center transparent");
		$(".select7").css('background-size','20px 14px');
		$(".select7").css('background-color','rgba(245,245,245,100)');
		$(".select7").css('border','solid 2px rgb(11, 206, 255)');

		$(".select8").css('background',"url('/images/tea_comment/icon_arr_blue.png') no-repeat scroll right center transparent");
		$(".select8").css('background-size','20px 14px');
		$(".select8").css('background-color','rgba(245,245,245,100)');
		$(".select8").css('border','solid 2px rgb(11, 206, 255)');
        
        $(".select7").attr('disabled', false);
        $(".select8").attr('disabled', false);

		$("#area5 input").css('border','none');
	});

	$("#teach-2").click(function() {
		$(".error-message10").replaceWith("<span class='error-message10'></span>");
		$(".error-message11").replaceWith("<span class='error-message11'></span>");
		$("#teach-1").attr("checked",false);
        $("#teach-2").attr("checked",true);
        
		$(".teach1").css('background-position', '-40px 0px');
		$(".teach2").css('background-position', '0px 0px');

		$(".select7").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select7").css('background-size','20px 14px');
		$(".select7").css('background-color','rgba(245,245,245,100)');
		$(".select7").css('border','solid 2px #ccc');

		$(".select8").css('background',"url('/images/tea_comment/icon_arr_gray.png') no-repeat scroll right center transparent");
		$(".select8").css('background-size','20px 14px');
		$(".select8").css('background-color','rgba(245,245,245,100)');
		$(".select8").css('border','solid 2px #ccc');

        $(".select7").attr('disabled', true);
        $(".select8").attr('disabled', true);
		$("#area5 input").css('border','solid 2px rgb(11, 206, 255)');
	});
    
	$("#btn-submit").click(function(){
        var str1 = load_data1();
        if (str1.length == 0) {
    	    $(".error-message1").replaceWith("<span class='error-message1'style='height:20px;color:red;font-size:12px;'>试听情况必填!</span>");
        }else {
    	    $(".error-message1").replaceWith("<span class='error-message1'></span>");
        }

        var str2 = load_data2();
        
        if (str2.length == 0) {
    	    $(".error-message2").replaceWith("<span class='error-message2'style='height:20px;color:red;font-size:12px;'>学习态度必选!</span>");
        }else {
            $(".error-message2").replaceWith("<span class='error-message2'></span>");
        }

        var str3 = load_data3();
        if (str3.length == 0) {
    	    $(".error-message3").replaceWith("<span class='error-message3'style='height:20px;color:red;font-size:12px;'>学习基础情况必选!</span>");
        }else {
            $(".error-message3").replaceWith("<span class='error-message3'></span>");
        }
        
        var str4 = load_data4();
        if (str4.length == 0) {
    	    $(".error-message4").replaceWith("<span class='error-message4'style='height:20px;color:red;font-size:12px;'>学生优点必选!</span>");
        }else {
    	    if (str4 == "其他未选") {

    	    }else {
    		    $(".error-message4").replaceWith("<span class='error-message4'></span>");
    	    }
        }

        var str5 = load_data5();
        if (str5.length == 0) {
    	    $(".error-message5").replaceWith("<span class='error-message5'style='height:20px;color:red;font-size:12px;'>学生有待提高必选!</span>");
        }else {
    	    if (str5 == "其他未选") {
      		    
    	    }else {
    		    $(".error-message5").replaceWith("<span class='error-message5'></span>");
    	    }
        }
        
        var str6 = load_data6();
        if (str6.length == 0) {
            $(".error-message9").replaceWith("<span class='error-message9'style='height:20px;color:red;font-size:12px;'>学生培训计划必选!</span>");
        }else {
            if(str6 == "教材版本") {
                
            }else {
         	    $(".error-message9").replaceWith("<span class='error-message9'></span>");
            }
        }

        var str7 = load_data7();
        if (str7.length == 0) {
            $(".error-message11").replaceWith("<span class='error-message11'style='height:20px;color:red;font-size:12px;'>学生教学方向必选!</span>");
        }else {
            if(str7 == "教学方向") {
                
            }else {
         	    $(".error-message11").replaceWith("<span class='error-message11'></span>");
            }
        }
        
        var str8 = load_data8();
        if (str8.length == 0) {
            
        }else {
    	    $(".error-message12").replaceWith("<span class='error-message12'></span>");
        }

        if (str1.length > 0 && str2.length > 0 && str3.length > 0 && str4.length > 0 && str5.length > 0 && str6.length > 0 && str7.length > 0 && str8.length > 0) {
            $.ajax({
		        url: '/tea_manage/set_stu_performance_for_seller',
		        type:'POST',
		        dataType: 'json',
		        data: {
			        "lessonid"               : geturl("lessonid"),
			        "stu_lesson_content"     : str1,
                    "stu_lesson_status"      : str2,
                    "stu_study_status"       : str3,
                    "stu_advantages"         : str4,
                    "stu_disadvantages"      : str5,
                    "stu_lesson_plan"        : str6,
                    "stu_teaching_direction" : str7,
                    "stu_advice"             : str8
		        },success: function(data) {
                    if(data.ret==0){
                        alert("评价成功");
                        window.close();
                    }else if(data.ret==-1){
                        alert(data.info);
                    }
                }
	        });
        }
    });

    function geturl(name) {
	    var reg = new RegExp("(^|\\?|&)" + name + "=([^&]*)(\\s|&|$)", "i");
	    if (reg.test(location.href)) return unescape(RegExp.$2.replace(/\+/g, " "));
	    return "";
	};
	
    function load_data1(){
        var str = "";
        var data =$("input[name='radio-listen'][checked]").val();
	    if (data == "顺利完成") {
		    $(".error-message1").replaceWith("<span class='error-message1'></span>");
		    str = "顺利完成。";
		    return str;
	    }else if(data == "未顺利完成")  {
            var textarea1 = $("#text_area1").val();
	        if(textarea1.length <= 0) {
		        $(".error-message1").replaceWith("<span class='error-message1' style='height:20px;color:red;font-size:12px;'>请输入未完成试听原因!</span>");
		        return "";
	        }
	        $(".error-message1").replaceWith("<span class='error-message1'></span>");
	        //str += "试听情况:未顺利完成。";
	        //str += "原因:";
	        str += textarea1;
	        return str;
	    }else {
	        $(".error-message1").replaceWith("<span class='error-message1'style='height:20px;color:red;font-size:12px;'>试听情况必填!</span>");
	        return "";
	    }
    }

    function load_data2() {
	    var str = "";
        var data =$("input[name='radio-attitude'][checked]").val();
        if (data == "积极配合，兴趣浓厚") {
            str += "积极配合，兴趣浓厚。";
        }else if(data == "较好配合，互动较多") {
            str += "较好配合，互动较多。";
        }else if(data == "配合度一般，但愿意回答问题") {
            str += "配合度一般，但愿意回答问题。";
        }else if(data == "不太愿意配合"){
            str += "不太愿意配合。";
        }
        return str;
    }

    function load_data3() {
	    var str = "";
        var data =$("input[name='radio-element'][checked]").val();
        if (data == "较好，紧跟老师节奏，完美消化所学") {
            str += "较好，紧跟老师节奏，完美消化所学。";
        }else if(data == "中等，但可以较好吸收当堂所学") {
            str += "中等，但可以较好吸收当堂所学。";
        }else if(data == "一般，部分内容需要再学习") {
            str += "一般，部分内容需要再学习。";
        }else if(data == "较差，试听内容基本听不懂"){
            str += "较差，试听内容基本听不懂。";
        }
        return str;
    }

    function load_data4() {
	    var str = "";
        var data1 =$("#advantage-1").prop("checked");
        if (data1 == true) {
            str += "理解能力强";
        }
        var data2 =$("#advantage-2").prop("checked");
        if (data2 == true) {
    	    if (str.length > 0) {
    		    str += ",";
    	    }
            str += "表达能力强";
        }

        var data3 =$("#advantage-3").prop("checked");
        if (data3 == true) {
    	    if (str.length > 0) {
    		    str += ",";
    	    }
            str += "思路清晰";
        }
        var data4 =$("#advantage-4").prop("checked");
        if (data4 == true) {
    	    if (str.length > 0) {
    		    str += ",";
    	    }
            str += "自信十足";
        }
        var data5 =$("#advantage-5").prop("checked");
        if (data5 == true) {
    	    var textarea2 = $("#text_area2").val();
    	    if (textarea2.length == 0) {
    		    $(".error-message4").replaceWith("<span class='error-message4' style='height:20px;color:red;font-size:12px;'>请输入学生其他优点!</span>");
		        return "其他未选";
    	    }
    	    if (str.length > 0) {
    		    str += ",";
    	    }
 		    str += textarea2;
        }

        return str;
    }

    function load_data5() {
	    var str = "";
        var data1 =$("#disadvantage-1").prop("checked");
        if (data1 == true) {
            str += "思维能力的培养";
        }
        var data2 =$("#disadvantage-2").prop("checked");
        if (data2 == true) {
    	    if (str.length > 0) {
    		    str += ",";
    	    }
            str += "知识系统化学习";
        }

        var data3 =$("#disadvantage-3").prop("checked");
        if (data3 == true) {
    	    if (str.length > 0) {
    		    str += ",";
    	    }
            str += "语言表达能力的提高";
        }
        var data4 =$("#disadvantage-4").prop("checked");
        if (data4 == true) {
    	    if (str.length > 0) {
    		    str += ",";
    	    }
            str += "举一反三的能力";
        }
        var data5 =$("#disadvantage-5").prop("checked");
        if (data5 == true) {
    	    var textarea3 = $("#text_area3").val();
    	    if (textarea3.length == 0) {
    		    $(".error-message5").replaceWith("<span class='error-message5' style='height:20px;color:red;font-size:12px;'>请输入学生其他有待提高的地方!</span>");
		        return "其他未选";
    	    }
    	    if (str.length > 0) {
    		    str += ",";
    	    }
 		    str += textarea3;
        }

        return str;
    }

    function load_data6() {
	    var str = "";
	    var data =$("input[name='radio-tranplan'][checked]").val();
        if (data == "从基础内容学习") {
    	    var grade = $(".select1").find("option:selected").text();
            var book =  $(".select2").find("option:selected").text();

            str += "从基础内容学习," + grade + "年级" + book + "册";

        }else if(data == "系统性巩固") {
            var grade = $(".select3").find("option:selected").text();
            var book =  $(".select4").find("option:selected").text();

            str += "系统性巩固," + grade + "年级" + book + "册";
        }else if(data == "提高学习") {
            var grade = $(".select5").find("option:selected").text();
            var book =  $(".select6").find("option:selected").text();

            str += "提高学习," + grade + "年级" + book + "册";
        }else if(data == "其他") {
            var textarea4 = $("#text_area4").val();
    	    if (textarea4.length == 0) {
    		    $(".error-message9").replaceWith("<span class='error-message9' style='height:20px;color:red;font-size:12px;'>请输入学生教材版本!</span>");
		        return "教材版本";
    	    }
    	    str += "其他," + textarea4;
        }else {

        }
        return str;
    }

    function load_data7() {
	    var str = "";
	    var data =$("input[name='radio-teach'][checked]").val();
	    if (data == "课内知识") {
            var grade = $(".select7").find("option:selected").text();
            var book =  $(".select8").find("option:selected").text();

            str += "课内知识," + grade + "年级" + book + "册";
	    }else if(data == "课外知识") {
            var textarea5 = $("#text_area5").val();
    	    if (textarea5.length == 0) {
    		    $(".error-message11").replaceWith("<span class='error-message11' style='height:20px;color:red;font-size:12px;'>请输入学生教学方向!</span>");
		        return "教学方向";
    	    }
    	    str += "课外知识,"+ textarea5;
	    }else {

	    }
	    return str;
    }

    function load_data8() {
	    var str = "";
	    var textarea6 = $("#text_area6").val();
	    if (textarea6.length >= 50) {
		    str += textarea6;
	    }else if(textarea6.length < 50) {
		    $(".error-message12").replaceWith("<span class='error-message12' style='height:20px;color:red;font-size:12px;'>请输入对学生意见和建议,至少50字!</span>");
	        return "";
	    }
	    return str;
    }

});
