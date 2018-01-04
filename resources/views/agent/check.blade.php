@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/select_seller_month_thing.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>
    <style>
     #cal_week th  {
         text-align:center;
     }

     #cal_week td  {
         text-align:center;
     }

     #cal_week .select_free_time {
         background-color : #17a6e8;
     }
    </style>




    <section class="content ">

        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>类型 </td>
                    <td>总监 </td>
                    <td>经理 </td>
                    <td>小组 </td>
                    <td>成员 </td>
                    <td>入职时间 </td>
                    <td>离职时间 </td>
                    <td>是否离职 </td>
                    <td>本月接通次数 </td>
                    <td>未接通次数 </td>
                    <td>有效试听次数 </td>
                    <td>预约试听次数 </td>
                    <td>签单数 </td>
                    <td>退单数 </td>
                    <td>11月等级 </td>
                    <td>12月等级 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr class="{{$var["level"]}}">
                        <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>
                        <td  data-class_name="{{@$var["first_group_name_class"]}}" class=" first_group_name  {{$var["main_type_class"]}} {{@$var["first_group_name_class"]}}  " >{{@$var["first_group_name"]}}</td>
                        <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name {{@$var["first_group_name_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>
                        <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>
                        <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}}
                            @if($var["seller_level_str"])
                                /{{$var["seller_level_str"]}}
                            @endif
                        </td>
                        <td >{{@$var["become_member_time"]}}</td>
                        <td >{{@$var["leave_member_time"]}}</td>
                        <td>{!! @$var["del_flag_str"] !!}</td>
                        <td class="called_times"></td>
                        <td class="no_called_times"></td>
                        <td class="suc_test_lesson_cout"></td>
                        <td class="require_lesson_count"></td>
                        <td class="order_count"></td>
                        <td class="refund_count"></td>
                        <td class="level11"></td>
                        <td class="level12"></td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-show"  title="编辑"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
