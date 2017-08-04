@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <section class="content ">

        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">统计项</span>
                        <select class="opt-change form-control" id="id_group_by_field" >
                            <option value="cur_require_adminid">销售</option>
                            <option value="teacherid">老师</option>
                            <option value="origin">渠道</option>
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">cc</span>
                        <input class="opt-change form-control" id="id_cur_require_adminid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">老师</span>
                        <input class="opt-change form-control" id="id_teacherid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">渠道</span>
                        <input class="form-control" id="id_origin_ex" />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>统计项 </td>
                    <td>试听数</td>
                    <td>签单数 </td>
                    <td>转化率</td>
                    <td>合同金额 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["title"]}} </td>
                        <td>{{@$var["test_lesson_count"]}} </td>
                        <td>{{@$var["order_count"]*1}} </td>
                        <td>{{@$var["percent"]}}% </td>
                        <td>{{@$var["order_money"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
