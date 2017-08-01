@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" >
    </script>
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >科目</span>
                        <select id="id_subject" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >面试老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacher_account"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >老师分类</span>
                        <select id="id_identity" class ="opt-change" ></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >渠道</span>
                        <input id="id_reference_teacherid">
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >面试类型</span>
                        <select id="id_interview_type" class ="opt-change" >
                            <option value="-1">全部</option>
                            <option value="1">视频</option>
                            <option value="2">1对1</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    2017-1-5日之后数据准确,之前不看.
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>面试老师  </td>
                    {!!\App\Helper\Utils::th_order_gen([                        
                        ["面试数","all_num" ],                     
                        ["面试人数","all_count" ],                     
                        ["实到人数","real_num" ],                     
                        ["入职人数","suc_count" ],
                        ["面试人数通过率","pass_per"],
                        ["面试人次通过率","all_pass_per"],
                        ["平均审核时长(天)","ave_time"],
                        ["试听成功数","all_lesson"],
                        ["签单数","have_order"],
                        ["签单率","order_per"]
                       ])  !!}
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["account"]}} </td>
                        <td>{{@$var["all_num"]}} </td>
                        <td >{{@$var["all_count"]}} </td>
                        <td >{{@$var["real_num"]}} </td>
                        <td >
                            @if(@$var["account"]=="全部")
                                {{@$var["suc_count"]}}({{$all_tea}})
                            @else
                                {{@$var["suc_count"]}}
                            @endif
                        </td>
                        <td >{{@$var["pass_per"]}}% </td>                        
                        <td >{{@$var["all_pass_per"]}}% </td>                        
                        <td >{{@$var["ave_time"]}}</td>                        
                        <td >{{@$var["all_lesson"]}}</td>                        
                        <td >{{@$var["have_order"]}}</td>                        
                        <td >{{@$var["order_per"]}}%</td>                        

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


