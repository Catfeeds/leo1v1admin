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
                        <span class="input-group-addon">每页行数</span>
                        <select class="opt-change form-control" id="id_page_number" >
                            <option value="10">每页10行</option>
                            <option value="30">每页30行</option>
                            <option value="50">每页50行</option>
                            <option value="100">每页100行</option>
                            <option value="500">每页500行</option>
                            <option value="1000">每页1000行</option>
                            <option value="5000">每页5000行</option>
                        </select>
                    </div>
                </div>


            </div>


        </div>
        <hr/>

        <table   class="common-table"   >
            <thead>
                <tr>

                    <td  >userid</td>
                    <td  >昵称</td>
                    <td  >grade</td>
                    <td  >助教</td>
                    <td >课时数</td> 
                    <td >课时收入</td> 
                    <td >总次数</td> 
                    <td  >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				    <tr>
                        <td >{{$var["userid"]}}</td>
                        <td >{{$var["student_nick"]}}</td>
                        <td >{{$var["grade"]}}</td>
                        <td >{{$var["assistant_nick"]}}</td>
                        <td >{{$var["lesson_count"]/100}}</td>
                        <td >{{@$var["lesson_price"]/100}}</td>
                        <td >{{$var["count"]}}</td>
                        <td >
                            <div 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
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
    
@endsection


