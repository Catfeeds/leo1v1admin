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
                    <td>试听时间</td>
                    <td>cc</td>
                    <td>学生</td>
                    <td>电话</td>
                    <td>城市</td>
                    <td>科目</td>
                    <td>年级</td>
                    <td>老师</td>
                    <td>渠道</td>
                    <td>转介绍</td>
                    <td>合同金额</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["lesson_start"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        <td>{{@$var["stu_nick"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["phone_location"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["tea_nick"]}} </td>
                        <td>{{@$var["origin"]}} </td>
                        <td>{{@$var["origin_userid"]}} </td>
                        <td>{{@$var["price"]}} </td>
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
