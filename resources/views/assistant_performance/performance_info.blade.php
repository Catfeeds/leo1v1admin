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
                <div class="col-xs-12 col-md-4">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-12 col-md-5">
                    <div class="input-group ">

                        <span>*8月以后有数据</span>
                    </div>
                </div>

               
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>助教</td>
                    <td>绩效(回访) </td>
                    <td>绩效(课程消耗) </td>
                    <td>绩效(扩课) </td>
                    <td>绩效(停课) </td>
                    <td>绩效(结课未续费) </td>
                    <td>课时消耗奖金</td>
                    <td>续费提成奖金 </td>
                    <td>续费目标 </td>
                    <td>转介绍奖金</td>
                    <td>总计</td>
                       
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $key=>$var )
                    <tr>
                        <td>{{@$var["name"]}}</td> 
                        <td>{{@$var["revisit_reword"]}}</td> 
                        <td>{{@$var["kpi_lesson_count_finish_reword"]}}</td> 
                        <td>{{@$var["kk_reword"]}}</td> 
                        <td>{{@$var["stop_reword"]}}</td> 
                        <td>{{@$var["end_no_renw_reword"]}}</td> 
                        <td>{{@$var["lesson_count_finish_reword"]}}</td> 
                        <td>{{@$var["renw_reword"]}}</td> 
                        <td>{{@$var["renw_target"]/100}}</td> 
                        <td>{{@$var["cc_tran_reword"]}}</td> 
                        <td>{{@$var["all_reword"]}}</td> 

                        <td>
                            <div 
                                 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

