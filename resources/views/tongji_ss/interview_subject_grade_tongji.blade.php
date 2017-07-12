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
                    <div class="input-group ">
                        <span class="input-group-addon">分类</span>
                        <select class="opt-change form-control" id="id_tongji_type" >
                            <option value="1">科目</option>
                            <option value="2">年级</option>
                        </select>
                    </div>
                </div>
                

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td> </td>
                    <td>面试数</td>
                    <td>面试人数 </td>
                    <td>面试通过数 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>
                            @if($tongji_type==1)
                                {{@$var["subject_str"]}}
                            @else
                                {{@$var["grade_ex_str"]}}
                            @endif
                        </td>
                        <td>{{$var["all_num"]}} </td>
                        <td>{{$var["all_count"]}} </td>
                        <td>{{$var["succ"]}} </td>
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
