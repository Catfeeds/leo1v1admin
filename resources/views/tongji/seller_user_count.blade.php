@extends('layouts.app')
@section('content')
    <script type="text/javascript" > 
     var self_groupid = "{{$self_groupid}}";
    </script>

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >组</span>
                        <select  class="opt-change"  id="id_groupid"  >
                            <option value="-1">全部 </option>
                            @foreach ( $group_field_list as $var ) 
                                <option value="{{$var["groupid"]}}">{{$var["group_name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >负责人</span>
                        <input type="text" value=""  class="opt-change"  id="id_admin_revisiterid"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>

            <table     class="common-table"  > 
                <thead>
                    <tr>
                      
                            <td>类型 </td>
                            <td>主管 </td>
                            <td>小组 </td>
                            
                        {!!\App\Helper\Utils::th_order_gen([
                            ["负责人","nick" ],
                            ["新进例子数","add_time_count" ],
                            ["消耗例子数","first_revisite_time_count" ],
                            ["例子进入一天内消耗数","after_24_first_revisite_time_count" ],
                            ["回访旧例子数","call_old_count" ],
                            ["申请排课数","require_test_lesson_count" ],
                            ["(销售)正式排课数","seller_test_lesson_count" ],
                            ["(教务)正式排课数","test_lesson_count" ],
                            ["试听成功数","test_lesson_count_succ" ],
                            ["试听失败-支付老师数","test_lesson_count_fail_need_money" ],
                            ["试听失败-不支付老师数","test_lesson_count_fail_not_need_money" ],
                            ["试听失败-换时间","test_lesson_count_change_time" ],
                           ])!!}

                        <td> 操作  </td> </tr>
                </thead>
                <tbody>
                    @foreach ( $table_data_list as $var )
                        
                        <tr class="{{$var["level"]}}">
                            <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>
                            <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>
                            <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>
                            <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}}</td>

                            <td class="add_time_count"> <a href="javascript:;" >{{@$var["add_time_count" ]*1}}</a></td>
                            <td class="first_revisite_time_count"> <a href="javascript:;" >{{@$var["first_revisite_time_count" ]*1}}</a></td>
                            <td class="after_24_first_revisite_time_count"> <a href="javascript:;" >{{@$var["after_24_first_revisite_time_count" ]*1}}</a></td>
                            <td class="call_old_count"> <a href="javascript:;" >{{@$var["call_old_count" ]*1}}</a></td>
                            <td class="require_test_lesson_count"> <a href="javascript:;" >{{@$var["require_test_lesson_count" ]*1}}</a></td>
                            <td class="seller_test_lesson_count"> <a href="javascript:;" >{{@$var["seller_test_lesson_count" ]*1}}</a></td>
                            <td class="test_lesson_count"> <a href="javascript:;" >{{@$var["test_lesson_count" ]*1}}</a></td>
                            <td class="test_lesson_count_succ"> <a href="javascript:;" >{{@$var["test_lesson_count_succ" ]*1}}</a></td>
                            <td class="test_lesson_count_fail_need_money"> <a href="javascript:;" >{{@$var["test_lesson_count_fail_need_money" ]*1}}</a></td>
                            <td class="test_lesson_count_fail_not_need_money"> <a href="javascript:;" >{{@$var["test_lesson_count_fail_not_need_money" ]*1}}</a></td>
                            <td class="test_lesson_count_change_time"> <a href="javascript:;" >{{@$var["test_lesson_count_change_time" ]*1}}</a></td>

                            <td>
                                <div class="row-data"
                                    {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>



        @include("layouts.page")
    </section>
    
@endsection

