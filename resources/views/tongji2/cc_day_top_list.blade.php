@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-12 col-md-3"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div  class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <button id="id_add" class="btn btn-primary">add</button>
                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table  class="common-table"  >
            <thead>
                <tr>
                    <td>uid</td>
                    <td>cc名称</td>
                    <td>业绩值</td>
                    <td>排名</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["uid"]}} </td>
                        <td>{{$var["account"]}} </td>
                        <td>{{$var["score"]}} </td>
                        <td>{{$var["rank"]}}</td>
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
