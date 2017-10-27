@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >

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
                    <td>teacherid </td>
                    <td>老师 </td>
                    <td>入职时间 </td>
                    <td>科目 </td>
                    <td>常规课数量 </td>
                    <td>试听课数量 </td>
                    <td>试听得分 </td>
                    <td>教学质量平均分 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["teacherid"]}} </td>
                        <td>{{$var["realname"]}} </td>
                        <td>{{$var["train_through_new_time_str"]}} </td>
                        <td>{{$var["subject_str"]}} </td>
                        <td></td>
                        <td></td>
                        <td></td>
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
