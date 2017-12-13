
@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row" >
                <div class="col-xs-6 col-md-3">
                    <div id="id_date_range">
                    </div>
                </div>
                <div clas="col-xs-6 col-md-2">
                    <select id="id_type">
                        <option value="0">全部</option>
                        <option value="1">常规课</option>
                        <option value="2">试听</option>
                    </select>
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
                    <td>老师姓名 </td>
                    <td>迟到5分钟 </td>
                    <td>迟到15分钟 </td>
                    <td>离开20分钟 </td>
                    <td>旷课次数  </td>
                    <td>调课 </td>
                    <td>请假 </td>
                    <td>大单数 </td>
                    <td>预警指数 </td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $info as $var )
                    <tr>
                        <td><a class="opt-detail" data_teacher="{{$var['teacherid']}}" style="cursor:pointer">{{$var["nick"]}}</a> </td>
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
