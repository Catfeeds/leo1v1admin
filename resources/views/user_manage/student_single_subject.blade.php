@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">助教</span>
                        <input class="opt-change form-control" id="id_assistantid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">学生</span>
                        <input class="opt-change form-control" id="id_studentid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">老师</span>
                        <input class="opt-change form-control" id="id_teacherid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">次数</span>
                        <input class="opt-change form-control" id="id_num" />
                    </div>
                </div>
            </div>


        </div>
        <hr/>

        <table   class="common-table"   >
            <thead>
                <tr>
                    <td >#</td>
                    <td >学生</td>
                    <td >电话</td>
                    <td >课时</td>
                    <td >课次</td> 
                    <td >平均课时</td>
                    <td >科目</td>
		    <td >年级</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				    <tr>
                        <td >{{$var["num"]}}</td>
                        <td ><a href="{{url('stu_manage?sid=').$var['userid']}}" target="_blank">{{$var["student_nick"]}}</a></td>
                        <td >{{$var['phone']}}</td>
                        <td >{{$var["lesson_count"]}}</td>
                         <td class="show_detail" date-teacherid="{{$var['teacherid']}}" date-studentid="{{$var['userid']}}" date-subject="{{$var['subject']}}"><a>{{@$var["count"]}}</a></td>
                        <td >{{$var["count_per"]}}</td>
                        <td >{{$var["subject"]}}</td>
			            <td >{{$var["grade"]}}</td>
                        <td >
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
        <div style="display:none;" >
            <div id="id_assign_log">
                <table   class="table table-bordered "   >
                    <tr>  <th> 时间 <th>课时 <th>老师 <th>助教 </tr>
                        <tbody class="data-body">
                        </tbody>
                </table>
            </div>
        </div>
    </section>
    
@endsection


