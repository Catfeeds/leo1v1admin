@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>

    <section class="content ">
        <div>
            <div class="row">
                
                <div class="col-xs-12 col-md-5" >
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary" id="id_add_meeting"> 新增会议记录 </button>
                </div>                


            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td style="display:none">id  </td>
                    <td >会议时间 </td>
                    <td >会议地点</td>
                    <td >主持人</td>
                    <td >主题</td>
                    <td >会议纪要</td>
                   
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["id"]}} </td>
                        <td> {{$var["create_time_str"]}} </td>
                        <td>{{$var["address"]}} </td>
                        <td>{{$var["moderator"]}} </td>
                        <td>{{$var["theme"]}} </td>
                        <td>{{$var["summary"]}} </td>
                       
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <!-- <a class="fa-user opt-yuhui"  title="与会老师"> </a> -->
                                <a href="teacher_meeting_join_info?create_time={{$var["create_time"]}}" class="fa-user opt-user-list"  title="点击进入与会老师信息"> </a>
                                <a class="fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa-times opt-del"  title="删除"> </a>

                               
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection


