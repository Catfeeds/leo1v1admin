@extends('layouts.app_new2')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content ">


        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">adminid</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>日期</td>
                    <td>开始</td>
                    <td>结束</td>
                    <td>间隔</td>
                    <!-- <td>异常</td> --> 
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["title"]}} </td>
                        <td>{{@$var["start_logtime_str"]}} </td>
                        <td>{{@$var["end_logtime_str"]}} </td>
                        <td>{{@$var["work_time_str"]}} </td>
                        <!-- <td><font color="red"> {{@$var["error_flag_str"]}}  </font></td> --> 
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
