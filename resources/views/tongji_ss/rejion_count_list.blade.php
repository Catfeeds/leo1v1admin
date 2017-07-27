@extends('layouts.app')
@section('content')


    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">渠道</span>
                        <input class="opt-change form-control" id="id_origin_ex" />
                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>重复报名次数 </td>
                    <td>总人数 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["rejoin_count"]}} </td>
                        <td>{{@$var["count"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                                <a class="fa fa-list opt-show" title="明细"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
