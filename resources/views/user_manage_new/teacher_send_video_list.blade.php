@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
	<script type="text/javascript" src="/source/jquery.fancybox.js"></script>
 	<link rel="stylesheet" type="text/css" href="/source/jquery.fancybox.css" media="screen" />


    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>               
                
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >科目</span>
                        <select id="id_subject" class ="opt-change" ></select>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td> 推送时间 </td>
                    <td> 科目  </td>
                    <td> 年级 </td>
                    <td> 老师 </td>
                    <td> 推荐理由 </td>
                    <td style="display:none"> 视频地址 </td>
                    <td> 推送的老师个数 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["send_time_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>                        
                        <td>{{@$var["teacher"]}} </td>                        
                        <td>{{@$var["send_reason"]}} </td>                        
                        <td>{{@$var["url"]}} </td>
                        <td>{{@$var["tea_num"]}} </td>

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

