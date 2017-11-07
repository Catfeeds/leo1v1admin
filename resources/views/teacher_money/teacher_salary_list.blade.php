@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-3">
                    <div id="id_date_range">
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>推荐人</span>
                        <input class="opt-change" id="id_reference"/>
                    </div>
                </div>
                <div class="col-xs-6 col-md-10">
                    <div class="input-group">
                        <span class="input-group">课程收入</span>
                        <input id="id_lesson_price" value="0">
                        <span class="input-group">老师总工资</span>
                        <input id="id_teacher_money_tax" value="">
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
                        <td >手机号</td>
                        <td style="display:none">持卡人</td>
                        <td style="display:none">身份证</td>
                        <td style="display:none">银行卡</td>
                        <td style="display:none">银行类型</td>
                        <td style="display:none">开户行</td>
                        <td style="display:none">预留手机</td>
                        <td>总工资</td>
                        <td>操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($table_data_list as $var)
                        <tr>
                            <td>{{$var['teacherid']}}</td>
                            <td>{{$var['realname']}}</td>
                            <td>{{$var['phone']}}</td>
                            <td>{{$var['bank_account']}}</td>
                            <td>身份证:{{$var['idcard']}}</td>
                            <td>银行卡:{{$var['bankcard']}}</td>
                            <td>{{$var['bank_type']}}</td>
                            <td>{{$var['bank_address']}}</td>
                            <td>预留手机:{{$var['bank_phone']}}</td>
                            <td>{{$var['money']}}</td>
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
        @include("layouts.page")
    </section>
@endsection
