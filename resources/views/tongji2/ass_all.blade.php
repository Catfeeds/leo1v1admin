@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div  class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td style="width:200px">项目 </td>
                    <td> 数值 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["field_name"]}} </td>
                        <td>{{$var["value"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-list opt-desc"  title="明细"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
