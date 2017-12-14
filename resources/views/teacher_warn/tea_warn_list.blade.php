
@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row" >
                <div class="col-xs-6 col-md-3">
                    <div id="id_date_range">
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group">
                        <span>课程类型</span>
                        <select id="id_course_type" class="opt-change" >
                            <option value="0">[全部]</option>
                            <option value="1">常规课</option>
                            <option value="2">试听课</option>
                        </select>
                    </div>
                </div>


                <!-- <div class="col-xs-6 col-md-2">
                     <div class="input-group ">
                     <span>老师</span>
                     <input class="opt-change" id="id_teacher"/>
                     </div>
                     </div> -->
                
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    @if($course_type == 1)
                    <td>老师姓名 </td>
                    <td>常规上课次数</td>
                    <td>常规迟到5分钟 </td>
                    <td>常规迟到15分钟 </td>
                    <td>常规离开20分钟 </td>
                    <td>常规旷课次数  </td>
                    <td>常规调课次数 </td>
                    <td>常规请假次数 </td>
                    <td>常规大单数 </td>
                    <td>预警指数 </td>
                    <td>操作</td>
                    @elseif ($course_type == 2)
                    <td>老师姓名 </td>
                    <td>试听上课次数</td>
                    <td>试听迟到5分钟 </td>
                    <td>试听迟到15分钟 </td>
                    <td>试听离开20分钟 </td>
                    <td>试听旷课次数  </td>
                    <td>试听调课次数 </td>
                    <td>试听请假次数 </td>
                    <td>试听大单数 </td>
                    <td>预警指数 </td>
                    <td>操作</td>
                    @else
                    <td>老师姓名 </td>
                    <td>上课次数</td>
                    <td>迟到5分钟 </td>
                    <td>迟到15分钟 </td>
                    <td>离开20分钟 </td>
                    <td>旷课次数  </td>
                    <td>调课 </td>
                    <td>请假 </td>
                    <td>大单数 </td>
                    <td>预警指数 </td>
                    <td>操作</td>

                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ( $info as $var )
                    <tr>
                        <td><a class="opt-detail" data_teacher="{{$var['teacherid']}}" style="cursor:pointer">{{$var["nick"]}}</a> </td>
                        <td>{{$var['lesson_num']}}</td>
                        <td>{{$var['five_num']}}</td>
                        <td>{{$var['fift_num']}}</td>
                        <td>{{$var['leave_num']}}</td>
                        <td>{{$var['absent_num']}}</td>
                        <td>{{$var['adjust_num']}}</td>
                        <td>{{$var['ask_leave_num']}}</td>
                        <td>{{$var['big_order_num']}}</td>
                        <td>{{$var['all']}}</td>
                        <td data_teacher="{{$var['teacherid']}}">
                            <a class="fa-phone opt-telphone" title="拨打老师电话"></a>
                            <a class="fa-comment opt-return-back " title="回访" ></a>
                            <a class="fa-comments opt-return-back-list " title="回访列表" ></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
