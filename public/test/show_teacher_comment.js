;$(function () {

    function geturl(name) {
	    var reg = new RegExp("(^|\\?|&)" + name + "=([^&]*)(\\s|&|$)", "i");
	    if (reg.test(location.href)) return unescape(RegExp.$2.replace(/\+/g, " "));
	    return "";
	};

    $.ajax({
        url:'http://api.yb1v1.com/tea_lesson/get_stu_performance_from_seller',
        type:'POST',
        dataType:'jsonp',
        data:{
            "lessonid":geturl("lessonid")
        },success:function(result){
            $(".stu_lesson_content").append("TEST 老师课呢阿道夫发达水电费，　卡多少分打发 老师课呢阿道夫发达水电费，　卡多少分打发 老师课呢阿道夫发达水电费，　卡多少分打发");
            $(".stu_lesson_status").append("asdfa ");
            $(".stu_study_status").append("xxx");
            $(".stu_advantages").append("asadfa ");
            $(".stu_disadvantages").append("sadfa ddd ");
            $(".stu_teaching_direction").append("dhhhadf ");
            $(".stu_lesson_plan").append("hmltadf ");
            $(".stu_advice").append("老师课呢阿道夫发达水电费，　卡多少分打发");
        }
    });

});
