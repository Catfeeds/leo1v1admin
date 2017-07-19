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
                        <span class="input-group-addon">分类</span>
                        <input class="opt-change form-control" id="id_todo_type" />
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">状态</span>
                        <input class="opt-change form-control" id="id_todo_status" />
                    </div>
                </div>
                 <div class="col-xs-6 col-md-2">
                    <button class="btn btn-warning" id="id_self_todo_new">增加回访</button>
            </div>


            </div>
          
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td> 分类 </td>
                    <td> 开始时间</td>
                    <td>结束时间</td>
                    <td> 信息 </td>
                    <td> 状态 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["todo_type_str"]}} </td>
                        <td>{{@$var["start_time"]}} </td>
                        <td>{{@$var["end_time"]}} </td>
                        <td>{{@$var["msg"]}} </td>
                        <td>{!! @$var["todo_status_str"] !!} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-share-square-o   opt-jump"  title="跳转"> </a>
                                <a class="fa fa-refresh    opt-reset"  title="刷新状态"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
