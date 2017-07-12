@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <style>
     #id_tbody    .error_money {
         background-color: #f2dede;
     }
    </style>
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-3">
                    <div id="id_date_range">
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">分配给</span>
                        <input class="opt-change form-control" id="id_check_adminid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">已分配</span>
                        <select class="opt-change form-control" id="id_has_check_adminid_flag" >
                        </select>
                    </div>
                </div>
                <div  class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >确认</span>
                        <select id="id_confirm_flag" class="opt-change"     >
                        </select>
                    </div>
                </div>
                <div  class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >支付</span>
                        <select id="id_pay_flag" class="opt-change"   >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-1">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_reset_lesson_count_all"> 重置课时</button>
                    </div>
                </div>
                <div class="col-xs-6 col-md-1">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_show_money_all"> 显示金额</button>
                    </div>
                </div>
                <div class="col-xs-6 col-md-1">
                    <div class="input-group ">
                        <button class="btn btn-warning" id="id_set_check_adminid">检查配置</button>
                    </div>
                </div>
                <div class="col-xs-6 col-md-4">
                    <div id="id_all_lesson_money">
                        @if  ($sum_all_lesson_money > 0 )
                           确认收入:{{$sum_all_lesson_money}}
                        @endif
                    </div>
                    <div id="id_money_info">
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td >老师 </td>
                    <td >检查负责人</td>
                    <td >工资分类/等级</td>
                    <td >所教科目</td>
                    <td >最终信息</td>
                    <td >计算信息</td>
                    <td >课堂收入</td>
                    <td style="display:none;">最终-总课时数</td>
                    <td style="display:none;">最终-1v1总课时数</td>
                    <td style="display:none;">最终-试听课数</td>
                    <td style="display:none;">最终-总金额</td>
                    <td style="display:none;">最终-1v1金额</td>
                    <td style="display:none;">最终-试听金额</td>
                    <td style="display:none;">计算-总课时数</td>
                    <td style="display:none;">计算-1v1总课时数</td>
                    <td style="display:none;">计算-试听课数</td>
                    <td style="display:none;">计算-总金额</td>
                    <td style="display:none;">计算-1v1金额</td>
                    <td style="display:none;">计算-试听金额</td>
                    <td>状态</td>
                    <td>确认信息</td>
                    <td>支付信息</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>
                            <input name="check" type="checkbox" class="opt-select-user" data-teacherid="{{$var["teacherid"]}}" />
                            <br/>
                            {{$var["index"]}} <br/>
                            {{$var["realname"]}}
                        </td>
                        <td>
                            {{$var["check_admin_nick"]}}
                        </td>
                        <td>{{$var["teacher_money_type_str"]}}/{{$var["level_str"]}}</td>
                        <td>{{$var["subject_str"]}}</td>
                        <td>
                            @if ($var["all_lesson_money"]  )
                             总收入: {{$var["all_lesson_money"]}} <br/>
                            @endif
                            总课时: {{$var["real_all_count"]}} <br/>
                            总金额: {{$var["real_money_all_count"]}} <br/>
                        </td>
                        <td >
                            计算课时: {{$var["all_count"]}} <br/>
                            计算金额: <span class="all_info"></span>
                        </td>
                        <td class="lesson_price">
                            课堂收入: {{$var["all_lesson_money"]}}<br/>
                            计算收入: <span class="all_lesson_price"></span>
                        </td>
                        <td>{{$var["real_all_count"]}}</td>
                        <td>{{$var["real_l1v1_count"]}}</td>
                        <td>{{$var["real_test_count"]}}</td>
                        <td>{{$var["real_money_all_count"]}}</td>
                        <td>{{$var["real_money_l1v1_count"]}}</td>
                        <td>{{$var["real_money_test_count"]}}</td>
                        <td>{{$var["all_count"] }} </td>
                        <td>{{$var["l1v1_lesson_count"]}} </td>
                        <td>{{$var["test_lesson_count"]}} </td>
                        <td class="all_money"> </td>
                        <td class="l1v1_money"> </td>
                        <td class="test_money"> </td>
                        <td class="status"> </td>
                        <td >
                            确认人: {{$var["confirm_admin_nick"] }} <br/>
                            是否确认: <font color="{{ $var["confirm_flag"]?"green":"red" }}">
                            {{$var["confirm_flag_str"] }} <br/>
                            </font>
                            确认时间: {{$var["confirm_time"] }} <br/>
                        </td>
                        <td >
                            支付人: {{$var["pay_admin_nick"] }} <br/>
                            是否支付: <font color="{{ $var["pay_flag"]?"green":"red" }}">
                            {{$var["pay_flag_str"] }} <br/>
                            </font>
                            支付时间: {{$var["pay_time"] }} <br/>

                        </td>
                        <td >
                            <div class="opt-div" 
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa opt-show"  title="">明细 </a>
                                <a class="fa opt-tea"   title="">老师</a>
                                <a class="fa opt-money" title="">计算</a>
                                <a class="fa opt-confirm"  title="">确认</a>
                                <a class="fa opt-un_confirm"  title="">取消确认</a>
                                <br/> 
                                <a class="fa opt-pay"  title="">支付</a>
                                <a class="fa opt-un_pay"  title="">取消支付</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection

