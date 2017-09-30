@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <style>
     .input-group{
         width:100%;
     }
     .input-group-w145{
         width:145px !important;
     }
    </style>
    <section class="content ">
        <div>
            <div class="row " >
               
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >助教</span>
                        <input id="id_assistantid"  /> 
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">校区</span>
                        <select class="opt-change form-control" id="id_campus_id" >
                            <option value="-1">全部</option>
                            @foreach($campus_list as $v)
                                <option value="{{$v["campus_id"]}}">{{$v["campus_name"]}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">小组</span>
                        <select class="opt-change form-control" id="id_groupid" >
                            <option value="-1">全部</option>
                            @foreach($groupid_list as $v)
                                <option value="{{$v["groupid"]}}">{{$v["group_name"]}}</option>
                            @endforeach

                        </select>
                    </div>
                </div>


               
            </div>
        </div>
        <hr/>
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td>校区</td>
                        <td>组别</td>
                        <td >学生姓名 </td>
                        <td >转介绍助教 </td>
                        <td > 转介绍时间</td>
                        <td >销售主管 </td>
                        <td >销售组长 </td>
                        <td >销售 </td>
                        <td >销售对应校区 </td>
                        <td >分配时间 </td>
                        <td >签单金额 </td>
                        <td >分配助教 </td>
                        <td >分配助教时间 </td>

                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>


                            <td>{{$var["campus_name"]}}</td>
                            <td>{{$var["group_name"]}}</td>                           
                            <td>
                                @if($master_flag==1)
                                    <a  href="/user_manage/ass_archive?order_by_str=ass_assign_time%20desc&grade=-1&student_type=-1&revisit_flag=-1&warning_stu=-1&user_name={{$var["userid"]}}"
                                        target="_blank" title="学生信息">{{@$var["nick"]}} </a>
                                @else
                                <a  href="/user_manage/ass_archive_ass?order_by_str=ass_assign_time%20desc&grade=-1&student_type=-1&revisit_flag=-1&warning_stu=-1&user_name={{$var["userid"]}}"
                                    target="_blank" title="学生信息">{{@$var["nick"]}} </a>
                                @endif
                            </td >

                            <td >
                                {{$var["ass_nick"]}}
                            </td>
                            <td >
                                {{$var["add_time_str"]}}
                            </td>
                            <td>{{$var["sub_assign_adminid_1_nick"]}}</td>
                            <td>{{$var["sub_assign_adminid_2_nick"]}}</td>
                            <td>{{$var["account"]}}</td>
                            <td>{{$var["seller_campus_name"]}}</td>
                            <td>{{$var["admin_assign_time_str"]}}</td>
                            <td>{{$var["order_price"]/100}}</td>
                            <td>{{$var["ass_name"]}}</td>
                            <td>{{$var["ass_assign_time_str"]}}</td>                           

                            <td>
                                <div class="opt-div"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >                                   
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
