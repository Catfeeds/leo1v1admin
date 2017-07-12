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
                    <div class="input-group " >
                        <span >总共{{$all_count}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6 col-md-3">
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td>期待日期 </td>
                            <td>个数</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ( $date_list as $var )
                                <tr>
                                    <td>{{@$var["id"]}} </td>
                                    <td>{{@$var["count"]}} </td>
                                </tr>
                            @endforeach
                        </tbody>
                </table>
            </div>
            <div class="col-xs-6 col-md-3">
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td>申请人</td>
                            <td>个数</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ( $require_admin_list as $var )
                                <tr>
                                    <td>{{@$var["id_str"]}} </td>
                                    <td>{{@$var["count"]}} </td>
                                </tr>
                            @endforeach
                        </tbody>
                </table>
            </div>

            <div class="col-xs-6 col-md-3">
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td>科目</td>
                            <td>个数</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ( $subject_list as $var )
                                <tr>
                                    <td>{{@$var["id_str"]}} </td>
                                    <td>{{@$var["count"]}} </td>
                                </tr>
                            @endforeach
                        </tbody>
                </table>
            </div>

            <div class="col-xs-6 col-md-3">
                <table     class="common-table"  >
                    <thead>
                        <tr>
                            <td>年级</td>
                            <td>个数</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ( $grade_list as $var )
                                <tr>
                                    <td>{{@$var["id_str"]}} </td>
                                    <td>{{@$var["count"]}} </td>
                                </tr>
                            @endforeach
                        </tbody>
                </table>
            </div>


        </div>



        @include("layouts.page")
    </section>

@endsection
