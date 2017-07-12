

$(function(){
    function countly_log(key,count){
        if (!count ) {
            count=1;
        }
        $.get("http://countly.yb1v1.com/i", {
            app_key:"891f21003e1f2c904fd93a9f2fecbd90a6da1c52",
            device_id:"js",
            events: JSON.stringify( [
                {
                    timestamp : (new Date()).getTime() ,
                    key       : key,
                    count     : count
                }
            ])
        });
    }

    
    function setCookie(name,value)
    {
        var days = 300;
        var exp = new Date();
        exp.setTime(exp.getTime() + days*24*60*60*1000);
        document.cookie = name + "="+encodeURIComponent(value)+ ";expires=" + exp.toGMTString()+";path=/";
    }
    //读取cookies
    function getCookie(name)
    {
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
        arr=document.cookie.match(reg);
        if(arr)
            return (arr[2]);
        else
            return null;
    }

    var key="selfid";
     var selfid=getCookie(key);
    if (!selfid) {
        selfid = Math.round(Math.random()*1000000000);
        setCookie(key,selfid );
        selfid=getCookie(key);
    }



    //get from
    var origin= decodeURIComponent(window.location.search.substr(1));
    var check_tel= function(value){
        return /^1[3|4|5|8|9]\d{9}$/.test(value);
    };
    
    var posted_flag=false;

    $("#id_submit").on("click",function(){
        if (posted_flag) {
            alert("您已提交过了．请等待理优老师对您的回访");
            return  false;
            
        }
        var phone   = $("#id_phone").val();
        var grade   = $('input:radio[name=id_grade]:checked').val();
        var subject = $('input:radio[name=id_subject]:checked').val();
        var has_pad = $('input:radio[name=id_has_pad]:checked').val();
        if (!check_tel(phone)) {

            countly_log(origin+":提示-手机号格式不合法" );
            alert("手机号格式不合法");
            return false;
        }
        if (!( grade>0) ) {
            countly_log(origin+":提示-请选择您孩子的年级" );
            alert("请选择您孩子的年级");
            return false;
        }
        if (!( subject>0) ) {
            countly_log(origin+":提示-请选择科目" );
            alert("请选择科目");
            return false;
        }

        if ( has_pad== undefined  ) {
            alert("请选择Pad类型");
            countly_log(origin+":提示-请选择Pad类型" );
            return false;
        }

        $.ajax({
            url: 'http://admin.yb1v1.com/common_ex/book_free_lesson',
            dataType: 'JSONP',
            data : {
                add_to_main_flag: 1,
                phone   : phone,
                subject : subject,
                origin : origin,
                grade   : grade,
                has_pad : has_pad
            },
            success: function(datas){
                console.log(datas);
                if ( datas.ret == 0 ){
                    posted_flag=true;
                    alert("提交成功");
                    countly_log(origin+":提交成功" );
                } else {
                    if (datas.info) {
                        countly_log(origin+":datas.info" );
                        alert(datas.info);
                    } else{
                        countly_log(origin+":提交失败" );
                        alert("提交失败");
                    }
                }
            },
            error: function(){
            }
        });


        return false; 
    });
    countly_log(origin+":打开页面" );

});


