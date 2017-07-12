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
    <script type="text/javascript" >
     var g_month_week_start = "{{@$month_week_start}}";
     var g_month= "{{@$month}}";
    </script>
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
     #cal_week .leave {
         background-color : #f00;
     }
     #cal_week .overtime {
         background-color : orange;
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
                    <td>月度团队目标</td>
                    <td>月度个人目标</td>
                    <td>月度个人试听目标</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )

                    <tr class="{{$var["level"]}}">
                        <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>
                        <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>
                        <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>
                        <td data-class_name="{{$var["account_class"]}}" class="account  {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}}</td>
                        <td class="month_money">{{@$var["month_money"]}}</td>
                        <td class="personal_money">{{@$var["personal_money"]}}</td>
                        <td class="test_lesson_count"> {{@$var["test_lesson_count"]}}</td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                               <a  title="编辑当月团队目标" class="fa fa-cny opt-seller-month-money"></a>
                               <a  title="编辑当月个人目标" class="fa fa-money opt-seller-personal-money"></a>
                               <a title="当月上班时间" class="fa fa-th-list opt-edit"></a>
                               <a title="请假及加班设置" class="fa fa-user opt-user"></a>
                               <!-- -<a  title="编辑当月上班时间" href="edit_seller_time?month={{$month}}&adminid={{@$var['adminid']}}&groupid={{@$var['groupid']}}" target="_blank" class="fa fa-th-list opt-edit-seller-time"></a> -->
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
