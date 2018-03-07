jQuery.fn.extend({


    table_head_static:  function(height ) {
        if($.check_in_phone() ) {
            return;
        }

        var p_div=$('<div  style="  overflow: auto;  margin: 20px 10px; "><div>');
        height= height? height: 500;
        p_div.css({
            "height" : ""+ height +"px"
        });

        var $table=$(this);
        $table.before(p_div);
        p_div.append($table);

        $table.find("thead td").css ( {
            "background-color":  "rgb(236, 240, 245)",
        });


        p_div.on('scroll',function(e){
            var item=e.target;
            var scrollTop = item.scrollTop;
            console.log(e.target);
            $(item).find('thead').css( {
                "transform" : 'translateY(' + scrollTop + 'px)',
            });
        })
    },
    table_admin_level_5_init:function(show_flag) {

        var $table=$(this);
        if (!show_flag) {
            $.each($table.find(".l-2,.l-3,.l-4,.l-5"),function(){
                $(this).hide();
            });
        }
        var link_css=        {
            color: "#3c8dbc",
            cursor:"pointer"
        };

        $table.find(".l-1 .main_type").css(link_css);
        $table.find(".l-2 .first_group_name").css(link_css);
        $table.find(".l-3 .up_group_name").css(link_css);
        $table.find(".l-4 .group_name").css(link_css);
        var switch_show_flag=function($item, self_class,  parent_class  ,  level_count ) {
            var show_flag= $item.data("show");
            if (!show_flag) {
                show_flag=0;
            }
            if (!level_count) {
                level_count=4;
            }
            show_flag= (show_flag+1)% level_count;
            $item.data("show" ,show_flag) ;

            var class_name= $item.data("class_name");

            var $opt_item=null;
            var select_class= "."+self_class+"."+class_name;
            if (show_flag ==1 ) {
                $opt_item=$table.find( select_class).parent("." +parent_class  );
                $opt_item.show();
            } else  if (show_flag ==2 ) {
                $opt_item=$table.find(select_class),
                $opt_item.parent().show();
            } else  if (show_flag ==3 ) {
                $opt_item=$table.find(select_class),
                $opt_item.parent().show();
            }else{
                $table.find(select_class).parent().hide();
            }
            $item.parent().show();
            return show_flag;
        };


        $table.find(".l-1 .main_type").on("click",function(){
            switch_show_flag($(this), "first_group_name","l-2");
        });

        $table.find(".l-2 .first_group_name").on("click",function(){
            switch_show_flag($(this), "up_group_name","l-3");
        });


        $table.find(".l-3 .up_group_name").on("click",function(){
            switch_show_flag($(this), "group_name","l-4");
        });

        $table.find(".l-4 .group_name").on("click",function(){
            switch_show_flag($(this), "account","l-5",2);
        });
    },



    table_admin_level_4_init:function(show_flag) {

        var $table=$(this);
        if (!show_flag) {
            $.each($table.find(".l-2,.l-3,.l-4"),function(){
                $(this).hide();
            });
        }
        var link_css=        {
            color: "#3c8dbc",
            cursor:"pointer"
        };

        $table.find(".l-1 .main_type").css(link_css);
        $table.find(".l-2 .up_group_name").css(link_css);
        $table.find(".l-3 .group_name").css(link_css);
        var switch_show_flag=function($item, self_class,  parent_class  ,  level_count ) {
            var show_flag= $item.data("show");
            if (!show_flag) {
                show_flag=0;
            }
            if (!level_count) {
                level_count=3;
            }
            show_flag= (show_flag+1)% level_count;
            $item.data("show" ,show_flag) ;

            var class_name= $item.data("class_name");

            var $opt_item=null;
            var select_class= "."+self_class+"."+class_name;
            if (show_flag ==1 ) {
                $opt_item=$table.find( select_class).parent("." +parent_class  );
                $opt_item.show();
            } else  if (show_flag ==2 ) {
                $opt_item=$table.find(select_class),
                $opt_item.parent().show();
            }else{
                $table.find(select_class).parent().hide();
            }
            $item.parent().show();
            return show_flag;
        };


        $table.find(".l-1 .main_type").on("click",function(){
            switch_show_flag($(this), "up_group_name","l-2");
        });

        $table.find(".l-2 .up_group_name").on("click",function(){
            switch_show_flag($(this), "group_name","l-3");
        });

        $table.find(".l-3 .group_name").on("click",function(){
            switch_show_flag($(this), "account","l-4",2);
        });
    },

    table_admin_level_3_init:function(show_flag) {

        var $table=$(this);
        if (!show_flag) {
            $.each($table.find(".l-2,.l-3"),function(){
                $(this).hide();
            });
        }
        var link_css=        {
            color: "#3c8dbc",
            cursor:"pointer"
        };
        var link_css_yellow= {
            color: "#EAC100",
            cursor:"pointer"
        };
        var link_css_gray= {
            color: "#9D9D9D",
            cursor:"pointer"
        };



        $table.find(".l-1 .main_type").css(link_css);
        $table.find(".l-2 .up_group_name").css(link_css_yellow);
        $table.find(".l-3 .group_name").css(link_css_gray);
        var switch_show_flag=function($item, self_class,  parent_class  ,  level_count ) {
            var show_flag= $item.data("show");
            if (!show_flag) {
                show_flag=0;
            }
            if (!level_count) {
                level_count=3;
            }
            show_flag= (show_flag+1)% level_count;
            $item.data("show" ,show_flag) ;

            var class_name= $item.data("class_name");

            var $opt_item=null;
            var select_class= "."+self_class+"."+class_name;
            if (show_flag ==1 ) {
                $opt_item=$table.find( select_class).parent("." +parent_class  );
                $opt_item.show();
            } else  if (show_flag ==2 ) {
                $opt_item=$table.find(select_class),
                $opt_item.parent().show();
            }else{
                $table.find(select_class).parent().hide();
            }
            $item.parent().show();
            return show_flag;
        };


        $table.find(".l-1 .main_type").on("click",function(){
            switch_show_flag($(this), "up_group_name","l-2");
        });

        $table.find(".l-2 .up_group_name").on("click",function(){
            switch_show_flag($(this), "group_name","l-3");
        });

    },

    table_group_level_5_init:function( show_flag) {
        var $table=$(this);

        if (!show_flag) {
            $.each($table.find(".l-2,.l-3,.l-4,.l-5"),function(){
                $(this).hide();
            });
        }

        var link_css=    {
            color: "#3c8dbc",
            cursor:"pointer"
        };

        $table.find(".l-1 .key0").css(link_css);
        $table.find(".l-2 .key1").css(link_css);
        $table.find(".l-3 .key2").css(link_css);
        $table.find(".l-4 .key3").css(link_css);

        $table.find(".l-1 .key0").on("click",function(){
            var $this=$(this);
            var class_name= $this.data("class_name");
            if ($this.data("show") ==true) {
                $table.find(".key1."+class_name ).parent().hide();
            }else{
                var $opt_item=$table.find(".key1."+class_name ).parent(".l-2");
                $opt_item.show();
            }
            $this.parent().show();
            $this.data("show", !$this.data("show") );

        });

        $table.find(".l-2 .key1").on("click",function(){
            var $this=$(this);
            var class_name= $this.data("class_name");
            if ($this.data("show") ==true) {
                $table.find(".key2."+class_name ).parent().hide();
            }else{
                var $opt_item=$table.find(".key2."+class_name ).parent(".l-3");
                $opt_item.show();
            }
            $this.parent().show();
            $this.data("show", !$this.data("show") );
        });

        $table.find(".l-3 .key2").on("click",function(){
            var $this=$(this);
            var class_name= $this.data("class_name");
            if ($this.data("show") ==true) {
                $table.find(".key3."+class_name ).parent().hide();
            }else{
                var $opt_item=$table.find(".key3."+class_name ).parent(".l-4");
                $opt_item.show();
            }
            $this.parent().show();
            $this.data("show", !$this.data("show") );
        });

        $table.find(".l-4 .key3").on("click",function(){
            var $this=$(this);
            var class_name= $this.data("class_name");
            if ($this.data("show") ==true) {
                $table.find(".key4."+class_name ).parent().hide();
            }else{
                var $opt_item=$table.find(".key4."+class_name ).parent(".l-5");
                $opt_item.show();
            }
            $this.parent().show();
            $this.data("show", !$this.data("show") );
        });

    },

    table_group_level_4_init:function( show_flag) {
        var $table=$(this);

        if (!show_flag) {
            $.each($table.find(".l-2,.l-3,.l-4"),function(){
                $(this).hide();
            });
        }

        var link_css=    {
            color: "#3c8dbc",
            cursor:"pointer"
        };

        $table.find(".l-1 .key1").css(link_css);
        $table.find(".l-2 .key2").css(link_css);
        $table.find(".l-3 .key3").css(link_css);

        $table.find(".l-1 .key1").on("click",function(){
            var $this=$(this);
            var class_name= $this.data("class_name");
            if ($this.data("show") ==true) {
                $table.find(".key2."+class_name ).parent().hide();
            }else{
                var $opt_item=$table.find(".key2."+class_name ).parent(".l-2");
                $opt_item.show();
            }
            $this.parent().show();
            $this.data("show", !$this.data("show") );

        });

        $table.find(".l-2 .key2").on("click",function(){
            var $this=$(this);
            var class_name= $this.data("class_name");
            if ($this.data("show") ==true) {
                $table.find(".key3."+class_name ).parent().hide();
            }else{
                var $opt_item=$table.find(".key3."+class_name ).parent(".l-3");
                $opt_item.show();
            }
            $this.parent().show();
            $this.data("show", !$this.data("show") );
        });

        $table.find(".l-3 .key3").on("click",function(){
            var $this=$(this);
            var class_name= $this.data("class_name");
            if ($this.data("show") ==true) {
                $table.find(".key4."+class_name ).parent().hide();
            }else{
                var $opt_item=$table.find(".key4."+class_name ).parent(".l-4");
                $opt_item.show();
            }
            $this.parent().show();
            $this.data("show", !$this.data("show") );
        });
    },

    table_group_level_more_init:function(level, show_flag) {
        var $table=$(this);

        if (!show_flag) {
            for(var a=2; a<level; a++){
                $.each($table.find(".l-"+a),function(){
                    $(this).hide();
                });
            }
        }

        var link_css=    {
            color: "#3c8dbc",
            cursor:"pointer"
        };

        for(var i=1; i<level-1; i++){
            $table.find(".l-"+i+" .key"+i).css(link_css);
        }

        for(var b=1; b<(level-1); b++){

            $table.find(".l-"+b+" .key"+b).on("click",function(){
                var $this=$(this);
                var class_name= $this.data("class_name");
                var num = $this.data('index');
                var c = 1+num;
                var find_str = ".key"+c+"."+class_name;
                var parent_str = ".l-"+c;
                if ($this.data("show") ==true) {
                    $table.find( find_str ).parent().hide();
                }else{
                    var $opt_item=$table.find( find_str ).parent( parent_str );
                    $opt_item.show();
                }
                $this.parent().show();
                $this.data("show", !$this.data("show") );

            });
        }

    },


    tbody_scroll_table:function( height  )  {
            $(this).table_head_static();
    },

    key_value_table_show:function(  show_flag ) {
        if (show_flag===false) {
            this.parent().parent().hide();
        }else{
            this.parent().parent().show();
        }
    },
    set_input_readonly :function ( readonly_flag ){
        if (readonly_flag ) {
            this.attr("disabled","disabled");
            this.attr("readonly","readonly");

            this.css("background-color", "#eee" );
        }else{
            this.arr("disabled","");
            this.attr("readonly","");
            this.css("background-color", "#fff" );
        }

    },


    insertAtCaret: function(myValue){
        return this.each(function(i) {
            if (document.selection) {
                //For browsers like Internet Explorer
                this.focus();
                var sel = document.selection.createRange();
                sel.text = myValue;
                this.focus();
            }
            else if (this.selectionStart || this.selectionStart == '0') {
                //For browsers like Firefox and Webkit based
                var startPos = this.selectionStart;
                var endPos = this.selectionEnd;
                var scrollTop = this.scrollTop;
                this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
                this.focus();
                this.selectionStart = startPos + myValue.length;
                this.selectionEnd = startPos + myValue.length;
                this.scrollTop = scrollTop;
            } else {
                this.value += myValue;
                this.focus();
            }
        });
    },

    append_dollar: function(){
        var  getCursorPosition=function( oTxt ){
            var cursurPosition=-1;
            if(oTxt.selectionStart){//非IE浏览器
                cursurPosition= oTxt.selectionStart;
            }else{//IE
                var range = document.selection.createRange();
                range.moveStart("character",-oTxt.value.length);
                cursurPosition=range.text.length;
            }
            return cursurPosition;
        };
        var setCursorPosition=function(elem, index) {
            var val = elem.value;
            var len = val.length;

            // 超过文本长度直接返回
            if (len < index) return;
            setTimeout(function() {
                elem.focus();
                if (elem.setSelectionRange) { // 标准浏览器
                    elem.setSelectionRange(index, index)   ;
                } else { // IE9-
                    var range = elem.createTextRange();
                    range.moveStart("character", -len);
                    range.moveEnd("character", -len);
                    range.moveStart("character", index);
                    range.moveEnd("character", 0);
                    range.select();
                }
            }, 10);
        };

        return this.each(function(i) {

            if (document.selection) {
                //For browsers like Internet Explorer
                this.focus();
                var sel = document.selection.createRange();
                if (sel.text.length >0 ){
                    var txt=sel.text
                            . replace(/\$/g, "" )
                            . replace(/\n/g, "$\n$" )
                    ;

                    sel.text = "$"+ txt+"$" ;
                    setCursorPosition(this,getCursorPosition(this )+2 );
                }

                this.focus();
            }
            else if (this.selectionStart || this.selectionStart == '0') {
                //For browsers like Firefox and Webkit based
                var startPos = this.selectionStart;
                var endPos = this.selectionEnd;
                var scrollTop = this.scrollTop;
                var txt2=this.value.substring(startPos,endPos) . replace(/\$/g, "" )
                        . replace(/\n/g, "$\n$" )
                ;

                var myValue=  "$"+txt2+"$";
                if (myValue!="$$" ){
                    var pre_str=this.value.substring(0, startPos)+myValue;
                    this.value = pre_str +this.value.substring(endPos,this.value.length);
                }

                this.focus();
                this.selectionStart = startPos + myValue.length;
                this.selectionEnd = startPos + myValue.length;
                this.scrollTop = scrollTop;
            } else {

            }

        });
    },

    get_opt_data: function( field){
        if (field) {
            return $(this).parent().data(field);
        }else{
            return $(this).parent().data();
        }
    },

    get_self_opt_data: function( field){
        return $(this).data(field);
    },

    get_row_opt_data:function( field) {
        return $(this).closest("tr").find("td:last>div").data();
    },


    select_get_text: function( ){
        return $(this).find("option:selected").text();
    },

    set_input_change_event :function ( func ) {

        $(this).on('keypress',function(e){
            if(e.keyCode== 13){
                func();
            }
        });
        $(this).on('change',function(e){
            func();
        });
    },

    iCheckValue:function() {
        return $(this).prop('checked')?1:0;
    },

  init_origin_ex: function( onChange) {

    $(this).on("click", function () {
      var $key0 = $("<select/>");
      var $key1 = $("<select/>");
      var $key2 = $("<select/>");
      var $key3 = $("<select/>");
      var $key4 = $("<select/>");
      var me = $(this);
      var key_list = me.val();

      var clean_select = function ($select) {
        $select.html("<option value=\"\">[全部]</option>");
      };

      key_list = key_list.split(",");
      //处理key
      $.do_ajax("/user_deal/origin_init_key_list", {
        "key0": key_list[0],
        "key1": key_list[1],
        "key2": key_list[2],
        "key3": key_list[3]
      }, function (ret) {
        clean_select($key0);
        clean_select($key1);
        clean_select($key2);
        clean_select($key3);
        clean_select($key4);

        $.each(ret.key0_list, function () {
          var v = this.k;
          $key0.append("<option value=\"" + v + "\">" + v + "</option>");
        });
        $.each(ret.key1_list, function () {
          var v = this.k;
          $key1.append("<option value=\"" + v + "\">" + v + "</option>");
        });
        $.each(ret.key2_list, function () {
          var v = this.k;
          $key2.append("<option value=\"" + v + "\">" + v + "</option>");
        });

        $.each(ret.key3_list, function () {
          var v = this.k;
          $key3.append("<option value=\"" + v + "\">" + v + "</option>");
        });

        $.each(ret.key4_list, function () {
          var v = this.k;
          $key4.append("<option value=\"" + v + "\">" + v + "</option>");
        });
        $key0.val(key_list[0]);
        $key1.val(key_list[1]);
        $key2.val(key_list[2]);
        $key3.val(key_list[3]);
        $key4.val(key_list[4]);
      });



      var set_select = function ($select, key1, key2, key3,key0) {
        $.do_ajax("/user_deal/origin_get_key_list", {
          "key0": key0,
          "key1": key1,
          "key2": key2,
          "key3": key3
        }, function (ret) {
          var sel_v = $select.val();
          $select.html("");
          $select.append("<option value=\"\">[全部]</option>");
          $.each(ret.list, function () {
            var v = this.k;
            $select.append("<option value=\"" + v + "\">" + v + "</option>");
          });
        });

      };

      $key0.on("change", function () {
        clean_select($key1);
        clean_select($key2);
        clean_select($key3);
        clean_select($key4);
        if ($key0.val()) {
          set_select($key1, "", "", "",$key0.val());
        }
      });

      $key1.on("change", function () {
        clean_select($key2);
        clean_select($key3);
        clean_select($key4);
        if ($key1.val()) {
          set_select($key2, $key1.val(), "", "",$key0.val());
        }
      });

      $key2.on("change", function () {
        clean_select($key3);
        clean_select($key4);
        if ($key2.val()) {
          set_select($key3, $key1.val(), $key2.val(), "",$key0.val());
        }
      });
      $key3.on("change", function () {
        clean_select($key4);
        if ($key3.val()) {
          set_select($key4, $key1.val(), $key2.val(), $key3.val(),$key0.val());
        }
      });


      var arr = [
        ["零级", $key0],
        ["一级", $key1],
        ["二级", $key2],
        ["三级", $key3],
        ["四级", $key4],
      ];

      $.show_key_value_table("渠道选择", arr, {
        label: '确认',
        cssClass: 'btn-warning',
        action: function (dialog) {
          var arr = [];
          arr.push($key0.val());
          arr.push($key1.val());
          arr.push($key2.val());
          arr.push($key3.val());
          arr.push($key4.val());
          me.val(arr.join(","));
          dialog.close();
          if (onChange) {
            onChange();
          }
        }
      });


    });
  },
    init_seller_groupid_ex: function(g_adminid_right, onChange) {
        $(this).on("click", function () {
            var $main_type_name = $("<select/>");
            var $main_group_name = $("<select/>");
            var $group_name = $("<select/>");
            var $account = $("<select/>");
            var me = $(this);
            var key_list = me.val();
            $main_type_name.html("<option value=\"\" >[全部]</option><option value=\"助教\" >助教</option><option value=\"销售\" selected >销售</option><option value=\"教务\" >教务</option><option value=\"全职老师\" >全职老师</option>")
            var clean_select = function ($select) {
                $select.html("<option value=\"\">[全部]</option>");
            };

            key_list = key_list.split(",");
            if(g_adminid_right != "" && g_adminid_right != null){
                key_list = g_adminid_right;
                console.log(g_adminid_right);
                if(key_list[0]=="全职老师"){
                    $main_type_name.html("<option value=\"\" >[全部]</option><option value=\"助教\" >助教</option><option value=\"销售\"  >销售</option><option value=\"教务\" >教务</option><option value=\"全职老师\" selected >全职老师</option>")
                    var clean_select = function ($select) {
                        $select.html("<option value=\"\">[全部]</option>");
                    };

                }else if(key_list[0]=="助教"){
                    $main_type_name.html("<option value=\"\" >[全部]</option><option value=\"助教\" selected >助教</option><option value=\"销售\"  >销售</option><option value=\"教务\" >教务</option><option value=\"全职老师\"  >全职老师</option>")
                    var clean_select = function ($select) {
                        $select.html("<option value=\"\">[全部]</option>");
                    };

                }else if(key_list[0]=="教务"){
                    $main_type_name.html("<option value=\"\" >[全部]</option><option value=\"助教\"  >助教</option><option value=\"销售\"  >销售</option><option value=\"教务\" selected >教务</option><option value=\"全职老师\"  >全职老师</option>")
                    var clean_select = function ($select) {
                        $select.html("<option value=\"\">[全部]</option>");
                    };

                }
            }
            //处理key
            $.do_ajax("/user_deal/seller_init_group_info", {
                "main_type_name": key_list[0],
                "main_group_name": key_list[1],
                "group_name": key_list[2]
            }, function (ret) {
                clean_select($main_group_name);
                clean_select($group_name);
                clean_select($account);


                $.each(ret.key2_list, function () {
                    var groupid = this.groupid;
                    var group_name = this.group_name;
                    $main_group_name.append("<option value=\"" + group_name + "\">" + group_name + "</option>");
                });

                $.each(ret.key3_list, function () {
                    var groupid = this.groupid;
                    var group_name = this.group_name;
                    $group_name.append("<option value=\"" + group_name + "\">" + group_name + "</option>");
                });

                $.each(ret.key4_list, function () {
                    var adminid = this.adminid;
                    var account = this.account;
                    $account.append("<option value=\"" + account + "\">" + account + "</option>");
                });
                //$main_type.val(key_list[0]);
                $main_group_name.val(key_list[1]);
                $group_name.val(key_list[2]);
                $account.val(key_list[3]);
                if(key_list[1] == "" || key_list[1] == null){
                    set_select($main_group_name, $main_type_name.val(), "", "");
                }
                //set_select($main_groupid, $main_type.val(), "", "");

            });



            var set_select = function ($select, main_type_name, main_group_name, group_name) {
                $.do_ajax("/user_deal/seller_get_group_info", {
                    "main_type_name": main_type_name,
                    "main_group_name": main_group_name,
                    "group_name": group_name
                }, function (ret) {
                    var sel_v = $select.val();
                    $select.html("");
                    $select.append("<option value=\"\">[全部]</option>");
                    if(group_name){
                        $.each(ret.list, function () {
                            var adminid = this.adminid;
                            var account = this.account;
                            $select.append("<option value=\"" + account + "\">" + account + "</option>");
                        });
                    }else{
                        if(main_group_name){
                            $.each(ret.list, function () {
                                var groupid = this.groupid;
                                var group_name = this.group_name;
                                $select.append("<option value=\"" + group_name + "\">" + group_name + "</option>");
                            });
                        }else{
                            if(main_type_name){
                                $.each(ret.list, function () {
                                    var groupid = this.groupid;
                                    var group_name = this.group_name;
                                    $select.append("<option value=\"" + group_name + "\">" + group_name + "</option>");
                                });
                            }
                        }
                    }

                });

            };


            $main_type_name.on("change", function () {
                clean_select($main_group_name);
                clean_select($group_name);
                clean_select($account);
                if ($main_type_name.val()) {
                    set_select($main_group_name, $main_type_name.val(), "", "");
                }
            });

            $main_group_name.on("change", function () {
                clean_select($group_name);
                clean_select($account);
                if ($main_group_name.val()) {
                    set_select($group_name, $main_type_name.val(), $main_group_name.val(), "");
                }
            });
            $group_name.on("change", function () {
                clean_select($account);
                if ($group_name.val()) {
                    set_select($account, $main_type_name.val(), $main_group_name.val(), $group_name.val());
                }
            });


            var arr = [
                ["分类", $main_type_name],
                ["主管", $main_group_name],
                ["小组", $group_name],
                ["成员", $account],
            ];

            $.show_key_value_table("渠道选择", arr, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function (dialog) {
                    var arr = [];
                    arr.push($main_type_name.val());
                    arr.push($main_group_name.val());
                    arr.push($group_name.val());
                    arr.push($account.val());
                    me.val(arr.join(","));
                    me.change();
                    if (onChange) {
                        onChange();
                    }
                    dialog.close();
                }
            },function(){

            });


        });

    }




});

jQuery.extend({
    custom_upload_file_process :function (btn_id,  is_public_bucket , complete_func, ctminfo , ext_file_list, bucket_info  ,noti_origin_file_func   ){
        var html_node=$('        <div class="row">'+
                        '            <div class="progress">'+
                        '                <div class="progress-bar" role="progressbar" aria-valuenow="60" '+
                        '                     aria-valuemin="0" aria-valuemax="100" style="width: 0%;">'+
                        '                    <span class="sr-only">40% 完成</span>'+
                        '                </div>'+
                        '            </div>'+
                        '        </div>');
        var dlg=null;

        $.custom_upload_file( btn_id,  is_public_bucket , complete_func, ctminfo , ext_file_list,
                              function(percentage){
                                  if (percentage==101) { //succ
                                      alert("上传完成");
                                      dlg.close();
                                  }
                                  html_node.find(".progress-bar") .css('width', percentage+'%');
                              },function(){ //before_upload
                                  dlg=BootstrapDialog.show({
                                      title: "上传进度",
                                      message: html_node
                                  });
                              }, bucket_info ,noti_origin_file_func  );

    },
    custom_upload_file_process_soft :function (btn_id,  is_public_bucket , complete_func, ctminfo , ext_file_list, bucket_info  ,noti_origin_file_func   ){

        var html_node=$('        <div class="row">'+
                        '            <div class="progress">'+
                        '                <div class="progress-bar" role="progressbar" aria-valuenow="60" '+
                        '                     aria-valuemin="0" aria-valuemax="100" style="width: 0%;">'+
                        '                    <span class="sr-only">40% 完成</span>'+
                        '                </div>'+
                        '            </div>'+
                        '        </div>');
        var dlg=null;
        var bucket_info = null;
        $.do_ajax("/common/get_bucket_info",{
            is_public : 0
        },function(ret){
            bucket_info = ret;
        });
        $.custom_upload_file_soft_new( btn_id,  is_public_bucket , complete_func, ctminfo , ext_file_list,
                              function(percentage){
                                  if (percentage==101) { //succ
                                      alert("上传完成");
                                      dlg.close();
                                  }
                                  html_node.find(".progress-bar") .css('width', percentage+'%');
                              },function(){ //before_upload
                                  dlg=BootstrapDialog.show({
                                      title: "上传进度",
                                      message: html_node
                                  });
                              }, bucket_info ,function(file_name) {
                }  );

    },


    custom_upload_file :function (btn_id,  is_public_bucket , complete_func, ctminfo , ext_file_list, noti_process , before_upload, bucket_info,noti_origin_file_func ){
        var init_upload=function( ret ) {
            var domain_name=ret.domain;
            var token=ret.token;
            var uploader = Qiniu.uploader({
                runtimes        : 'html5, flash, html4',
                browse_button   : btn_id, //choose files id
                uptoken         : token,
                domain          : "http://"+domain_name,
                max_file_size   : '30mb',
                dragdrop        : true,
                flash_swf_url   : '/js/qiniu/plupload/Moxie.swf',
                chunk_size      : '4mb',
                unique_names    : false,
                save_key        : false,
                auto_start      : true,
                multi_selection : false,
                filters         : {
                    mime_types: [
                        {title: "", extensions: ext_file_list.join(",") }
                    ]
                },
                init : {
                    'FilesAdded': function(up, files) {
                        plupload.each(files, function(file) {
                            console.log('waiting...'+file.name );
                        });
                    },
                    'BeforeUpload': function(up, file) {
                        console.log('before uplaod the file 11111');
                        var match = file.name.match(/.*\.(.*)?/);
                        var file_ext=match[1];
                        var check_flag=false;
                        $.each ( ext_file_list,  function(i,item) {
                            if ( item.toLowerCase() ==file_ext.toLowerCase()) {
                                check_flag=true;
                            }
                        });
                        if (!check_flag  ) {
                            BootstrapDialog.alert("文件后缀必须是: "+ ext_file_list.join(",") +"<br> 刷新页面，重新上传"  );
                            return false;
                        }
                        console.log('before uplaod the file');
                        if(before_upload) {
                            before_upload();
                        }
                        return true;
                    },
                    'UploadProgress': function(up,file) {
                        if(noti_process) {
                            noti_process (file.percent);
                        }
                        console.log(file.percent);
                        console.log('upload progress');
                    },
                    'UploadComplete': function() {
                        console.log(' UploadComplete .. end ');
                    },
                    'FileUploaded' : function(up, file, info) {
                        if(noti_process) {
                            noti_process (101);
                        }
                        console.log('Things below are from FileUploaded');

                        if (noti_origin_file_func) {
                            noti_origin_file_func(this.origin_file_name);
                        }

                        complete_func(up, info, file, ctminfo);
                    },
                    'Error': function(up, err, errTip) {
                        console.log('Things below are from Error');
                        BootstrapDialog.alert(errTip);
                    },
                    'Key': function(up, file) {
                        var key = "";
                        var time = (new Date()).valueOf();
                        var match = file.name.match(/.*\.(.*)?/);
                        /*
                          if( uploader.on_noti_origin_file_func) {
                          uploader.on_noti_origin_file_func(file.name);
                          }
                        */
                        this.origin_file_name=file.name;
                        var file_name=$.md5(file.name) +time +'.' + match[1];
                        console.log('gen file_name:'+file_name);
                        return file_name;
                    }
                }
            });
        };
        if (bucket_info) {
            init_upload(bucket_info);
        }else{
            do_ajax( "/common/get_bucket_info",{
                is_public: is_public_bucket ? 1:0
            },function(ret){
                init_upload(ret);
            });
        }
    },
    custom_upload_file_soft_new :function (btn_id,  is_public_bucket , complete_func, ctminfo , ext_file_list, noti_process , before_upload, bucket_info,noti_origin_file_func ){
        var init_upload=function( ret ) {
            var domain_name=ret.domain;
            var token=ret.token;
            var uploader = Qiniu.uploader({
                runtimes: 'html5, flash, html4',
                browse_button: btn_id , //choose files id
                uptoken: token,
                domain: "http://"+domain_name,
                max_file_size: '100mb',
                dragdrop: true,
                flash_swf_url: '/js/qiniu/plupload/Moxie.swf',
                chunk_size: '4mb',
                unique_names: false,
                save_key: false,
                auto_start: true,
                multi_selection: false,
                filters: {
                    mime_types: [
                        {title: "", extensions: ext_file_list.join(",") }
                    ]
                },
                init: {
                    'FilesAdded': function(up, files) {
                        plupload.each(files, function(file) {
                            console.log('waiting...'+file.name );
                        });
                    },
                    'BeforeUpload': function(up, file) {
                        console.log('before uplaod the file 11111');
                        var match = file.name.match(/.*\.(.*)?/);
                        var file_ext=match[1];
                        var check_flag=false;
                        $.each ( ext_file_list,  function(i,item) {
                            if ( item.toLowerCase() ==file_ext.toLowerCase()) {
                                check_flag=true;
                            }
                        });
                        if (!check_flag  ) {
                            BootstrapDialog.alert("文件后缀必须是: "+ ext_file_list.join(",") +"<br> 刷新页面，重新上传"  );
                            return false;
                        }
                        console.log('before uplaod the file');
                        if(before_upload) {
                            before_upload();
                        }
                        return true;

                    },
                    'UploadProgress': function(up,file) {
                        if(noti_process) {
                            noti_process (file.percent);
                        }
                        console.log(file.percent);
                        console.log('upload progress');
                    },
                    'UploadComplete': function() {
                        console.log(' UploadComplete .. end ');
                    },
                    'FileUploaded' : function(up, file, info) {
                        /*if(noti_process) {
                            noti_process (101);
                        }
                        console.log('Things below are from FileUploaded');

                        if (noti_origin_file_func) {
                            noti_origin_file_func(this.origin_file_name);
                        }

                        complete_func(up, info, file, ctminfo);
                        */
                        if(noti_process) {
                            noti_process (101);
                        }
                        console.log('Things below are from FileUploaded');
                        console.log(1111);
                        if(info.response){
                            complete_func(up, info.response, file, ctminfo);
                        }else{
                            complete_func(up, info, file, ctminfo);
                        }
                    },
                    'Error': function(up, err, errTip) {
                        console.log('Things below are from Error');
                        BootstrapDialog.alert(errTip);
                    },
                    'Key': function(up, file) {
                        /*
                        var key = "";
                        var time = (new Date()).valueOf();
                        var match = file.name.match(/.*\.(.*)?/);
                        
                          if( uploader.on_noti_origin_file_func) {
                          uploader.on_noti_origin_file_func(file.name);
                          }
                        
                        this.origin_file_name=file.name;
                        var file_name=$.md5(file.name) +time +'.' + match[1];
                        console.log('gen file_name:'+file_name);
                        return file_name;
                        */
                        var file_type = 1;
                        var key = "";
                        var time = (new Date()).valueOf();
                        var match = file.name.match(/.*\.(.*)?/);

                        // var file_name=$.md5(file.name) +time +'.' + match[1];
                        if(file_type == 1){
                            var new_file_name = "student/"+match[0];
                        }
                        console.log('gen file_name:'+new_file_name);
                        return new_file_name;

                    }
                }
            });
        };
        if (bucket_info) {
            init_upload(bucket_info);
        }else{
            do_ajax( "/common/get_bucket_info",{
                is_public: is_public_bucket ? 1:0
            },function(ret){
                init_upload(ret);
            });
        }
    },

    self_upload_process:function(id,url,ctminfo,ext_file_list,ex_args,complete_func ) {
        var html_node=$('        <div class="row">'+
                        '            <div class="progress">'+
                        '                <div class="progress-bar" role="progressbar" aria-valuenow="60" '+
                        '                     aria-valuemin="0" aria-valuemax="100" style="width: 0%;">'+
                        '                    <span class="sr-only">40% 完成</span>'+
                        '                </div>'+
                        '            </div>'+
                        '        </div>');
        var dlg=null;

        $.self_upload( id,  url, ctminfo, ext_file_list,ex_args,
                       function(percentage){
                           if (percentage==101) { //succ
                               alert("上传完成");
                               dlg.close();
                           }
                           html_node.find(".progress-bar") .css('width', percentage+'%');
                       },complete_func ,function(){ //before_upload
                           dlg=BootstrapDialog.show({
                               title: "上传进度",
                               message: html_node
                           });
                       });
    },
    self_upload :function (id,url,ctminfo,ext_file_list,ex_args,process_func,complete_func, before_upload  ){
        var uploader = new plupload.Uploader({
            browse_button : id, //触发文件选择对话框的按钮，为那个元素id
            url : url, //服务器端的上传页面地址
            flash_swf_url : '/js/qiniu/plupload/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
            silverlight_xap_url : '/js/qiniu/plupload/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
            filters: {
                mime_types : [ //只允许上传图片和zip文件
                    {title : "", extensions: ext_file_list.join(",") }
                ],
                max_file_size : '40m',
                prevent_duplicates : true //不允许选取重复文件
            },
            multipart_params : ex_args
        });

        uploader.init({
            'BeforeUpload': function(up, file) {
                if(before_upload) {
                    before_upload();
                }
                return true;

            }
        });
        uploader.bind('FilesAdded',
                      function(up, files) {
                          uploader.start();
                      });
        uploader.bind('BeforeUpload',
                      function() {
                          if(before_upload){
                              before_upload();
                          }
                      });

        uploader.bind(
            'FileUploaded',
            function( uploader,file,responseObject) {
                if(process_func) {
                    process_func(101);
                }

                console.log('Things below are from FileUploaded');

                if (complete_func) {
                    complete_func(JSON.parse( responseObject.response),ctminfo );
                    console.log(ctminfo);
                }
            });

        uploader.bind(
            'UploadProgress',
            function(up,file) {
                if(process_func) {
                    process_func(file.percent);
                }
                console.log(file.percent);
                console.log('upload progress');
            });
    },

    filed_init_date_range:function(date_type,  opt_date_type, start_time,  end_time) {
        $('#id_date_type').val(date_type);
        $('#id_opt_date_type').val(opt_date_type); //时段
        $('#id_start_time').val($.DateFormat(start_time , "yyyy-MM-dd" ));
        $('#id_end_time').val( $.DateFormat(end_time, "yyyy-MM-dd" ));
    },

    filed_init_date_range_query_str:function(date_type,  opt_date_type, start_time,  end_time) {
        return "date_type="+date_type +"&opt_date_type=" + opt_date_type + "&start_time="+ $.DateFormat(start_time , "yyyy-MM-dd" ) + "&end_time=" +$.DateFormat(end_time, "yyyy-MM-dd" );
    },


    teacher_custom_show_pdf: function (file_url) {

        $.ajax({
            url: "/teacher_info/get_pdf_download_url",
            type: 'GET',
            dataType: 'json',
            data: {'file_url': file_url},
            success: function(ret) {
                if (ret.ret != 0) {
                    BootstrapDialog.alert(ret.info);

                } else {
                    var match = file_url.match(/.*\.(.*)?/);
                    if (match[1].toLowerCase() != "pdf" ||  $.check_in_phone() )  {

                        // BootstrapDialog.alert("不是pdf:" + ret.file_ex  );
                        if ($.check_in_wx()) {
                            BootstrapDialog.alert("复制,在其他浏览器打开<br/>  "+ret.file_ex);

                        }else{
                            window.open(ret.file_ex, '_self');
                        }


                        return;
                    }
                    window.open('/pdf_viewer/?file='+ret.file, '_blank');
                }
            }
        });
    },

    custom_show_pdf: function (file_url, get_abs_url) {
        if (!get_abs_url) {
            get_abs_url= "/tea_manage/get_pdf_download_url";
        }

        $.ajax({
            url: get_abs_url,
            type: 'GET',
            dataType: 'json',
            data: {'file_url': file_url},
            success: function(ret) {
                if (ret.ret != 0) {
                    BootstrapDialog.alert(ret.info);
                } else {
                    var match = file_url.match(/.*\.(.*)?/);
                    if (match[1].toLowerCase() != "pdf" ||  $.check_in_phone() )  {
                        var file = "";
                        if(ret.file_ex!="" && ret.file_ex!=undefined){
                            file = ret.file_ex;
                        }else{
                            file = ret.file;
                        }

                        if ($.check_in_wx()) {
                            BootstrapDialog.alert("复制,在其他浏览器打开<br/>  "+file);
                        }else{
                            window.open(file, '_self');
                        }
                        return;
                    }
                    window.open('/pdf_viewer/?file='+ret.file, '_blank');
                }
            }
        });
    },

    custom_upload_file :function (btn_id,  is_public_bucket , complete_func, ctminfo , ext_file_list, noti_process ){
        do_ajax( "/common/get_bucket_info",{
            is_public: is_public_bucket ? 1:0
        },function(ret){
            var domain_name=ret.domain;
            var token=ret.token;

            var uploader = Qiniu.uploader({
                runtimes: 'html5, flash, html4',
                browse_button: btn_id , //choose files id
                uptoken: token,
                domain: "http://"+domain_name,
                max_file_size: '30mb',
                dragdrop: true,
                flash_swf_url: '/js/qiniu/plupload/Moxie.swf',
                chunk_size: '4mb',
                unique_names: false,
                save_key: false,
                auto_start: true,
                multi_selection: false,
                filters: {
                    mime_types: [
                        {title: "", extensions: ext_file_list.join(",") }
                    ]
                },
                init: {
                    'FilesAdded': function(up, files) {
                        plupload.each(files, function(file) {
                            console.log('waiting...'+file.name );
                        });
                    },
                    'BeforeUpload': function(up, file) {
                        console.log('before uplaod the file 11111');
                        var match = file.name.match(/.*\.(.*)?/);
                        var file_ext=match[1];
                        var check_flag=false;
                        $.each ( ext_file_list,  function(i,item) {
                            if ( item.toLowerCase() ==file_ext.toLowerCase()) {
                                check_flag=true;
                            }
                        });

                        var multipart_params=uploader.getOption("multipart_params");
                        multipart_params.token=token; 
                        uploader.setOption("multipart_params", multipart_params );
                        
                        if (!check_flag  ) {
                            BootstrapDialog.alert("文件后缀必须是: "+ ext_file_list.join(",") +"<br> 刷新页面，重新上传"  );
                            return false;
                        }
                        console.log('before uplaod the file');
                        return true;

                    },
                    'UploadProgress': function(up,file) {
                        if(noti_process) {
                            noti_process (file.percent);
                        }
                        console.log(file.percent);
                        console.log('upload progress');
                    },
                    'UploadComplete': function() {
                        console.log(' UploadComplete .. end ');
                    },
                    'FileUploaded' : function(up, file, info) {
                        if(noti_process) {
                            noti_process (0);
                        }
                        console.log('Things below are from FileUploaded');
                        console.log(1111);
                        if(info.response){
                            complete_func(up, info.response, file, ctminfo);
                        }else{
                            complete_func(up, info, file, ctminfo);
                        }

                    },
                    'Error': function(up, err, errTip) {
                        console.log('Things below are from Error');
                        BootstrapDialog.alert(errTip);
                    },
                    'Key': function(up, file) {
                        var key = "";
                        var time = (new Date()).valueOf();
                        var match = file.name.match(/.*\.(.*)?/);
                        var file_name=$.md5(file.name) +time +'.' + match[1];
                        console.log('gen file_name:'+file_name);
                        return file_name;

                    }
                }

            });
        });

    },
    custom_upload_file_soft : function(file_type, btn_id,  is_public_bucket , complete_func, ctminfo , ext_file_list, noti_process ){
        do_ajax( "/common/get_bucket_info",{
            is_public: is_public_bucket ? 1:0
        },function(ret){
            var domain_name=ret.domain;
            var token=ret.token;

            var uploader = Qiniu.uploader({
                runtimes: 'html5, flash, html4',
                browse_button: btn_id , //choose files id
                uptoken: token,
                domain: "http://"+domain_name,
                max_file_size: '100mb',
                dragdrop: true,
                flash_swf_url: '/js/qiniu/plupload/Moxie.swf',
                chunk_size: '4mb',
                unique_names: false,
                save_key: false,
                auto_start: true,
                multi_selection: false,
                filters: {
                    mime_types: [
                        {title: "", extensions: ext_file_list.join(",") }
                    ]
                },
                init: {
                    'FilesAdded': function(up, files) {
                        plupload.each(files, function(file) {
                            console.log('waiting...'+file.name );
                        });
                    },
                    'BeforeUpload': function(up, file) {
                        console.log('before uplaod the file 11111');
                        var match = file.name.match(/.*\.(.*)?/);
                        var file_ext=match[1];
                        var check_flag=false;
                        $.each ( ext_file_list,  function(i,item) {
                            if ( item.toLowerCase() ==file_ext.toLowerCase()) {
                                check_flag=true;
                            }
                        });
                        if (!check_flag  ) {
                            BootstrapDialog.alert("文件后缀必须是: "+ ext_file_list.join(",") +"<br> 刷新页面，重新上传"  );
                            return false;
                        }
                        console.log('before uplaod the file');
                        return true;
                    },
                    'UploadProgress': function(up,file) {
                        if(noti_process) {
                            noti_process (file.percent);
                        }
                        console.log(file.percent);
                        console.log('upload progress');
                    },
                    'UploadComplete': function() {
                        console.log(' UploadComplete .. end ');
                    },
                    'FileUploaded' : function(up, file, info) {
                        if(noti_process) {
                            noti_process (0);
                        }
                        console.log('Things below are from FileUploaded');
                        console.log(1111);
                        if(info.response){
                            complete_func(up, info.response, file, ctminfo);
                        }else{
                            complete_func(up, info, file, ctminfo);
                        }

                    },
                    'Error': function(up, err, errTip) {
                        console.log('Things below are from Error');
                        BootstrapDialog.alert(errTip);
                    },
                    'Key': function(up, file) {
                        var key = "";
                        var time = (new Date()).valueOf();
                        var match = file.name.match(/.*\.(.*)?/);

                        // var file_name=$.md5(file.name) +time +'.' + match[1];
                        if(file_type == 1){
                            var new_file_name = "student/"+match[0];
                        }
                        console.log('gen file_name:'+new_file_name);
                        return new_file_name;
                    }
                }
            });
        });
    },


    check_in_wx :function (){
        //window.
        var ua = window.navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i) == 'micromessenger'){
            return true;
        }else{
            return false;
        }
    },


    check_in_phone :function (){
        return  $(window).width() <= 678;
    },


    wopen: function (url,open_self_window){
        if (open_self_window) {
            window.open(url,"_self");
        }else{
            window.open(url);
        }
    },

    dlg_get_html_by_class:function(item_class) {
        return $("." + item_class).html();
    },

    obj_copy_node :function (item){
        return $( $(item )[0].outerHTML );
    },
    updateUrl:function(url,key){
        key= (key || 't') +'=';  //默认是"t"
        var reg=new RegExp(key+'\\d+');  //正则：t=1472286066028
        var timestamp=+new Date();
        if(url.indexOf(key)>-1){ //有时间戳，直接更新
            return url.replace(reg,key+timestamp);
        }else{  //没有时间戳，加上时间戳
            if(url.indexOf('\?')>-1){
                var urlArr=url.split('\?');
                if(urlArr[1]){
                    return urlArr[0]+'?'+key+timestamp+'&'+urlArr[1];
                }else{
                    return urlArr[0]+'?'+key+timestamp;
                }
            }else{
                if(url.indexOf('#')>-1){
                    return url.split('#')[0]+'?'+key+timestamp+location.hash;
                }else{
                    return url+'?'+key+timestamp;
                }
            }
        }
    },

    isWeiXin:function(){
        var ua = window.navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i) == 'micromessenger'){
            return true;
        }else{
            return false;
        }
    },
    reload :function () {
        //alert("提交成功");
        if ($.isWeiXin()) {
            window.location.href=$.updateUrl(window.location.href,'_t');
        }else{
            window.location.reload();
        }
    },

    get_table_key:function (fix) {
        var path_list = window.location.path.split("/");
        return "" + fix + "-" + path_list[1] + "-" + path_list[2].split("?")[0];
    },

    do_ajax: function( url,data, success_func, jsonp_flag ){

        function ajax_default_deal_func(result){
            if (result.ret ){
                BootstrapDialog.alert(result['info']);
            }else{
                if (!window.g_t ) {
                    $.reload();
                }
            }
        }

        if (  !success_func) {
            success_func= ajax_default_deal_func ;
        }

        $.ajax({
            url:  url,
            type: 'POST',
            data:data,
            dataType: jsonp_flag? "jsonp": 'json',
            success: success_func

            ,error: function( XMLHttpRequest, textStatus, errorThrown ) {

                var first_flag=true;
                var args_str="";
                $.each(data, function(key,value){
                    if (first_flag) {
                        args_str= key +"=" + encodeURIComponent( value);
                        first_flag=false;
                    }else{
                        args_str+= "&"+key +"=" +  encodeURIComponent(value);
                    }
                });
                if( XMLHttpRequest.readyState ==4 ) {
                    var call_url= window.location.origin +  this.url+"?"+ args_str   ;
                    BootstrapDialog.alert("<a href=\""+call_url+"\" target=\"_blank\">系统错误- 操作失败, 已发邮件 通知开发人员 </a>"+  errorThrown);
                }
            },
        });
    },
    do_ajax_t: function (url,data,success_func){
        if (!success_func) {
            success_func=function(){};
        }
        $.do_ajax(url,data,success_func);
    },

    reload_self_page :function (args, url){
        var args_str="";
        var first_flag=true;

        var pathname=window.location.toString().split("?" )[0] ;

        var open_self_window=true;
        if (url) {
            pathname=url;
            open_self_window=false;
        }
        $.each(args, function(key,value){
            if (first_flag) {
                args_str= key +"=" + encodeURIComponent( value);
                first_flag=false;
            }else{
                args_str+= "&"+key +"=" +  encodeURIComponent(value);
            }
        });
        var len = pathname.length;
        var last_str = pathname.substring(len-1,len);
        if(last_str=="#"){
            pathname = pathname.substring(0,len-1);
        }
        $.wopen( pathname  +"?" +  args_str,  open_self_window );
    },

    enum_multi_select :function ( $element, enum_name, onChange , id_list   ,select_group_list) {
        //原来的不显示，显示display
        var $show_input = $($element[0].outerHTML);
        //清除id
        $show_input.attr("id","");
        $show_input.css("cursor","inherit");
        $show_input.insertAfter($element);
        $element.hide();

        var val            = $element.val();
        var select_list    = val.split(/,/);
        var select_id_list = [];
        var show_text_arr  = [];
        var desc_map=g_enum_map[enum_name]["desc_map"];
        console.log($show_input);
        console.log($element);
        $.each(select_list,function( ){
            var id= parseInt(this);
            select_id_list.push(id);
            if ( id==-1) {
                show_text_arr.push("全部");
            }else{
                show_text_arr.push(desc_map[id]);
            }
        });
        $show_input.val( show_text_arr.join(","));

        $show_input.on("click",function(){
            var desc_map=g_enum_map[enum_name]["desc_map"];

            var data_list=[
            ];
            $.each(desc_map, function(k,v){
                if ($.isArray( id_list)) {
                    if($.inArray( parseInt(k), id_list ) != -1 ){
                        data_list.push([k, v] );
                    }
                }else{
                    data_list.push([k, v] );
                }
            });

            var btn_list =[
            ];
            select_group_list = select_group_list || [];
            $.each( select_group_list, function( k , v  ){
                btn_list.push({
                    label: k,
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        dialog.close();
                        $element.val(v.join(","));

                        onChange( v );
                    }
                });
            } );

            btn_list.push({
                label: "全部",
                cssClass: 'btn-warning',
                action: function(dialog) {
                    dialog.close();
                    $element.val([-1].join(","));
                    onChange( [-1] );
                }
            });


            $("<div></div>").admin_select_dlg({
                'data_list': data_list,
                "header_list":["id","属性"] ,
                "onChange": function ( select_list,dlg ){
                    dlg.close();
                    var select_all=false;
                    $.each (select_list,function(){
                        if (this==-1) {
                            select_all=true;
                            return false;
                        }
                        return true;
                    }) ;

                    if (select_all) {
                        select_list = [-1];
                    }

                    $element.val(select_list.join(","));
                    onChange( select_list  );
                },
                "select_list": select_id_list,
                "multi_selection":true,
                btn_list :btn_list ,

            });

        });


    },
    enum_multi_select_new :function ( $element, enum_name, onChange , id_list   ,select_group_list) {
        //复制上面的，稍作修改
        //原来的不显示，显示display
        var $show_input = $($element[0].outerHTML);
        //清除id
        $show_input.attr("id","");
        $show_input.css("cursor","inherit");
        $show_input.insertAfter($element);
        $element.hide();

        var val            = $element.val();
        console.log(val)
        var select_list    = val.split(/,/);
        var select_id_list = [];
        var show_text_arr  = [];
        var desc_map=g_enum_map[enum_name]["desc_map"];
        console.log($show_input);
        console.log($element);
        $.each(select_list,function( ){
            var id= parseInt(this);
            select_id_list.push(id);
            if ( id==-1) {
                show_text_arr.push("全部");
            }else{
                show_text_arr.push(desc_map[id]);
            }
        });
        $show_input.val( show_text_arr.join(","));

        $show_input.on("click",function(){
            var desc_map=g_enum_map[enum_name]["desc_map"];

            var data_list=[
            ];
            $.each(desc_map, function(k,v){
                if ($.isArray( id_list)) {
                    if($.inArray( parseInt(k), id_list ) != -1 ){
                        data_list.push([k, v] );
                    }
                }else{
                    data_list.push([k, v] );
                }
            });

            var btn_list =[
            ];
            select_group_list = select_group_list || [];
            $.each( select_group_list, function( k , v  ){
                btn_list.push({
                    label: k,
                    cssClass: 'btn-primary',
                    action: function(dialog) {
                        dialog.close();
                        $element.val(v.join(","));
                        onChange( v );
                    }
                });
            } );

            btn_list.push({
                label: "全部",
                cssClass: 'btn-warning',
                action: function(dialog) {
                    dialog.close();
                    $element.val([-1].join(","));
                    $element.next().val('全部');
                    onChange( [-1] );
                }
            });


            $("<div></div>").admin_select_dlg({
                'data_list': data_list,
                "header_list":["id","属性"] ,
                "onChange": function ( select_list,dlg ){
                    dlg.close();
                    var select_all=false;
                    var next_val = '';
                    $.each (select_list,function(i){
                        if (this==-1) {
                            select_all=true;
                            return false;
                        }
                        next_val = next_val+ desc_map[select_list[i]]+',';
                        return true;
                    }) ;

                    if (select_all) {
                        select_list = [-1];
                    }

                    $element.val(select_list.join(","));
                    $element.next().val(next_val);
                    onChange( select_list  );
                },
                "select_list": select_id_list,
                "multi_selection":true,
                btn_list :btn_list ,

            });

        });


    },


    admin_select_user :function ( $element, type, call_func, is_not_query_flag, args_ex, th_input_id ) {
var select_no_select_value = -1;

        var select_no_select_title = "[全部]"  ;
        if (is_not_query_flag)  {
            select_no_select_value = 0;
            select_no_select_title = "[未设置]"  ;
        }
        args_ex = $.extend({}, {
            "main_type":  -1,
            "groupid":  -1,
            "select_btn_config": [],
            "adminid": -1
        },  args_ex);

        var filter_list=[];
        if (type=="admin" || type=="admin_group_master" ) {
            filter_list=[
                [
                    {
                        size_class: "col-md-4" ,
                        title :"分类",
                        type  : "select" ,
                        'arg_name' :  "main_type"  ,
                        value:  args_ex.main_type ,

                        select_option_list: [ {
                            value : -1 ,
                            text :  "全部"
                        },{
                            value :  0 ,
                            text :  "未设置"
                        },{
                            value :  1 ,
                            text :  "助教"
                        },{
                            value :  2 ,
                            text :  "销售"

                        },{
                            value :  3 ,
                            text :  "教务"
                        },{
                            value :  4 ,
                            text :  "教研"

                        },{
                            value :  5 ,
                            text :  "全职老师"

                        },{
                            value :  6 ,
                            text :  "薪资运营"
                        },{
                            value :  7 ,
                            text :  "TMK"
                        },{
                            value :  8 ,
                            text :  "招师"
                        },{
                            value :  9 ,
                            text :  "质监"
                        }]
                    },{
                        size_class: "col-md-8" ,
                        title :"姓名/电话",
                        'arg_name' :  "nick_phone"  ,
                        type  : "input"
                    }

                ]
            ];

        } else{
            filter_list=[
                [
                    {
                        size_class: "col-md-4" ,
                        title :"性别",
                        type  : "select" ,
                        'arg_name' :  "gender"  ,
                        select_option_list: [ {
                            value : -1 ,
                            text :  "全部"
                        },{
                            value :  1 ,
                            text :  "男"
                        },{
                            value :  2 ,
                            text :  "女"

                        }]
                    },{
                        size_class: "col-md-8" ,
                        title :"姓名/电话",
                        'arg_name' :  "nick_phone"  ,
                        type  : "input"
                    }

                ]
            ];


        }

        var field_list=[];
        if (type=="research_teacher" || type=="teacher" || type=="train_through_teacher" || type=="research_teacher_zs") {
            field_list= [
                {
                    title:"id",
                    width :50,
                    field_name:"id"
                },{
                    title:"昵称",
                    //width :50,
                    render:function(val,item) {
                        return item.nick;
                    }
                },{
                    title:"真实姓名",
                    //width :50,
                    render:function(val,item) {
                        return item.realname;
                    }

                },{
                    title:"电话",
                    field_name:"phone"
                },{
                    title:"科目",
                    field_name:"subject"
                },{
                    title:"年级",
                    field_name:"grade"
                }

            ];

        }else{
            field_list= [
                {
                    title:"id",
                    width :50,
                    field_name:"id"
                },{
                    title:"性别",
                    //width :50,
                    render:function(val,item) {
                        return item.gender;
                    }

                },{
                    title:"昵称",
                    //width :50,
                    render:function(val,item) {
                        return item.nick;
                    }
                },{
                    title:"真实姓名",
                    //width :50,
                    render:function(val,item) {
                        return item.realname;
                    }

                },{
                    title:"电话",
                    field_name:"phone"
                }
            ];

        }
        if ( window.location.hostname.indexOf('class') > 0){
            var length = window.location.href.indexOf('class');
            var url = window.location.href;
            var first_url = url.substring(0,length);
            var new_length = length+6;
            var last_url = url.substring(new_length,url.length);
            var new_url = first_url+last_url;
        }else{
            new_url = "/user_manage/get_user_list";
        }


        $element.admin_select_dlg_ajax({
            "lru_flag" : true,
            "lru_item_desc" : function(item ) {
                return " "+ item.nick + "-"+ item.phone ;
            },
            "opt_type" :  "select", // or "list"
            select_no_select_value  :   select_no_select_value , // 没有选择是，设置的值
            select_no_select_title  :   select_no_select_title, // "未设置"
            "th_input_id" : th_input_id,

            "url"          : new_url,
            //其他参数
            "args_ex" : {
                type  : type ,
                groupid: args_ex.groupid,
                adminid: args_ex.adminid
            },
            select_primary_field : "id",
            select_display       : "nick",


            //字段列表
            'field_list' : field_list,
            //查询列表
            filter_list: filter_list,

            "auto_close"       : true,
            //选择
            "onChange"         : call_func,
            //加载数据后，其它的设置
            "onLoadData"       : null,
            select_btn_config :  args_ex.select_btn_config,

        });

    },

    dlg_need_html_by_id:function( id ){
        return $('<div></div>').append( $('#' +id ).html() );
    },

    DateFormat : function ( unixtime, fmt) {
        var date_v=new Date(unixtime*1000);
        var o = {
            "M+": date_v.getMonth() + 1, //月份
            "d+": date_v.getDate(), //日
            "h+": date_v.getHours(), //小时
            "m+": date_v.getMinutes(), //分
            "s+": date_v.getSeconds(), //秒
            "q+": Math.floor((date_v.getMonth() + 3) / 3), //季度
            "S": date_v.getMilliseconds() //毫秒
        };
        if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (date_v.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (var k in o)
            if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return  fmt;
    },
    // 2016-14-10 12:10:10
    strtotime:function( str) {
        var tmp_datetime = str.replace(/:/g,'-');
        tmp_datetime = tmp_datetime.replace(/ /g,'-');
        var arr = tmp_datetime.split("-");
        var hh=arr[3];
        var mm=arr[4];
        var ss=arr[5];
        if (hh == undefined) {
            hh=0;
        }
        if (mm== undefined) {
            mm=0;
        }
        if (ss== undefined) {
            ss=0;
        }

        var now = new Date(Date.UTC(arr[0],arr[1]-1,arr[2],hh-8,mm,ss));
        return parseInt(now.getTime()/1000);
    },




    do_ajax_get_nick:function( type,  id, func) {
        $.ajax({
            type     : "post",
            url      : "/user_manage/get_nick" ,
            dataType : "json",
            data : {
                "type" : type
                ,"id"  : id
            },

            error: function() {
                alert("操作失败, 已通知开发人员 ");
            },
            success : function(result){
                var nick = result.nick;
                func(id,nick );
            }});
    },

    do_get_env:function( func) {
        $.ajax({
            type     : "post",
            url      : "/common_new/get_env" ,
            dataType : "json",
            data : {
            },
            success : function(result){
                var env= result.env;
                func(env);
            }});
    },


    fiter_obj_field:function( obj,field_name_list ) {
        var ret= {};
        $.each( field_name_list ,function(i,field_name){
            ret[field_name]=obj[field_name];
        });
        return ret;
    },

    show_input:function( title,  value, ok_func ,$input  ){
        if (!$input){
            $input=$("<input/>");
        }else{
            $input=$($input);
        }

        $input.val(value);

        BootstrapDialog.show({
            title: title,
            message: $input,
            buttons: [{
                label: '确认',
                cssClass: 'btn btn-warning',
                action: function(dialog)  {
                    ok_func($input.val());
                    dialog.close();
                }
            }, {
                label: '取消',
                cssClass: 'btn btn-default',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });

    },

    dlg_set_width:function(dlg, width) {
        dlg.getModalDialog().css("width", ""+width+"px");
    },

    get_page_select_date_str:function(has_date_type) {
        var str="";
        if (has_date_type) {
            str="date_type"+"="+$('#id_date_type').val() +"&";
        }
        return str+= "opt_date_type"+"="+$('#id_opt_date_type').val()+"&"+
            "start_time"+"="+	$('#id_start_time').val()+"&"+
            "end_time"+"="+$('#id_end_time').val();
    },
    plupload_Uploader:function( config ) {
        return  new plupload.Uploader(config);
    },
    get_action_str:function() {
        var ret=window.location.pathname.split("/")[2];
        if (!ret) {
            ret="index";
        }
        return ret;
    },
    show_key_value_table :function (title,arr ,btn_config,onshownfunc, close_flag, width ){
        var table_obj=$("<table class=\"table table-bordered table-striped\"  > <tr> <thead> <td style=\"text-align:right;\">属性  </td>  <td> 值 </td> </thead></tr></table>");

        $.each(arr , function( index,element){
            var row_obj=$("<tr> </tr>" );
            var td_obj_left=$( "<td style=\"text-align:right; width:30%;\"></td>" );
            var td_obj_right=$( "<td ></td>" );

            $.each(element,function(dom_key,dom_value){
                if(dom_key==0){
                    td_obj_left.append(dom_value);
                }else{
                    td_obj_right.append(dom_value);
                }
            });
            row_obj.append(td_obj_left);
            row_obj.append(td_obj_right);

            table_obj.append(row_obj);
        });
        var all_btn_config=[{
            label: '返回',
            action: function(dialog) {
                dialog.close();
            }
        }];
        if (btn_config){
            if($.isArray( btn_config)){
                $.each(btn_config ,function(){
                    all_btn_config.push(this);
                });
            }else{
                all_btn_config.push(btn_config );
            }
        }
        var closable = true;
        if (close_flag) {
            closable=false;
        }

        var dlg=BootstrapDialog.show({
            title: title,
            message :  table_obj ,
            closable: false,
            buttons: all_btn_config ,
            onshown:onshownfunc
        });

        if (closable) {
            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );
        }

        if (width) {
            dlg.getModalDialog().css("width", ""+width+"px");
        }

    }
    ,check_power:function( powerid ) {
        return g_power_list[powerid];
    },    tea_show_key_value_table :function (title,arr ,btn_config,onshownfunc, close_flag, width , styleCss){
        var table_obj=$("<table class=\"table table-bordered \"> <tr> <thead></thead></tr></table>");
        var styleCss = styleCss;//给第二个td添加自定义属性
        $.each(arr , function( index,element){
            var row_obj=$("<tr> </tr>" );
            var v = element[0] ;
            if ( v === 'merge') {
                var td_obj=$( "<td colspan=\"2\" style=\"text-align:center;color:#00a6ff\"></td>" );
                td_obj.append( element[1] );
                row_obj.append(td_obj);
                table_obj.append(row_obj);

            } else {
                var td_obj=$( "<td style=\"text-align:right; width:30%;line-height:33px;\"></td>" );
                td_obj.append(v);
                row_obj.append(td_obj);
                td_obj=$( "<td style=\""+styleCss+"\"></td>" );

                td_obj.append( element[1] );
                row_obj.append(td_obj);
                table_obj.append(row_obj);
            }
        });
        var all_btn_config=[{
            label: '返回',
            cssClass : 'btn-default col-xs-2 col-xs-offset-7',
            action: function(dialog) {
                // dialog.close();

                if(confirm('你还未保存信息，确定要返回吗？')){
                    dialog.close();
                }
            }
        }];
        if (btn_config){
            if($.isArray( btn_config)){
                $.each(btn_config ,function(){
                    all_btn_config.push(this);
                });
            }else{
                all_btn_config.push(btn_config );
            }
        }
        var closable = true;
        if (close_flag) {
            closable=false;
        }

        var dlg=BootstrapDialog.show({
            title: title,
            message :  table_obj ,
            closable: false,
            buttons: all_btn_config ,
            onshown:onshownfunc
        });

        if (closable) {
            var close_btn=$('<div class="bootstrap-dialog-close-button" style="display: block;"><button class="close">×</button></div>');
            dlg.getModalDialog().find(".bootstrap-dialog-header").append( close_btn);
            close_btn.on("click",function(){
                dlg.close();
            } );
        }

        if (width) {
            dlg.getModalDialog().css("width", ""+width+"px");
        }

    }

});
