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
                    <td>时段 </td>
                    <td>总数 </td>
                    @foreach ($diff_title_list as $item )
                    <td>{{$item}} </td>
                    @endforeach
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["title"]}} </td>
                        <td>{{$var["v_all_count"]}} </td>

                        @foreach ($diff_list as $item )
                                <td>{{$var["v_".$item]}} </td>
                        @endforeach

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
