@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">处理状态</span>
                        <select class="opt-change form-control" id="id_is_modify_time_flag" >
                            <option value="-1">全部</option>
                            <option value="1">已成功</option>
                            <option value="2">拒绝</option>
                            <option value="0">处理中</option>
                        </select>
                    </div>
                </div>


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
                    <td>课程id </td>
                    <td>申请人 </td>
                    <td>申请时间</td>
                    <td>处理状态</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["lessonid"]}} </td>
                        <td>{{@$var["p_name"]}}</td>
                        <td>{{@$var["parent_deal_time"]}} </td>
                        <td>{!!@$var['is_modify_time_flag_str']!!}</td>
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
