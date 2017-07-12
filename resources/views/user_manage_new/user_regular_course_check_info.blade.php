@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">助教</span>
                        <input class="opt-change form-control" id="id_assistantid"/>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">学生</span>
                        <input class="opt-change form-control" id="id_userid"/>
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">老师</span>
                        <input class="opt-change form-control" id="id_teacherid"/>
                    </div>
                </div>

                             
               
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>学生 </td>
                    <td>老师</td>
                    <td>助教</td>
                    <td>常规课时间</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        <td>{{@$var["lesson_start_str"]}} </td>
                        
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                               
                                <a class="fa-user opt-user " title="学生个人信息" ></a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

