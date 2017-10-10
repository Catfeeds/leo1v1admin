@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>


                <!-- <div class="col-xs-6 col-md-2">
                     <div class="input-group ">
                     <span class="input-group-addon">grade</span>
                     <input class="opt-change form-control" id="id_grade" />
                     </div>
                     </div>

                     <div class="col-xs-6 col-md-2">
                     <div class="input-group ">
                     <span class="input-group-addon">msg</span>
                     <input class="opt-change form-control" id="id_msg" />
                     </div>
                     </div> -->
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <button id="id_add" class="btn btn-primary"> add </button>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>用户id </td>
                    <td>说明 </td>
                    <td>添加时间 </td>
                    <td>操作人</td>
                    <td>金额[元] </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["agent_id"]}}</td>
                        <td>{{$var["agent_money_ex_type_str"]}} </td>
                        <td>{{$var["add_time"]}} </td>
                        <td>{{$var["account"]}}&#12288;真实姓名：{{$var["name"]}}</td>
                        <td>{{$var["money"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
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

