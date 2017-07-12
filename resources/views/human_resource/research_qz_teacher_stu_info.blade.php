@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div id="id_date_range" class="opt-change">
                    </div>
                </div>                

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >角色</span>
                        <select id="id_account_role" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="4">教研</option>
                            <option value="5">全职老师</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>编号</td>
                    <td >学生</td>
                    <td >老师</td>
                    <td >年级</td>
                    <td >总课时</td>
                    <td >剩余课时</td>
                    <td>预计结课时间</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $k=>$var )
                    <tr>
                        <td>{{$k+1}} </td>
                        <td>{{$var["nick"]}} </td>
                        <td>{{$var["realname"]}} </td>
                        <td>{{$var["grade_str"]}} </td>
                        <td>{{$var["assigned_lesson_count"]/100}} </td>
                        <td>{{$var["lesson_left"]/100}} </td>
                        <td>{{@$var["end_day"]}} </td>
                        
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!} >
                               
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

