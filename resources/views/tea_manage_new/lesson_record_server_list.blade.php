@extends('layouts.app')
@section('content')
    <style>
     .table-responsive  {
         overflow-x: unset;
     }

    </style>

    <script type="text/javascript" src="/page_ts/lib/admin_set_select_field.js"></script>

    <section class="content ">

        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">类型</span>
                        <select   class="opt-change form-control" id="id_lesson_type" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <input class="opt-change form-control" id="id_subject" />
                    </div>
                </div>





                <div class="col-xs-12 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">声音服务器</span>
                        <input class="opt-change form-control" id="id_record_audio_server1" />
                    </div>
                </div>

                <div class="col-xs-12 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">xmpp服务器</span>
                        <input class="opt-change form-control" id="id_xmpp_server_name" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_set_select_list">批量分配声音服务器</button>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_set_select_list_xmpp">批量分配xmpp</button>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"    >
            <thead>
                <tr>

                    <td style="width:80px">
                        <a href="javascript:;" id="id_select_all" title="全选">全</a>
                        <a href="javascript:;" id="id_select_other" title="反选">反</a>
                    </td>

                    <td id="id_test" style="min-width:100px;" >

                    </td>

                    <td  style="min-width:100px;" id="th_subject"  > <span> 科目</span> </td>
                    <td class="td-query" data-queryid="id_record_audio_server1"  style="min-width:100px;"   >声音服务器</td>
                    <td class="td-query" data-queryid="id_xmpp_server_name"  style="min-width:100px;"   > xmpp服务器</td>
                    <td  style="min-width:100px;"   >
                        <span>课程类型</span> <a href="javascript:;" class="fa  fa-sort "/> <a href="javascript:;" class="fa fa-filter"/>
                    </td>
                    <td class="td-query" data-queryid="id_date_range"  style="min-width:100px;"   > 上课时间 </td>
                    <td>学生</td>
                    <td>老师</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>

                        <td> <input type="checkbox" class="opt-select-item" data-id="{{@$var["lessonid"]}}"/>   {{@$var["index"]}} </td>
                        <td>{{@$var["lessonid"]}} </td>
                        <td>{{@$var["record_audio_server1"]}} </td>
                        <td>{{@$var["xmpp_server_name"]}} </td>
                        <td>{{@$var["lesson_time"]}} </td>
                        <td>{{@$var["student_nick"]}} </td>
                        <td>{{@$var["teacher_nick"]}} </td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                                <a class="fa  opt-lesson" title=""> 课程 </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
