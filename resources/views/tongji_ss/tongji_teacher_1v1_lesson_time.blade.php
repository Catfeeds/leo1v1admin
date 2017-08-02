@extends('layouts.app')
@section('content')
    <script src='/page_js/select_teacher_free_time.js?{{@$_publish_version}}'></script>
    <script src='/page_js/set_lesson_time.js?{{@$_publish_version}}'></script>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <script type="text/javascript" src="/page_js/seller_student/common.js?v=121"></script>

    <script type="text/javascript" src="/page_js/lib/select_dlg.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
       
  
    <section class="content ">
        
        <div>
            <div class="row">
                
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead >
                <tr>
                    <td>老师</td>
                    <td>0618</td>
                    <td>0619</td>
                    <td>0625</td>
                    <td>0626</td>
                    <td>0702</td>
                    <td>0703</td>
                    <td>0709</td>
                    <td>0710</td>
                    <td>0716</td>
                    <td>0717</td>
                    <td>0723</td>
                    <td>0724</td>
                    <td>0730</td>
                    <td>0731</td>
                    <td>星期一</td>
                    <td>星期天</td>
                    <td>合计</td>
                    <td>平时加班</td>
                    <td>总计</td>
                   
                   
                                                  
                    <td>操作 </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $k=>$var )
                    <tr>                      
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["20170618"]}}</td>
                        <td>{{@$var["20170619"]}}</td>
                        <td>{{@$var["20170625"]}}</td>
                        <td>{{@$var["20170626"]}}</td>
                        <td>{{@$var["20170702"]}}</td>
                        <td>{{@$var["20170703"]}}</td>
                        <td>{{@$var["20170709"]}}</td>
                        <td>{{@$var["20170710"]}}</td>
                        <td>{{@$var["20170716"]}}</td>
                        <td>{{@$var["20170717"]}}</td>
                        <td>{{@$var["20170723"]}}</td>
                        <td>{{@$var["20170724"]}}</td>
                        <td>{{@$var["20170730"]}}</td>
                        <td>{{@$var["20170731"]}}</td>
                        <td>
                            @if(!empty($var["one_day"]))
                                {{@$var["one_day"]}}&nbsp&nbsp/&nbsp&nbsp{{@$var["one_hour"]}}
                            @endif
                        </td>
                        <td>
                            @if(!empty($var["seven_day"]))
                                {{@$var["seven_day"]}}&nbsp&nbsp/&nbsp&nbsp{{@$var["seven_hour"]}}
                            @endif
                        </td>
                        <td>
                            @if(!empty($var["all_day"]))
                                {{@$var["all_day"]}}&nbsp&nbsp/&nbsp&nbsp{{@$var["all_hour"]}}
                            @endif
                        </td>
                        <td>
                            @if(!empty($var["late_day"]))
                                {{@$var["late_day"]}}&nbsp&nbsp/&nbsp&nbsp{{@$var["late_hour"]}}
                            @endif
                        </td>
                        <td>
                            @if(!empty($var["total_day"]))
                                {{@$var["total_day"]}}&nbsp&nbsp/&nbsp&nbsp{{@$var["total_hour"]}}
                            @endif
                        </td>


                       
                                        
                        <td>
                            <div class="row-data"
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

