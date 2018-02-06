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
     table > thead > tr > th,table > thead > tr >td, table > tbody > tr > th,table > tbody > tr >td{ word-break: break-all;}
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
                    <!-- <td >日均通时</td>
                         <td>日均呼出</td>
                         <td>月呼出量</td>
                         <td>日均邀约</td> -->
                    <td>试听申请数</td>
                    <td>教务排课数</td>
                    <td>学生上课数</td>
                    <td>试听成功数</td>
                    <td>试听成功人数</td>
                    <td>第一周试听成功数</td>
                    <td>第二周试听成功数</td>
                    <td>第三周试听成功数</td>
                    <td>第四周试听成功数</td>
                    <td>取消人数</td>
                    <td>取消率</td>
                    <td>绩效对应系数</td>
                    <td>签约人数</td>
                    <td>签约率</td>
                    <td>签约金额</td>
                    <td style="display:none;">单笔</td>
                    <td>团队业绩指标</td>
                    <td>团队指标完成率</td>
                    <td>团队缺口金额</td>
                    <td>在职人数</td>
                    <td>离职人数</td>
                    <td style="display:none;">销售自定指标</td>
                    <td style="display:none;"> 自定指标完成率</td>
                    <td style="display:none;">自定指标缺口金额</td>
                    <td style="display:none;"> 应工作天数</td>
                    <td style="display:none;">实际工作天数</td>
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
                        <!-- <td style=" width:80px" >{{@$var["duration_count_for_day"]}}</td>
                             <td >{{@$var["is_called_phone_count_for_day"]}}</td>
                             <td >{{@$var["is_called_phone_count_for_month"]}}</td>
                             <td >{{@$var["require_test_count_for_day"]}}</td> -->
                        <td >{{@$var["require_test_count_for_month"]}}</td>
                        <td >{{@$var["test_lesson_count_for_month"]}}</td>
                        <td class="test_lesson_count"></td>
                        <td class="succ_all_count_for_month"></td>
                        <td class="dis_succ_all_count_for_month"></td>
                        <td class="suc_lesson_count_one"></td>
                        <td class="suc_lesson_count_two"></td>
                        <td class="suc_lesson_count_three"></td>
                        <td class="suc_lesson_count_four"></td>
                        <td class="fail_all_count_for_month"></td>
                        <td class="lesson_per"></td>
                        <td class="kpi"></td>
                        <td class="success_all_count_for_month">{{@$var["all_new_contract_for_month"]}}</td>
                        <td class="order_per"></td>
                        <td >{{@$var["all_price_for_month"]}}</td>
                        <td >{{@$var["ave_price_for_month"]}}</td>
                        <td >{{@$var["target_money"]}}</td>
                        <td >{{@$var["finish_per"]}}</td>
                        <td >{{@$var["los_money"]}}</td>
                        <td class="at_job">{{@$var["become_member_num"]}}</td>
                        <td class="leave_job">{{@$var["leave_member_num"]}}</td>
                        <td >{{@$var["target_personal_money"]}}</td>
                        <td >{{@$var["finish_personal_per"]}}</td>
                        <td >{{@$var["los_personal_money"]}}</td>
                        <td >{{@$var["month_work_day_now"]}}</td>
                        <td >{{@$var["month_work_day_now_real"]}}</td>
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
