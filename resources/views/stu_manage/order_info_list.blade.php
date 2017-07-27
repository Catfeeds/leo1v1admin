@extends('layouts.stu_header')
@section('content')
    <section class="content ">
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">课堂类型</span>
                    <select id="id_competition_flag" class="opt-change">
                        <option value="0">常规1对1</option>
                        <option value="1">竞赛1对1</option>
                    </select>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td>orderid</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td>合同类型</td>
                    <td>合同状态</td>
                    <td>总课时数</td>
                    <td>剩余课时</td>
                    <td>实付价格</td>
                    <td>原始金额</td>
                    <td>优惠原因</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td> {{$var["orderid"]}} </td>
                        <td> {{$var["grade_str"]}} </td>
                        <td> {{$var["subject_str"]}} </td>
                        <td> {{$var["contract_type_str"]}} </td>
                        <td> {{$var["contract_status_str"]}} </td>
                        <td> {{$var["lesson_total"]}} </td>
                        <td> {{$var["order_left"]}} </td>
                        <td> {{$var["price"]}} </td>
                        <td> {{$var["discount_price"]}} </td>
                        <td> {{$var["discount_reason"]}} </td>
                        <td>
                            <div

                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-list-alt opt-hand-over btn" title="新建交接单"></a>
                                <a class="btn fa fa-th-list opt-hand-over_info" title="交接单详情"></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection
