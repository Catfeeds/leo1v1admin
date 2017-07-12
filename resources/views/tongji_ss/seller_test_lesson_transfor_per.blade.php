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
                    <td>主管 </td>
                    <td>小组 </td>
                    <td>成员 </td>
                    <td>试听成功数</td>
                    <td>签约人数</td>
                    <td>签约率</td>
                    <td>绿色通道试听成功数</td>
                    <td>绿色通道占比</td>
                    <td>绿色通道签约人数</td>
                    <td>绿色通道签约率</td>
                    <td>非绿色通道试听成功数</td>
                    <td>非绿色通道签约人数</td>
                    <td>非绿色通道签约率</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )

                    <tr class="{{$var["level"]}}">
                        <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>
                        <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>
                        <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>
                        <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}}</td>
                        <td >{{@$var["succ_all_count_for_month"]}}</td>
                        <td >{{@$var["all_new_contract_for_month"]}}</td>
                        <td >{{@$var["order_per"]}}</td>
                        <td >{{@$var["succ_green_count_for_month"]}}</td>
                        <td >{{@$var["green_per"]}}</td>
                        <td >{{@$var["all_green_contract_for_month"]}}</td>
                        <td >{{@$var["green_order_per"]}}</td>
                        <td >{{@$var["non_green_count"]}}</td>
                        <td >{{@$var["non_green_order"]}}</td>
                        <td >{{@$var["non_green_order_per"]}}</td>


                        <td>
                            <div
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
