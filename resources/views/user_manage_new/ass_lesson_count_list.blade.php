@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-5">
                <div id="id_date_range" >
                </div>
            </div>
        </div>
        <hr/>



        <table   class="common-table"   >
            <thead>
                <tr>

                    <td  >助教id</td>
                    <td  >昵称</td>
                    <td >消耗课时数</td> 
                    <td style="display:none">占周比</td> 
                    <td >上课人数</td> 
                    <td >系数</td> 
                    <td >总次数</td> 
                    <td  >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				    <tr>
                        <td >{{$var["assistantid"]}}</td>
                        <td >{{$var["assistant_nick"]}}</td>
                        <td >{{$var["lesson_count"]/100}}</td>
                        <td >{{$var["week_per"]}}%</td>
                        <td >{{$var["user_count"]}}</td>
                        <td >{{$var["xs"]}}</td>
                        <td >{{$var["count"]}}</td>
                        <td >
                            <div class="btn-group"
                                 data-assistantid="{{$var["assistantid"]}}" ;
                                 >
                                <a class=" fa-list-alt opt-show-lesson-list" title="显示对应课程列表"></a>
                            </div>
                        </td>
				    </tr>
                @endforeach
            </tbody>
        </table>
            @include("layouts.page")
            
    </section>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>


@endsection

