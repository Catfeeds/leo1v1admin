@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
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
                    <td style="display:none;">是否离职 </td>
                    <td >是否当月离职 </td>
                    <td>
                        第一周试听成功数
                        ({{$first_week}})
                    </td>
                    <td>
                        第二周试听成功数
                        ({{$second_week}})
                    </td>
                    <td>
                        第三周试听成功数
                        ({{$third_week}})
                    </td>
                    <td>
                        第四周试听成功数
                        ({{$four_week}})
                    </td>
                    <td>上课成功数</td>
                    <td>上课成功人数</td>
                    <td>上课取消数</td>
                    <td>上课数</td>
                    <td>取消率</td>
                    <td>绩效对应系数<br/>(取消率<=18%(40%)+周试听成功>=12节(15%))</td>
                    <td >签约总金额 </td>
                    <td>上月签单 </td>
                    <td>月末定级 </td>
                    <td >基本工资 </td>
                    <td >保密津贴 </td>
                    <td >绩效工资 </td>
                    <td style="display:none;">应得绩效工资 </td>
                    <td style="display:none;">分期金额 </td>
                    <td style="display:none;">非分期金额 </td>
                    <td style="display:none;">一天内签约金额 </td>
                    <td >团队上月签约金额</td>
                    <td >团队签约金额</td>
                    <td style="display:none;">团队签约分期金额</td>
                    <td style="display:none;">团队签约非分期金额</td>
                    <td >团队签约目标</td>
                    <td >特殊申请金额</td>

                    <td style="display:none;"  >正常签约金额 </td>
                    <td  style="display:none;" > 单纯 特殊申请  </td>
                    <td style="display:none;" > 单纯 一天内 签约金额 </td>
                    <td style="display:none;" >特殊申请 并且一天内 签约金额 </td>

                    <td >提成点</td>
                    <td >主管提成点</td>
                    <td style="display:none;">新员工提成系数</td>
                    <td >提成金额</td>
                    <td >单月提成金额</td>
                    <td >季度提成金额</td>
                    <td >计算方式</td>
                    <td style="display:none;">团队提成金额(主管)</td>
                    <td style="display:none;">团队达标提成金额</td>
                    <td style="display:none;">团队实际上课数</td>
                    <td style="display:none;">团队试听取消率</td>
                    <td style="display:none;">团队试听转化率</td>
                    <td style="display:none;">团队人员离职率</td>
                    <td >主管kpi</td>
                    <td >主管kpi计算方式<br/>(实际上课数>=50(10%)+试听取消率<=18%(10%)+试听转化率>=10%(40%)+离职率<=20%(40%))</td>
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
                        <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}} "  >{{$var["account"]}}
                            @if(isset($var['seller_level']))
                                /{{$var["seller_level_str"]}}
                            @endif
                        </td>
                        <td >{{@$var["become_member_time"]}}</td>
                        <td >{{@$var["leave_member_time"]}}</td>
                        <td>{!! @$var["del_flag_str"] !!}</td>
                        <td class="cur_del_flag_str"></td>
                        <td class="suc_first_week"></td>
                        <td class="suc_second_week"></td>
                        <td class="suc_third_week"></td>
                        <td class="suc_fourth_week"></td>
                        <td class="suc_all_count"></td>
                        <td class="dis_suc_all_count"></td>
                        <td class="fail_all_count"></td>
                        <td class="test_lesson_count"></td>
                        <td class="lesson_per"></td>
                        <td class="kpi"></td>
                        <td class="all_price" ></td>
                        <td class="last_all_price" ></td>
                        <td class="last_seller_level"></td>
                        <td class="base_salary" ></td>
                        <td class="sup_salary" ></td>
                        <td class="per_salary" ></td>
                        <td class="get_per_salary" ></td>
                        <td class="stage_money" ></td>
                        <td class="no_stage_money" ></td>
                        <td class="24_hour_all_price"></td>
                        <td class="last_group_all_price"  ></td>
                        <td class="group_all_price"  ></td>
                        <td class="group_all_stage_price"  ></td>
                        <td class="group_all_no_stage_price"  ></td>
                        <td class="group_default_money"  ></td>
                        <td class="require_all_price" ></td>
                        <td class="all_price_1" ></td>
                        <td class="require_all_price_1" ></td>
                        <td class="v24_hour_all_price_1" ></td>
                        <td class="require_and_24_hour_price_1" ></td>

                        <td class="percent" ></td>
                        <td class="group_money_add_percent" ></td>
                        <td class="new_account_value" ></td>
                        <td class="money" ></td>
                        <td class="cur_month_money" ></td>
                        <td class="three_month_money" ></td>
                        <td class="desc" ></td>
                        <td class="group_master_money" ></td>
                        <td class="group_self_money" ></td>
                        <td class="group_month_avg_lesson" ></td>
                        <td class="group_month_avg_lesson_per" ></td>
                        <td class="group_month_avg_order_per" ></td>
                        <td class="group_month_avg_leave_per" ></td>
                        <td class="group_kpi" ></td>
                        <td class="group_kpi_desc" ></td>
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
