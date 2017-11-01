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
                        <span>工资类型</span>
                        <select class="opt-change" id="id_teacher_money_type">
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span>老师身份</span>
                        <select class="opt-change" id="id_teacher_type">
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
                @if(in_array($acc,["echo","adrian","ted","jim","michelle","sherry"]))
                <div class="col-xs-6 col-md-10">
                    <div class="input-group">
                            <span class="input-group">课程收入</span>
                            <input id="id_lesson_price" value="0">
                    </div>
                </div>
                @endif
                <div class="col-xs-12 col-md-11">
                    <div class="input-group">
                        <span class="input-group">老师税前金额</span>
                        <input id="id_teacher_money_tax" value="">
                        <span class="input-group">老师税后金额</span>
                        <input id="id_teacher_money" value="">
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
                    </div>
                </div>
            </div>
        </div>
        <hr/>
            <table class="common-table"> 
                <thead>
                    <tr>
                        <td>操作</td>
                    </tr>
                </thead>
                <tbody id="id_tbody">
                    @foreach($table_data_list as $var)
                        <tr>
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
