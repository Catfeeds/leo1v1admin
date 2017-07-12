@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>


                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">助教试听分类</span>
                        <select class="opt-change form-control" id="id_ass_test_lesson_type" >
                        </select>
                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>助教 </td>
                    <td> 试听数</td>
                    <td> 成功转1v1数</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["title"]}}-{{@$var["require_adminid"]}} </td>
                        <td>{{@$var["count"]}} </td>
                        <td>{{@$var["course_count"]}} </td>
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
