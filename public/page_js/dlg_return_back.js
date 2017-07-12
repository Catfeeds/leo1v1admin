$(function(){
  // 录入回访
  $(".opt-return-back").on("click",function(){
        var userid=$(this).parent().data("userid");
        BootstrapDialog.show({
            title: '回访录入',
            message  : dlg_need_html_by_id( "id_add_return_record_dlg") ,
            closable : false,
            buttons  : [{

                label: '查看全部',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    $.wopen("/stu_manage/return_record?sid="+userid);
                }
            },{

                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {

                var revisit_type   = $.trim(dlg_get_val_by_id("id_return_record_type") );
                var revisit_person = $.trim(dlg_get_val_by_id("id_return_record_person") );
                var operator_note  = $.trim(dlg_get_val_by_id("id_return_record_record"));
                    if (operator_note =="" ){
                        alert("还没有内容") ;
                    }
                  $.ajax({
                  type     :"post",
                  url      :"/revisit/add_revisit_record",
                  dataType :"json",
                  data     :{'userid':userid,
                                   'operator_note':operator_note,
                                   'revisit_person':revisit_person,
                                   'revisit_type':revisit_type
                                  },
                  success  : function(result){
                            if(result.ret != 0){
                                alert(result.info);
                            }else{
                        window.location.reload();
                            }
                  }
                });
                }
            }]
        });
    });

    //回访记录
    $(".opt-return-back-list").on("click",function(){
        var userid=$(this).parent().data("userid");
        var phone=$(this).parent().data("phone");

      $.ajax({
        type     :"post",
        url      :"/revisit/get_revisit_info",
        dataType :"json",
              size : BootstrapDialog.SIZE_WIDE,
        data     :{"userid":userid,phone:phone},
        success  : function(result){
          var html_str="<table class=\"table table-bordered table-striped\"  > ";
                  html_str+=" <tr><th> 时间  <th> 回访类型 <th> 负责人 <th>对象 <th>内容 </tr>   ";
          $.each( result.revisit_list ,function(i,item){
                      //console.log(item);
                      //return;
                      var revisit_person  ="";
                      if(item.revisit_person  ) {
                          revisit_person  = item.revisit_person;
                      }
            html_str=html_str+"<tr><td>"+item.revisit_time +"</td><td>"+item.revisit_type+"</td><td>"+ item.sys_operator +"</td><td>"+revisit_person+"</td><td>"+item.operator_note+" </td></tr>";
          } );



                  var dlg=BootstrapDialog.show({
                      title: '回访记录',
                      message :  html_str ,
                      closable: true,
                      buttons: [{
                          label: '查看全部',
                          cssClass: 'btn-warning',
                          action: function(dialog) {
                              $.wopen("/stu_manage/return_record?sid="+userid);
                          }
                      },{

                          label: '返回',
                          action: function(dialog) {
                              //dlg.setSize(BootstrapDialog.SIZE_WIDE);
                              dialog.close();
                          }
                      }]
                  });

                  if (!$.check_in_phone()) {
                      dlg.getModalDialog().css("width", "800px");
                  }

        }
      });

    });

});
