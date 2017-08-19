@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >课程类型</span>
                        <select id="id_lesson_type" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="-2">常规</option>
                            <option value="2">试听</option>
                        </select>
                    </div>
                </div>

               
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td >老师身份 </td>
                    <td> 数量</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["identity_str"]}} </td>
                        <td>{{$var["num"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
