@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>               
               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2"  >
                    <div class="input-group ">
                        <span class="input-group-addon">学科</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>id </td>
                    <td >真实姓名</td>                  
                    <td>入库时间</td>
                    <td>年级段</td>
                    <td>第一科目</td>
                    <td>离职信息</td>
                    <td>离职时间</td>
                    <td>操作人</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["teacherid"]}} </td>
                        <td>{{$var["realname"]}} </td>
                        <td>{{@$var["create_time_str"]}} </td>
                        <td>
                            @if($var["grade_start"]>0)
                                {{$var["grade_start_str"]}} 至 {{$var["grade_end_str"]}}
                            @else
                                {{$var["grade_part_ex_str"]}}
                            @endif
                        </td>
                        <td>{{$var["subject_str"]}} </td>
                        <td>{{$var["quit_info"]}} </td>
                        <td>{{$var["quit_time_str"]}} </td>
                        <td>{{$var["account"]}} </td>
                        
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!}  >                               


                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

