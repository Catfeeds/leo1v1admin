import Vue from 'vue'
import Component from 'vue-class-component'
import { timingSafeEqual } from 'crypto';

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template : require("./admin_table_row.html" ),
  created  : function () {
  },

  data : function () {
    return {

    };
  },
  props : {
    "row_data" : {
      type     : Object,
      required : true,
    },

    "index" : {
      type     : Number,
      required : true,
    }

  },
  computed : {
    multi_select : function () {
      return this.$parent.$props.multi_select;
    }
  },
  mounted : function(){
  },
})



export default class admin_table_row extends Vue {

  on_created (){
  }

  show_as_list(e : MouseEvent ) {
    var item = this.$props.row_data;
    var index = this.$props.index;
    var th_row = $(e.currentTarget).closest("table").find("thead td");
    var data_row = $(e.currentTarget).closest("tr").find("td");
    var arr :Array<any> = [];
    th_row.each(function (index, element) {
      if (index != 0 && index != th_row.length - 1) {
        arr.push([$(element).text(), $(data_row[index]).text().replace(/,/g, ", ")]);
      }
      if (index == th_row.length - 1) {
        var a_list = $(data_row).find(">div >a");
        var opt_arr : Array<any> = [];
        var $show_inter_data = $("<a class=\"btn fa inter-data\" href=\"javascript:;\"  >inter data</a>");
        if (!$(e.currentTarget).hasClass("table-clean-flag")) {
          opt_arr.push(["操作", $show_inter_data]);
        }
        $show_inter_data.on("click", function () {
          var opt_data_arr  :Array<any>= [];
          $.each(item, function(k, v){
            opt_data_arr.push([k,v]);
          });
          $.show_key_value_table("内部数据", opt_data_arr);
        });
        $.each(a_list, function (a_i, a_item) {
          var new_item = $(a_item).clone();
          if (!new_item.hasClass("td-info")
              && new_item.css("display") != "none") {
            new_item.append(" " + new_item.attr("title"));
            new_item.on("click", function () {
              $(a_item)[0].click();
            });
            opt_arr.push(["操作",
                          new_item]);
          }
        });
        arr = opt_arr.concat(arr);
      }
    });
    $.show_key_value_table("详细信息", arr);
    return false;

  }


}
