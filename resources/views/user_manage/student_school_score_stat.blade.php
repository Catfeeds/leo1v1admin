@extends('layouts.app')
@section('content')
    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
            <div class="col-md-3 col-xs-0" data-always_show="1">
                <div class="input-group col-sm-12"  >
                    <input  id="id_username" type="text" value="" class="form-control opt-change"  placeholder="输入学生名，回车查找" />
                </div>
            </div>

                <div class="col-md-2 col-xs-0">
                    <div class="input-group ">
                        <span>年级</span>
                        <select class="opt-change" id="id_grade">
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-xs-0">
                    <div class="input-group ">
                        <span>学期</span>
                        <select class="opt-change" id="id_semester">
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-xs-0">
                    <div class="input-group ">
                        <span>类型</span>
                        <select class="opt-change" id="id_stu_score_type">
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>学生姓名</td>
                    <td>年级</td>
                    <td>学期</td>
                    <td>类型</td>
                    <td>时间</td>
                    <td>语文</td>
                    <td>数学</td>
                    <td>英语</td>
                    <td>化学</td>
                    <td>物理</td>
                    <td>生物</td>
                    <td>政治</td>
                    <td>历史</td>
                    <td>地理</td>
                    <td>科学</td>
                    {!!\App\Helper\Utils::th_order_gen([
                        ["班级排名","rank" ],
                        ["年级排名","grade_rank" ],
                        ["进步次数","rank_up" ],
                        ["退步次数","rank_down" ],
                       ])  !!}
                    <td>学校</td>
                    <td>录入者</td>
                    <td>详情</td>
                    <td></td>
                     
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td><a href="{{url('stu_manage?sid=').$var['userid']}}" target="_blank">{{@$var["nick"]}}</a></td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["semester_str"]}} </td>
                        <td>{{@$var["stu_score_type_str"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        @for($i=1; $i <@11; $i++)
                            @if($var["subject"] == $i)
                                    <td>{{$var["score"]/10}}</td>
                            @else
                                <td></td>
                            @endif
                        @endfor
                        <td>{{@$var["rank"]}} </td>
                        <td>{{@$var["grade_rank"]}} </td>
                        <td>{{@$var["rank_up"]}} </td>
                        <td>{{@$var["rank_down"]}} </td>
                        <td>{{@$var["school"]}} </td>
                        <td>{!!@$var["create_admin_nick"]!!} </td>
                        <td><a href="{{url('stu_manage/score_list?sid=').$var['userid']}}" target="_blank">详情</a></td>
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


