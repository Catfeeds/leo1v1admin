/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/info_support-get_books.d.ts" />


$(function(){
    Enum_map.append_option_list("subject", $("#id_subject"),false,[1,2,3,4,5,6,7,8,9,10,11]);
    $("#id_subject").val(g_args.subject);
    var provice_pro = "<option value='-1'>全部</option>";
    $.each(ChineseDistricts[86],function(i,val){
        provice_pro = provice_pro + '<option value='+i+'>'+val+'</option>'
    });
    $("#id_province").html(provice_pro);
    $("#id_province").val(g_args.province);

    var city_pro = "<option value='-1'>全部</option>";
    if(g_args.province > 0 ){
        $.each(ChineseDistricts[g_args.province],function(i,val){
            city_pro = city_pro + '<option value='+i+'>'+val+'</option>'
        });
    }
    $("#id_city").html(city_pro);
    $("#id_city").val(g_args.city);

    $('.opt-change').set_input_change_event(load_data);
    
    
    //添加教材
    $('.opt_add').on('click',function(){
        var id_subject = $("<select style='width:90%'/>");
        Enum_map.append_option_list("subject", id_subject,true,[1,2,3,4,5,6,7,8,9,10,11]);

        var id_province = $("<select style='width:90%' onchange='get_city(this.options[this.options.selectedIndex].value,event)'/>");
        var pro = "<option value='0'>全部</option>";
        $.each(ChineseDistricts[86],function(i,val){
            pro = pro + '<option value='+i+'>'+val+'</option>'
        });
        id_province.html(pro);

        var id_city = $("<select style='width:90%'><option value='0'>全部</option></select>");

        var id_little = $("<input style='width:90%' onclick='get_books(event)'/>");
        var id_middle = $("<input style='width:90%' onclick='get_books(event)'/>");
        var id_high = $("<input style='width:90%' onclick='get_books(event)'/>");
        var arr=[
            ["科目", id_subject ],
            ["省份", id_province ],
            ["城市", id_city ],
            ["小学教材版本", id_little ],
            ["初中教材版本", id_middle ],
            ["高中教材版本", id_high ],
        ];
         $.show_key_value_table("添加教材版本", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var subject = parseInt(id_subject.val());
                var province = parseInt(id_province.val());
                var city = parseInt(id_city.val());
                var province_name = id_province.find("option:selected").text();
                var city_name = id_city.find("option:selected").text();

                var low  = id_little.attr('books');
                var middle  = id_middle.attr('books');
                var high  = id_high.attr('books');
                var data = {
                    'subject':subject,
                    'province':province,
                    'province_name' : province_name,
                    'city':city,
                    'city_name' : city_name,
                    "low" : low,
                    "middle" : middle,
                    "high"  : high
                }
                console.log(data);
                if(!subject || !province || !city){
                    BootstrapDialog.alert("科目或者省份或者城市不能为空");
                    return false;
                }   
               
                $.ajax({
                    type     :"post",
                    url      :"/info_support/save_books",
                    dataType :"json",
                    data     :data,
                    success : function(res){
                        BootstrapDialog.alert(res.msg);
                        window.location.reload();
                    }
                });
            }
         },function(){

         },false,900)
    })

    $(".opt_edit").on("click",function(){
        var obj = $(this).parents("tr");
        var id_subject = $("<select style='width:90%'/>");
        Enum_map.append_option_list("subject", id_subject,true,[1,2,3,4,5,6,7,8,9,10,11]);
        id_subject.val(obj.find(".subject").attr('subject'));

        var id_province = $("<select style='width:90%' onchange='get_city(this.options[this.options.selectedIndex].value,event)'/>");
        var pro = "<option value='0'>全部</option>";
        $.each(ChineseDistricts[86],function(i,val){
            pro = pro + '<option value='+i+'>'+val+'</option>'
        });
        id_province.html(pro);
        var province = obj.find(".province").attr('province');
        id_province.val(province);

        var id_city = $("<select style='width:90%' />");
        var city_pro = "<option value='0'>全部</option>";
        $.each(ChineseDistricts[province],function(i,val){
            city_pro = city_pro + '<option value='+i+'>'+val+'</option>'
        });
        id_city.html(city_pro);
        id_city.val(obj.find(".city").attr('city'));

        var id_little = $("<input style='width:90%' onclick='get_books(event)'/>");
        var id_middle = $("<input style='width:90%' onclick='get_books(event)'/>");
        var id_high = $("<input style='width:90%' onclick='get_books(event)'/>");

        get_books_val(obj.find(".low"),id_little);
        get_books_val(obj.find(".middle"),id_middle);
        get_books_val(obj.find(".high"),id_high);
        var arr=[
            ["科目", id_subject ],
            ["省份", id_province ],
            ["城市", id_city ],
            ["小学教材版本", id_little ],
            ["初中教材版本", id_middle ],
            ["高中教材版本", id_high ],
        ];
         $.show_key_value_table("添加教材版本", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {
                var subject = parseInt(id_subject.val());
                var province = parseInt(id_province.val());
                var city = parseInt(id_city.val());
                var province_name = id_province.find("option:selected").text();
                var city_name = id_city.find("option:selected").text();

                var low  = id_little.attr('books');
                var middle  = id_middle.attr('books');
                var high  = id_high.attr('books');
                var data = {
                    'subject':subject,
                    'province':province,
                    'province_name' : province_name,
                    'city':city,
                    'city_name' : city_name,
                    "low" : low,
                    "middle" : middle,
                    "high"  : high
                }
                console.log(data);
                if(!subject || !province || !city){
                    BootstrapDialog.alert("科目或者省份或者城市不能为空");
                    return false;
                }   
               
                $.ajax({
                    type     :"post",
                    url      :"/info_support/save_books",
                    dataType :"json",
                    data     :data,
                    success : function(res){
                        BootstrapDialog.alert(res.msg);
                        window.location.reload();
                    }
                });
            }
         },function(){

         },false,900)
 
    });
});

function load_data(){

    $.reload_self_page ( {
        subject       : $('#id_subject').val(),
        province         : $('#id_province').val(),
        city       : $('#id_city').val(),
    });
}
    
function get_city(province,oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var $city = $(target).parents("tr").next().find("select");
  
    var pro = '<option value="0">全部</option>';
    
    if(province > 0){
        $.each(ChineseDistricts[province],function(i,val){
            pro = pro + '<option value='+i+'>'+val+'</option>'
        });
    }
    $city.html(pro);
}

function get_books_val(obj,id_obj){
    var name_str = "",id_str = "";
    obj.find('span').each(function(){
        name_str += $(this).text();
        id_str += $(this).attr("book") + ',';
    });
    if(name_str != ""){
        name_str = name_str.substring(0,name_str.length-1);
        id_obj.val(name_str);
    }
    if(id_str != ""){
        id_str = id_str.substring(0,id_str.length-1);
        id_obj.attr({"books":id_str});
    }
}

function get_books(oEvent){
    var e = oEvent || window.event;
    var target = e.target || e.srcElement;
    var books  = $(target).attr('books');
    var select_list = [];
    var select_search = $(target).val();
    if(books){
        var book_arr = books.split(',');
        for(var x in book_arr){
            select_list.push(book_arr[x]);
        };
    }

    $(this).admin_select_dlg_second({
        header_list     : [ "id","名称" ],
        data_list       : [],
        enum_name   : "region_version",
        multi_selection : true,
        select_list     : select_list,
        select_search   : select_search,
        onChange        : function( select_list,dlg) {
            var str = '';
            var str_id = '';
            $('#id_body .warning').each(function(){
                str += $(this).find('td:eq(1)').text() + ',';
                str_id += $(this).find('td:eq(0)').text() + ',';
            })

                str_id != '' ? str_id = str_id.substring(0,str_id.length-1) : '' ;
            str != '' ? str = str.substring(0,str.length-1) : '' ;
            
            $(target).val(str);
            $(target).attr({'books':str_id});
        }
    });

}
