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
    <style>
     .panel-heading {
         font-size:20px;
         text-align:center;
     }
    </style>

  
    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span class="input-group-addon">类型</span>
                        <select class="opt-change form-control" id="id_type_flag" >
                            <option value="1">个人</option>
                            <option value="2">学科</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary" id="id_read_rule"> 查看评分规则 </button>
                </div>                


                
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead >
                <tr>
                    <td></td>
                    <td>审核时长</td>
                    <td>审核数量</td>
                    <td>面试试听课数</td>
                    <td>面试签单数</td>
                    <td>面试签单率</td>
                    <td>面试试听课数(上月)</td>
                    <td>面试签单数(上月)</td>
                    <td>面试签单率(上月)</td>
                    <td>新入职反馈时长</td>
                    <td>反馈数量</td>
                    <td>首次试听课数</td>
                    <td>首次试听签单数</td>
                    <td>首次试听转化率</td>
                    <td>首次试听课数(上月)</td>
                    <td>首次试听签单数(上月)</td>
                    <td>首次试听转化率(上月)</td>
                    <td>反馈前</td>
                    <td>反馈后</td>
                    <td>反馈后转化率提升度</td>
                    <td>反馈前(上月)</td>
                    <td>反馈后(上月)</td>
                    <td>反馈后转化率提升度(上月)</td>
                    <td>投诉处理时长</td>
                    <td>试听成功数(销售)</td>
                    <td>试听成功数(销售)-占比</td>
                    <td>签单率</td>
                    <td>签单率(转介绍)</td>
                    <td>签单率(扩课)</td>
                    <td>签单率(换老师)</td>
                                                  
                    <td>操作 </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>
                            @if($type_flag==1)
                                {{@$var["account"]}}
                            @else
                                {{@$var["subject_str"]}}
                            @endif
                        </td>
                        <td>{{@$var["interview_time_avg"]}}天</td>
                        <td>{{@$var["interview_num"]}}</td>
                        <td>{{@$var["interview_lesson"]}}</td>
                        <td>{{@$var["interview_order"]}}</td>
                        <td>{{@$var["interview_per"]}}%</td>
                        <td>{{@$var["last_interview_lesson"]}}</td>
                        <td>{{@$var["last_interview_order"]}}</td>
                        <td>{{@$var["last_interview_per"]}}%</td>
                        <td>{{@$var["record_time_avg"]}}天</td>
                        <td>{{@$var["record_num_all"]}}</td>
                        <td>{{@$var["first_lesson"]}}</td>
                        <td>{{@$var["first_order"]}}</td>
                        <td>{{@$var["first_per"]}}%</td>
                        <td>{{@$var["last_first_lesson"]}}</td>
                        <td>{{@$var["last_first_order"]}}</td>
                        <td>{{@$var["last_first_per"]}}%</td>
                        <td>{{@$var["first_next_per"]}}%</td>
                        <td>{{@$var["next_per"]}}%</td>
                        <td>{{@$var["add_per"]}}%</td>
                        <td>{{@$var["last_first_next_per"]}}%</td>
                        <td>{{@$var["last_next_per"]}}%</td>
                        <td>{{@$var["last_add_per"]}}%</td>
                        <td>{{@$var["other_record_time_avg"]}}天</td>
                        <td>{{@$var["lesson_num"]}}</td>
                        <td>{{@$var["lesson_num_per"]}}%</td>
                        <td>{{@$var["lesson_per"]}}%</td>
                        <td>{{@$var["lesson_per_other"]}}%</td>
                        <td>{{@$var["lesson_per_kk"]}}%</td>
                        <td>{{@$var["lesson_per_change"]}}%</td>
                                                                
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

        <hr>
        @if($type_flag==1)
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        个人KPI
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>教研</td>
                                    <td>审核时长</td>
                                    <td>面试签单率</td>
                                    <td>新入职反馈时长</td>
                                    <td>反馈数量</td>
                                    <td>首次试听转化率</td>
                                    <td>反馈后转化率提升度</td>
                                    <td>投诉处理时长</td>
                                    <td>试听成功数(销售)-占比</td>
                                    <td>签单率</td>
                                    <td>签单率(转介绍)</td>
                                    <td>签单率(扩课)</td>
                                    <td>签单率(换老师)</td>
                                    <td>总分</td>

                                </tr>
                            </thead>
                            <tbody id="id_score_list">
                                @foreach ( $score_list as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td>{{@$var["account"]}}</td>
                                        <td class="five_score">{{ @$var["interview_time_score"] }}</td>
                                        <td class="twenty_five_score">{{ @$var["interview_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["record_time_score"] }}</td>
                                        <td class="five_score">{{ @$var["record_num_score"] }}</td>
                                        <td class="five_score">{{ @$var["first_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["add_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["other_record_time_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_num_per_score"] }}</td>
                                        <td class="twenty_five_score">{{ @$var["lesson_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_other_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_kk_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_change_score"] }}</td>
                                        <td>{{ @$var["total_score"] }}</td>                                        
                                      
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            
        </div>
        @else
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        团队KPI
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>学科</td>
                                    <td>审核时长</td>
                                    <td>面试签单率</td>
                                    <td>新入职反馈时长</td>
                                    <td>反馈数量</td>
                                    <td>首次试听转化率</td>
                                    <td>反馈后转化率提升度</td>
                                    <td>投诉处理时长</td>
                                    <td>试听成功数(销售)-占比</td>
                                    <td>签单率</td>
                                    <td>签单率(转介绍)</td>
                                    <td>签单率(扩课)</td>
                                    <td>签单率(换老师)</td>
                                    <td>总分</td>

                                </tr>
                            </thead>
                            <tbody id="id_group_score_list">
                                @foreach ( $group_score_list as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td>{{@$var["subject_str"]}}</td>
                                        <td class="five_score">{{ @$var["interview_time_score"] }}</td>
                                        <td class="twenty_five_score">{{ @$var["interview_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["record_time_score"] }}</td>
                                        <td class="five_score">{{ @$var["record_num_score"] }}</td>
                                        <td class="five_score">{{ @$var["first_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["add_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["other_record_time_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_num_per_score"] }}</td>
                                        <td class="twenty_five_score">{{ @$var["lesson_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_other_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_kk_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_change_score"] }}</td>
                                        <td>{{ @$var["total_score"] }}</td>                                        

                                        
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="col-xs-12 col-md-12">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        学科KPI
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>排名</td>
                                    <td>学科</td>
                                    <td>审核时长</td>
                                    <td>面试签单率</td>
                                    <td>新入职反馈时长</td>
                                    <td>反馈数量</td>
                                    <td>首次试听转化率</td>
                                    <td>反馈后转化率提升度</td>
                                    <td>投诉处理时长</td>
                                    <td>试听成功数(销售)-占比</td>
                                    <td>签单率</td>
                                    <td>签单率(转介绍)</td>
                                    <td>签单率(扩课)</td>
                                    <td>签单率(换老师)</td>
                                    <td>总分</td>

                                </tr>
                            </thead>
                            <tbody id="id_score_list">
                                @foreach ( $score_list as $key=> $var )
                                    <tr>
                                        <td> <span> {{$key+1}} </span> </td>
                                        <td>{{@$var["subject_str"]}}</td>
                                        <td class="five_score">{{ @$var["interview_time_score"] }}</td>
                                        <td class="twenty_five_score">{{ @$var["interview_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["record_time_score"] }}</td>
                                        <td class="five_score">{{ @$var["record_num_score"] }}</td>
                                        <td class="five_score">{{ @$var["first_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["add_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["other_record_time_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_num_per_score"] }}</td>
                                        <td class="twenty_five_score">{{ @$var["lesson_per_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_other_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_kk_score"] }}</td>
                                        <td class="five_score">{{ @$var["lesson_per_change_score"] }}</td>
                                        <td>{{ @$var["total_score"] }}</td>                                        

                                        
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>           

        </div>

        @endif
    </section>
    
@endsection

