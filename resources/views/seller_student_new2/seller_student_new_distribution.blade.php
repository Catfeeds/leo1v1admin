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
                    <td>入职时间 </td>
                    <td>离职时间 </td>
                    <td>是否离职 </td>
                    <td>拨打认领数</td>
                    <td>手动认领数</td>
                    <td>手动分配数</td>
                    <td>手动分配未联系数</td>
                    <td>在职人数</td>
                    <td>离职人数</td>
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
                        <td >{{@$var["become_member_time"]}}</td>
                        <td >{{@$var["leave_member_time"]}}</td>
                        <td>{!! @$var["del_flag_str"] !!}</td>
                        <td >{{@$var["auto_get_count"]}}</td>
                        <td >{{@$var["hand_get_count"]}}</td>
                        <td >
                            <a href="javascript:;" class="distribution_count" >
                                {{@$var["count"]}}
                            </a>
                        </td>
                        <td >
                            <a href="javascript:;" class="no_call_count" >
                                {{@$var["no_call_count"]}}
                            </a>
                        </td>
                        <td >{{@$var["become_member_num"]}}</td>
                        <td >{{@$var["leave_member_num"]}}</td>
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
