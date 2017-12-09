@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    </script>
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-3">
                    <div id="id_date_range">
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>老师</span>
                        <input class="opt-change" id="id_teacher"/>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>老师类型</span>
                        <select id="id_teacher_type" class="opt-change">
                            <option value="-1">全部</option>
                            <option value="1">全职老师</option>
                            <option value="2">兼职老师</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-1 show_lesson_price" style="display:none">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_get_lesson_price">重置课程收入</button>
                    </div>
                </div>
                <div class="col-xs-6 col-md-8">
                    <div class="input-group">
                        <span class="input-group show_lesson_price">课程收入:</span>
                        <span id="id_lesson_price" class="show_lesson_price">0</span>
                        <span class="input-group">老师总工资</span>
                        <span id="id_teacher_money">{{$all_money}}</span>
                        <span class="input-group">全职老师总工资</span>
                        <span id="id_all_money">{{$all_all_money}}</span>
                        <span class="input-group">兼职老师总工资</span>
                        <span id="id_not_money">{{$all_not_money}}</span>
                        <span class="input-group">税后老师总工资</span>
                        <span id="id_teacher_money_tax">{{$all_money_tax}}</span>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
            <table class="common-table">
                <thead>
                    <tr>
                        <td width="100px">老师id</td>
                        <td width="100px">姓名</td>
                        <td style="display:none">老师类型</td>
                        <td >手机号</td>
                        <td >科目</td>
                        <td style="display:none">持卡人</td>
                        <td style="display:none">身份证</td>
                        <td style="display:none">银行卡</td>
                        <td style="display:none">银行类型</td>
                        <td style="display:none">开户行</td>
                        <td >开户省</td>
                        <td >开户市</td>
                        <td style="display:none">预留手机</td>
                        <td>总工资</td>
                        <td style="display:none">添加时间</td>
                        <td style="display:none">工资发放时间时间</td>
                        <td>操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($table_data_list as $var)
                        <tr>
                            <td>{{$var['teacherid']}}</td>
                            <td>{{$var['realname']}}</td>
                            <td>
                                {{$var['teacher_money_type_str']}}<br>
                                {{$var['teacher_type_str']}}<br>
                            </td>
                            <td>{{$var['phone']}}</td>
                            <td>{{$var['subject_str']}}</td>
                            <td>{{$var['bank_account']}}</td>
                            <td>身份证:{{$var['idcard']}}</td>
                            <td>银行卡:{{$var['bankcard']}}</td>
                            <td>{{$var['bank_type']}}</td>
                            <td>{{$var['bank_address']}}</td>
                            <td>{{$var['bank_province']}}</td>
                            <td>{{$var['bank_city']}}</td>
                            <td>预留手机:{{$var['bank_phone']}}</td>
                            <td>{{$var['money']}}</td>
                            <td>{{$var['add_time']}}</td>
                            <td>{{$var['pay_time']}}</td>
                            <td>
                                <div class="opt-div"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                    <a class="fa fa-list opt-show" title="明细"></a>
                                    <!-- <a class="fa fa-user opt-tea" title="老师"></a> -->
                                    @if($acc == 'ricky' || $acc == 'sunny')
                                        <a class="fa fa-edit opt-edit" title="修改工资发放"></a>
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
