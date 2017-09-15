@extends('layouts.app')
@section('content')


    <section class="content ">
        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">销售</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">例子赠送类型</span>
                        <select class="opt-change form-control" id="id_seller_new_count_type" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_add">后台赠送 </button>
                    </div>
                </div>




            </div>
        </div>




        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>生成时间 </td>
                    <td>类型</td>
                    <td>销售 </td>
                    <td>可抢</td>
                    <td>已抢</td>
                    <td>剩余</td>
                    <td>生效开始时间</td>
                    <td>生效结束时间</td>
                    <td>说明</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["add_time"]}} </td>
                        <td>{{$var["seller_new_count_type_str"]}} </td>
                        <td>{{$var["admin_nick"]}} </td>
                        <td>{{$var["count"]}} </td>
                        <td>{{$var["get_count"]*1}} </td>
                        <td>{{$var["count"]-$var["get_count"]}} </td>
                        <td>{{$var["start_time"]}} </td>
                        <td>{{$var["end_time"]}} </td>
                        <td>{{$var["value_ex_str"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
