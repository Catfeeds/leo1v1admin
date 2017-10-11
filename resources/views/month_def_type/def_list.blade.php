@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <!-- <span class="input-group-addon">msg</span>
                             <input class="opt-change form-control" id="id_msg" /> -->
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">month_def_type</span>
                        <input class="opt-change form-control" id="id_month_def_type" />
                    </div>
                </div>

                 <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <bttton id="id_add" class="btn btn-primary">添加</button>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>id </td>
                    <td>月定义类型 </td>
                    <td>定义时间 </td>
                    <td>开始时间 </td>
                    <td>结束时间 </td>
                    <td>操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id"]}} </td>
                        <td>{{$var["month_def_type_str"]}} </td>
                        <td>{{$var["def_time"]}} </td>
                        <td>{{$var["start_time"]}} </td>
                        <td>{{$var["end_time"]}} </td>
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
