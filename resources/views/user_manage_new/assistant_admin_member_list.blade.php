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
                    <td>目标系数</td>
                    <td>续费目标值</td>
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
                        <td class="lesson_target">{{@$var["lesson_target"]}}</td> 
                        <td class="renew_target">{{@$var["renew_target"]}}</td> 
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                
                                @if(in_array($account,["jack","jim","郑璞","孙佳旭","孙瞿","michael"]) && $var["main_type"]==1 && $var["level"]=="l-1")
                                    <a  title="编辑当月目标系数" class="fa fa-edit opt-ass-month-target"></a>
                                    <a  title="查看更改记录" class=" opt-show-change-list">查看更改记录</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
 
@endsection

