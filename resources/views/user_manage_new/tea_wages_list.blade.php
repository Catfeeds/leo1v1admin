@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <style>
     #id_tbody .error_money {
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
                        <span>工资类型</span>
                        <select class="opt-change" id="id_teacher_money_type">
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>推荐人</span>
                        <input class="opt-change" id="id_reference"/>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>等级</span>
                        <select class="opt-change" id="id_level">
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>老师平台类型</span>
                        <select class="opt-change" id="id_teacher_ref_type">
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>显示工资</span>
                        <select class="opt-change" id="id_show_data">
                            <option value="0">不显示</opition>
                            <option value="1">显示</opition>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>课程核算类型</span>
                        <select class="opt-change" id="id_show_type">
                            <option value="current">当前消耗</opition>
                            <option value="all">本月所有</opition>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-10">
                    <div class="input-group">
                        @if(in_array($acc,["echo","adrian","ted","jim","michelle","sherry"]))
                            <span class="input-group">课程收入</span>
                            <input id="id_lesson_price" value="{{@$all_lesson_money}}">
                        @endif
                        <span class="input-group">总课时</span>
                        <input class="all_lesson_total" value="{{@$all_lesson_total}}">
                        <span class="input-group">1对1</span>
                        <input class="all_lesson_1v1" value="{{@$all_lesson_1v1}}">
                        <span class="input-group">试听</span>
                        <input class="all_lesson_trial" value="{{@$all_lesson_trial}}">
                        <span class="input-group">课程扣款</span>
                        <input id="id_teacher_cost" value="">
                        <span class="input-group">工资扣税</span>
                        <input id="id_teacher_cost_tax" value="">
                    </div>
                </div>
                <div class="col-xs-12 col-md-11">
                    <div class="input-group">
                        <span class="input-group">老师税前金额</span>
                        <input id="id_teacher_money_tax" value="">
                        <span class="input-group">老师税后金额</span>
                        <input id="id_teacher_money" value="">
                        <span class="input-group">1对1金额</span>
                        <input id="id_teacher_normal" value="">
                        <span class="input-group">试听金额</span>
                        <input id="id_teacher_trial" value="">
                        <span class="input-group">课时奖励</span>
                        <input id="id_teacher_reward" value="">
                        <span class="input-group">荣誉榜</span>
                        <input id="id_teacher_reward_ex" value="">
                    </div>
                </div>
                <div class="col-xs-12 col-md-10">
                    <div class="input-group">
                        <span class="input-group">廖老师工作室</span>
                        <input id="id_teacher_ref_money_1" value="0" >
                        <span class="input-group">王老师工作室</span>
                        <input id="id_teacher_ref_money_2" value="0">
                        <span class="input-group">明日之星</span>
                        <input id="id_teacher_ref_money_3" value="0">
                        <span class="input-group">方超工作室</span>
                        <input id="id_teacher_ref_money_4" value="0">
                        <span class="input-group">王磊工作室</span>
                        <input id="id_teacher_ref_money_5" value="0">
                    </div>
                </div>
                <div class="col-xs-12 col-md-10" style="display:none">
                    <div class="input-group">
                        <span class="input-group">廖老师</span>
                        <input id="id_lesson_ref_money_1" value="0" >
                        <span class="input-group">王老师</span>
                        <input id="id_lesson_ref_money_2" value="0">
                        <span class="input-group">明日之星</span>
                        <input id="id_lesson_ref_money_3" value="0">
                        <span class="input-group">方超</span>
                        <input id="id_lesson_ref_money_4" value="0">
                        <span class="input-group">王磊</span>
                        <input id="id_lesson_ref_money_5" value="0">
                    </div>
                </div>
                <!-- <div class="col-xs-6 col-md-10">
                     <div class="input-group">
                     <span class="input-group">所有老师</span>
                     <input value="{{@$teacher_num}}">
                     <span class="input-group">1对1老师</span>
                     <input value="{{@$teacher_1v1}}">
                     <span class="input-group">试听老师</span>
                     <input value="{{@$teacher_trial}}">
                     <span class="input-group">在读学生</span>
                     <input value="{{@$stu_num}}">
                     </div>
                     </div> -->
                <div class="col-xs-6 col-md-1">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_show_money_all">显示金额</button>
                    </div>
                </div>
                <div class="col-xs-6 col-md-1">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_reset_lesson_count_all"> 重置课时</button>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        @if($show_data==1)
            <table class="common-table"> 
                <thead>
                    <tr>
                        <td>No.</td>
                        <td width="100px">老师id</td>
                        <td width="100px">姓名</td>
                        <td >手机号</td>
                        <td >科目</td>
                        <td style="display:none">持卡人</td>
                        <td style="display:none">身份证</td>
                        <td style="display:none">银行卡</td>
                        <td style="display:none">银行类型</td>
                        <td style="display:none">开户行</td>
                        <td style="display:none">预留手机</td>
                        <td>1对1课时</td>
                        <td>试听课课时</td>
                        <td>总课时</td>
                        <td>计算课时</td>
                        <td>代理工资</td>
                        <td>税前</td>
                        <td>税后</td>
                        <td>状态</td>
                        <td>操作</td>
                    </tr>
                </thead>
                <tbody id="id_tbody">
                    @foreach($table_data_list as $var)
                        <tr>
                            <td>{{$var['id']}}</td>
                            <td>{{$var['teacherid']}}</td>
                            <td>{{$var['tea_nick']}}</td>
                            <td>{{$var['phone']}}</td>
                            <td>{{$var['subject_str']}}</td>
                            <td>{{$var['bank_account']}}</td>
                            <td>身份证:{{$var['idcard']}}</td>
                            <td>银行卡:{{$var['bankcard']}}</td>
                            <td>{{$var['bank_type']}}</td>
                            <td>{{$var['bank_address']}}</td>
                            <td>预留手机:{{$var['bank_phone']}}</td>
                            <td>{{$var['lesson_1v1']}}</td>
                            <td>{{$var['lesson_trial']}}</td>
                            <td>{{$var['lesson_total']}}</td>
                            <td>
                                <span class="lesson_total"></span>
                            </td>
                            <td>
                                <span class="lesson_ref_money"></span>
                            </td>
                            <td>
                                <span class="lesson_price_tax"></span>
                            </td>
                            <td>
                                <span class="lesson_price"></span>
                            </td>
                            <td class="status"></td>
                            <td>
                                <div class="opt-div" 
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                    <a class="fa fa-list opt-show" title="明细"></a>
                                    <a class="fa fa-user opt-tea" title="老师"></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        @include("layouts.page")
    </section>
@endsection
