@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
        <div class="right">
            <div class=" helper_teach">
			    <div class="teacher_list">
                    <div class="cont_box">
                        <div class="row">
                            <div class="col-xs-6 col-md-2">
                                <div class="input-group ">
                                    <span class="input-group-addon">年级</span>
                                    <select class="opt-change form-control" id="id_grade" >
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-2">
                                <div class="input-group ">
                                    <span class="input-group-addon">学科</span>
                                    <select class="opt-change form-control " id="id_subject" >
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-2" >
                                <div class="input-group ">
                                    <span >老师</span>
                                    <input id="id_teacherid"  /> 
                                </div>
                            </div>
                            <a  id="id_add_closest" class="btn btn-warning " > <li  class="fa fa-plus">添加教师特长</li> </a>
                        </div>
                    </div>
                    <hr>
                    <div class="cont_box">
                        <!-- <h3>助教查找结果<a href="javascript:;" class="done_s">新增助教</a></h3> -->
                        <div class="cont">
                            <table   class="table table-bordered table-striped"   >
                                <thead>
                                    <tr>
                                        <td class="remove-for-not-xs" ></td>
                                        <td class="remove-for-xs">序号</td>
                                        <td >teacherid</td>
                                        <td >教师姓名</td>
                                        <td >年级</td>
                                        <td >科目</td>
                                        <td class="remove-for-xs">程度</td>
                                        <td class="remove-for-xs">说明</td>
                                        <td class="remove-for-xs">操作</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($table_data_list as $var)
                                        <tr>
                                            @include('layouts.td_xs_opt')
                                            <td class="remove-for-xs">{{$var["number"]}}</td>
                                            <td>{{$var["teacherid"]}}</td>
                                            <td>{{$var["nick"]}}</td>
                                            <td>{{$var["grade_str"]}}</td>
                                            <td>{{$var["subject_str"]}}</td>
                                            <td class="remove-for-xs">{{$var["degree_str"]}}</td>
                                            <td class="remove-for-xs">{{$var["introduction"]}}</td>
                                            <td  >
                                                <div
                                                    {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                                >
                                                    <a href="javascript:;" title="编辑教师信息" class="btn fa fa-edit opt-edit-info"></a>
                                                    <a href="javascript:;" title="删除" class="btn fa fa-trash-o fa-lg done_t"></a>
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @include("layouts.page")
                </div>
            </div>
	    </div>
    </section>
        
        
@endsection
