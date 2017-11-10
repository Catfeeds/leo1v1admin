@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-3"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">视角</span>
                        <select class="opt-change form-control" id="id_flag" >
                            <option value="1">cc</option>
                            <option value="2">老师</option>
                            <option value="3">渠道</option>
                        </select>

                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否绿色通道</span>
                        <select class="opt-change form-control" id="id_is_green_flag" >
                            <option value="-1">全部</option>
                            <option value="0">是</option>
                            <option value="1">否</option>
                        </select>

                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否下载试卷</span>
                        <select class="opt-change form-control" id="id_is_down" >
                            <option value="-1">全部</option>
                            <option value="0">是</option>
                            <option value="1">否</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">试听年级</span>
                        <input class="opt-change form-control" id="id_grade" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">试听学科</span>
                        <input class="opt-change form-control" id="id_subject" />
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">设备选择</span>
                        <select class="opt-change form-control" id="id_has_pad" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <input class="opt-change form-control" id="id_phone_location" placeholder="手机号归属地，回车搜索"/>
                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>名称</td>
                    <td>获得新例子数</td>
                    <td>试听成功数</td>
                    <td>试听成功率</td>
                    <td>签约成功数</td>
                    <td>转化率</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["stu_count"]}} </td>
                        <td>{{@$var["lesson_succ_count"]}} </td>
                        <td>{{@$var["lesson_succ_rate"]}}% </td>
                        <td>{{@$var["order_count"]}} </td>
                        <td>{{@$var["sign_rate"]}}% </td>
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
