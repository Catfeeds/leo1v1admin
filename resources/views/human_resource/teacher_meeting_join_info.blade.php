@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" >
     var g_create_time = "{{@$create_time}}"; 
    </script>


           

    <section class="content ">
        <div>
            <div class="row">
                
                <div class=" col-md-3">
                    <div class="input-group " style="height:35px">
                        <span>会议时间 : {{$create_time_str}}</span>
                    </div>
                </div>

                <div class=" col-md-2">
                    <div class="input-group ">
                        <span>老师</span>
                        <input type="text" id="id_teacherid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >科目</span>
                        <select id="id_subject" class ="opt-change" ></select>
                    </div>
                </div>

                <div class=" col-md-2">
                    <div class="input-group ">
                        <span>老师姓名检索</span>
                        <input type="text" id="id_jhp" />
                    </div>
                </div>
                <div class="col-xs-3 col-md-1">
                    <button class="btn btn-primary" id="id_set_teacher_join_info">录入出席信息</button>
                </div>

 



            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td style="width:10px">
                        <a href="javascript:;" id="id_select_all" title="全选">全</a>
                        <a href="javascript:;" id="id_select_other" title="反选">反</a>
                    </td>
                    <td style="display:none" >会议时间 </td>
                    <td>会议人员</td>
                    <td >出席情况</td>
                    <td >对应系数</td>                  
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td> <input type="checkbox" class="opt-select-item" data-teacherid="{{$var["teacherid"]}}"/>   {{@$var["index"]}} </td>
                        <td>{{$create_time_str}} </td>
                        <td  class="jhp" >{{$var["nick"]}} </td>
                        <td>{{$var["join_info_str"]}}</td>
                        <td>{{$var["xishu"]}}</td>
                       
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                               
                                <a class="fa-edit opt-edit"  title="编辑"> </a>

                               
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection


