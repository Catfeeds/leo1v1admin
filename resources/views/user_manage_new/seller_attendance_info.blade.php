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
                <div class="col-xs-6 col-md-2"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <input type="text" value="" class=" form-control click_on put_name opt-change"  data-field="account" id="id_account"  placeholder="根据姓名 回车查找" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >应出勤情况</span>
                        <select  id="id_plan_seller_work_status" class="opt-change"  >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >实际出勤情况</span>
                        <select  id="id_seller_work_status" class="opt-change"  >
                            <option value="-2">出勤 </option>

                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <button class="btn" id="id_plan_work_count" data-value="{{$plan_work_count}}" >{{$plan_work_count}}</button>
                    <button class="btn" id="id_real_work_count" data-value="{{$real_work_count}}" >{{$real_work_count}}</button> 
                    <button class="btn" id="id_leave_count" data-value="{{$leave_count}}" >{{$leave_count}}</button> 
                    <button class="btn" id="id_overtime_count" data-value="{{$overtime_count}}" >{{$overtime_count}}</button> 
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
                    <td>应出勤情况</td>
                    <td>实际出勤情况</td>
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

                       
                        <td class="plan_do_list">{{@$var["plan_do_str"]}}</td> 
                        <td class="real_do_list">{{@$var["real_do_str"]}}</td> 
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                               <a title="编辑实际出勤信息" class="fa fa-edit opt-edit"></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
 
@endsection

