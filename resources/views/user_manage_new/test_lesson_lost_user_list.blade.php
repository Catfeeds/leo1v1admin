@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" >
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>试听时间 </td>
                    <td>昵称 </td>
                    <td>年级</td>
                    <td>性别</td>
                    <td>电话</td>
                    <td>资源获得者</td>
                    <td>资源获得时间</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["lesson_start"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["gender_str"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["last_revisit_admin_nick"]}} </td>
                        <td>{{@$var["last_revisit_admin_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-user opt-user " title="个人信息" ></a>
                                <a class="fa-comments opt-return-back-list " title="回访列表" ></a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

