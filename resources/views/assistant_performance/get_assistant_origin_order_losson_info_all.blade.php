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
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >助教</span>
                        <input id="id_assistantid"  /> 
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">下单人</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>

               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">转介绍</span>
                        <input class="opt-change form-control" id="id_origin_userid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >学生</span>
                        <input id="id_studentid"  />
                    </div>
                </div>                               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">老师</span>
                        <input class="opt-change form-control" id="id_teacherid" />
                    </div>
                </div>


                <div class="col-xs-6 col-md-2" data-always_hide="1">
                    <div class="input-group ">
                        <input id="id_sys_operator"  class="opt-change" placeholder="下单人,回车搜索" />
                    </div>
                </div>
               

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>添加时间 </td>
                    <td>转介绍助教 </td>
                    <td>学生 </td>
                    <td>电话号码 </td>
                    <td>年级 </td>
                    <td>科目 </td>
                    <td>负责人 </td>
                    <td>任课老师 </td>
                    <td>签单金额 </td>
                    <td>下单人 </td>
                    <td>下单时间 </td>
                    <td>确认时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["add_time_str"]}} </td>
                        <td>{{@$var["name"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["phone_ex"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["ass_name"]}} </td>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["price"]/100}} </td>
                        <td>{{@$var["sys_operator"]}} </td>
                        <td>{{@$var["order_time_str"]}} </td>
                        <td>{{@$var["pay_time_str"]}} </td>
                       

                        <td>
                            <div 
                                
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                @if(@$var["lessonid"]>0)
                                    <a class="opt-get-stu-comment">老师评价</a> 
                                    <a class="opt-play-new" title="回放-new">回放-new</a>
                                @endif
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

