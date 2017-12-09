/// <reference path="../common.d.ts" />

(function($, window, document,undefined) {

    var init_th_input=function( $ele, $th, options ) {
        var title=$.trim($th.find("span").text());
        var old_title=title;
        $th.data("title",title);
        var enum_name =  options.enum_type;
        var id_list= options.show_id_list;
        var select_group_list= options.btn_list;
        var desc_map=g_enum_map[enum_name]["desc_map"];

        var select_list    = [];
        // alert( options.select_value );
        select_list= (""+ options.select_value).split(/,/);
        var select_id_list = [];
        $.each(select_list,function( ){
            var id= parseInt(this);
            select_id_list.push(id);
        });


        var data_list=[
        ];
        $.each(desc_map, function(k,v){
            if ($.isArray( id_list)) {
                if($.inArray( parseInt(k), id_list ) != -1 ){
                    data_list.push([ parseInt(k), v] );
                }
            }else{
                data_list.push([ parseInt(k), v] );
            }
        });
        var select_css = options.select_css;

        var row_str= "";
        var select_desc_list=[];
        $.each( data_list , function(k, v){
            var id=v[0];
            var desc=v[1];
            var row_css="";
            if ($.inArray( id, select_id_list  ) !==-1 ) {
                row_css= select_css ;
            }
            row_str+= "<tr class=\""+ row_css +"\" data-id=\""+id+"\" > <td> "+id+" </td> <td> "+desc+"</td> </tr> ";
        });
        $.each(select_id_list,function() {
            var id=this;
            if (id!=-1) {
                select_desc_list.push( Enum_map.get_desc( enum_name, id ));
            }
        });

        if(select_desc_list.length >0 ) {
            title= select_desc_list.join(",");
        }

        if (title.length > options.title_length ) {
            title=title.substring(0, options.title_length )+"...";
        }

        var btn_id_config= options.btn_id_config;
        var btn_ex_str="";
        $.each(btn_id_config,function( k, v ){
            btn_ex_str+='  <button class="btn btn-info btn-do-post-select" data-value="'+
                v.join(",") +'">'+ k +'</button>' ;
        } );
        var  display_title="";
        if ( old_title != title) {
            display_title= old_title;
        }

        var  html_obj=$(
            ' <li class="dropdown  " style="list-style: none;" >  ' +
                '  <a href="#" class="dropdown-toggle a-title" data-toggle="dropdown" aria-expanded="true"> '
                + title +' </a>'
                + '  <ul class="dropdown-menu"  style="width:'+options.width +'px;"  > ' +
                '       <li style=" margin:5px auto; width:90%;float: right;" > <span> <span class="text-danger" style=" font-size:18px"> '+display_title +'</span> <input  type="checkbox"  class="input_multi_select"  />  多选  </span> '
                + btn_ex_str
                +' <button class="btn btn-primary btn-do-post-select" data-value="-1"> 全部</button>  <button class="btn btn-warning btn-do-post "  > 提交 </button> </li>' +
                '       <table class=" table table-bordered table-hover " style=" margin:0 auto; width:90%;"  > ' + row_str +
                '   </table> ' +
                '  </ul> ' +
                ' </li> ');


        html_obj.find("tr" ).on("click",function( ){

            var multi_selection=window.localStorage.getItem(options.multi_selection_key);
            if (!( multi_selection =="true")){
                html_obj.find( "table tr" ).removeClass( select_css );
            }

            $(this).toggleClass( select_css );

            if (!( multi_selection =="true")){
                var select_value=$(this).data("id");
                $ele.val(select_value);
                if (options.onChange){
                    options.onChange( select_value);
                }
            }

            return false;
        });

        var multi_selection=window.localStorage.getItem(options.multi_selection_key);

        var $input_multi_select =html_obj.find(".input_multi_select");
        if (multi_selection =="true") {
            $input_multi_select.attr("checked", "checked" );
            html_obj.find(".btn-do-post").show();
        }else{
            html_obj.find(".btn-do-post").hide();
        }
        $input_multi_select.on("change",function( ){
            var val=""+ $input_multi_select.is(':checked');
            window.localStorage.setItem(options.multi_selection_key, val);
            if (val=="true") {
                html_obj.find(".btn-do-post").show();
            }else{
                html_obj.find(".btn-do-post").hide();
            }
        })

        $th.find("span").html( html_obj );
        html_obj.find(".btn-do-post").on("click", function() {
            var $select_list=html_obj.find("table ." +select_css );
            var select_arr=[];
            $.each( $select_list, function( ){
                select_arr.push( $(this).data("id"));
            });
            var select_value=select_arr.join(",");
            $ele.val(select_value);
            if (options.onChange){
                options.onChange(select_value);
            }
        });
        html_obj.find(".btn-do-post").on("click", function() {
            var $select_list=html_obj.find("table ." +select_css );
            var select_arr=[];
            $.each( $select_list, function( ){
                select_arr.push( $(this).data("id"));
            });
            var select_value=select_arr.join(",");
            $ele.val(select_value);
            if (options.onChange){
                options.onChange(select_value);
            }
        });

        html_obj.find(".btn-do-post-select").on("click", function() {
            var select_value= $(this).data("value");
            $ele.val(select_value);
            if (options.onChange){
                options.onChange(select_value);
            }
        });

        //排序 放进来
        html_obj.find(".a-title" ).append( $th.find(".td-sort-item" ));

    }
    //定义构造函数
    var Cset_select_dlg= function( $ele,  opt) {
        this.$ele= $ele;

        this.defaults = {
            "enum_type"    : "grade",
            "select_value" : -1,
            "onChange"     : null,
            "th_input_id"  : null,
            "show_id_list" : null,
            "width"   :300,
            "btn_id_config": [],
            "select_css" :  "danger",
            "title_length" : 7,

        };
        this.options = $.extend({}, this.defaults, opt);
        this.options.multi_selection_key="multi_selection_key_"+$.trim(window.location.pathname)+ "_"  + this.$ele.attr("id");

        var me=this;
        var th_input_id=this.options.th_input_id  ;
        var set_to_th_flag=false;

        if ( !$.check_in_phone() &&  th_input_id) {
            var $th_input= $("#" + this.options.th_input_id  );
            if ($th_input.is(":visible")){ 
                this.$ele.parent().parent().hide();
                this.$ele.parent().parent().data( "always_hide", 1);
                init_th_input( $ele, $th_input, this.options  );

                set_to_th_flag=true;
            }
        }

        this.$ele.val(this.options.select_value);
        if (!set_to_th_flag ){
            $.enum_multi_select(
                this.$ele,
                this.options.enum_type ,
                this.options.onChange,
                this.options.show_id_list ,
                this.options.btn_id_config );
        }
    };


    //定义方法
    Cset_select_dlg.prototype = {

    };

    //在插件中使用对象
    $.fn.admin_set_select_field = function(options) {
        //创建的实体
        var select_dlg  = new Cset_select_dlg(this,  options);
        return  select_dlg;
    };
})(jQuery, window, document);


(function($, window, document,undefined) {

    //在插件中使用对象
    $.fn.admin_select_user_new = function(options) {
        $(this).val( options.select_value);
        if (options.can_sellect_all_flag) {
            var args_ex= $.extend({}, options.args_ex, { "select_btn_config": [{
                "label": "全部",
                "value": -1
            }]} );
        }

        $.admin_select_user( $(this),
                             options.user_type,
                             options.onChange,
                             options.is_not_query_flag,
                             args_ex,
                             options.th_input_id
                           );
    };
})(jQuery, window, document);
