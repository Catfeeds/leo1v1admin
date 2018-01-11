@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script src='/page_js/select_teacher_free_time.js?{{@$_publish_version}}'></script>
    <script src='/page_js/set_lesson_time.js?{{@$_publish_version}}'></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>


    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>
               

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">助教</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>                            
               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">总课时</span>
                        <button btn="btn-warning"  id="id_num" >{{ $total/100 }}</button>
                    </div>
                </div>                            




            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>userid </td>
                    <td>学生姓名</td>
                    <td>常规课时 </td>                  
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["userid"]}} </td>
                        <td>{{@$var["stu_nick"]}} </td>                        

                        <td> <a href="/stu_manage/regular_course_stu?sid={{ @$var["userid"] }}" target="_blank" >{{@$var["regular_total"]/100}} </a></td> 
                        
                       

                        <td>
                            <div class="row-data"  
                                 
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >                               
                                <a class="fa-user opt-user " title="个人信息" ></a>
                                
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

