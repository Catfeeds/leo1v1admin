@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>

<script type="text/javascript" src="/page_js/seller_student/common.js"></script>

<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
<script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <section class="content">
        <div >

            <div class="row">
                <div class="col-md-2">
                    <div class="input-group">
                        <span>姓名:</span> 
                        <span style="background-color: #fff;">{{$seller_student_info["nick"]}}</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <span>年级:</span> 
                        <span style="background-color: #fff;">{{$seller_student_info["grade_str"]}}</span>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="input-group">
                        <span>科目:</span> 
                        <span style="background-color: #fff;">{{$seller_student_info["subject_str"]}}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <span>上课时间:</span> 
                        <span style="background-color: #fff;">{{$seller_student_info["st_class_time"]}}</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <span>已抢:</span> 
                        <span  style="background-color: #fff;">{{$seller_student_info["assigned_teacher_nick"]}}</span>
                    </div>
                </div>



            </div>

        </div>
        <hr />
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td >teacherid</td>
                        <td >派单者</td>
                        <td >派单时间</td>
                        <td >老师</td>
                        <td >擅长程度</td>
                        <td >老师确认状态</td>
                        <td >老师确认时间</td>
                        <td >是否有微信</td>
                        <td style="display:none;">微信openid</td>
                        <td>操作 </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td>{{$var["teacherid"]}}</td>
                            <td>{{$var["assign_admin_nick"]}}</td>
                            <td>{{$var["assign_time"]}}</td>
                            <td>{{$var["teacher_nick"]}}</td>
                            <td>{{$var["degree_str"]}}</td>
                            <td>{{$var["teacher_confirm_flag_str"]}}</td>
                            <td>{{$var["teacher_confirm_time"]}}</td>
                            <td>{{$var["has_openid_str"]}}</td>
                            <td>{{$var["openid"]}}</td>
                            
                            <td>
                                <div 
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                    <a class="fa-wechat opt-send-webcat " title="微信派单" >  </a>
                                </div>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>

    </section>

@endsection

