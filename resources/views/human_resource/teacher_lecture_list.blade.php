@extends('layouts.app')
@section('content')
<script type="text/javascript" > 
 var acc= "{{$acc}}";
 var g_adminid= "{{$adminid}}";
 var tea_subject= "{{$tea_subject}}";
</script>
<script type="text/javascript" src="/js/svg.js"></script>
<script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div id="id_date_range" >
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
                        <span >年级</span>
                        <select id="id_grade" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >审核状态</span>
                        <select id="id_status" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >测试数据</span>
                        <select id="id_is_test_flag" class ="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >扩年级</span>
                        <select id="id_trans_grade" class ="opt-change" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >是否全职老师</span>
                        <select id="id_full_time" class ="opt-change" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >绑定微信</span>
                        <select id="id_have_wx" class ="opt-change" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >推荐人</span>
                        <input id="id_teacherid" type="text" value="" class="opt-change" placeholder="" />
                    </div>
                </div>
                <div class=" col-md-3">
                    <div class="input-group ">
                        <span>搜索</span>
                        <input type="text" id="id_phone" placeholder="请输入电话或姓名搜索"/>
                    </div>
                </div>
                @if($acc=="adrian")
                <div class="col-xs-12 col-md-2">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_reset_lecture_grade">重置试讲年级</button>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td display="none">num</td>
                    <td>手机号</td>
                    <td>是否绑定微信</td>
                    <td>老师姓名</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td width="150px">视频标题</td>
                    <td>老师身份</td>
                    <td>全职老师</td>
                    <td width="150px">教材版本</td>
                    <td>视频添加时间</td>
                    <td style="display:none">试讲预约提交时间</td>
                    <td style="display:none">视频确认时间</td>
                    <td>确认状态</td>
                    <td width="300px">确认原因</td>
                    <td width="120px">扩年级</td>
                    <td width="120px">跨科面试情况</td>
                    <td>推荐人</td>
                    <td>负责人</td>
                    <td>客户端版本</td>
                    <td style="display:none">测试数据</td>
                    <td>招师</td>
                    <td class ="caozuo">操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["num"]}}</td>
                        <td>{{$var["phone"]}}</td>
                        <td>{{$var["have_wx_flag"]}}</td>
                        <td>{{$var["nick"]}}</td>
                        <td>{{$var["grade_str"]}}</td>
                        <td>{{$var["subject_str"]}}</td>
                        <td>{{$var["title"]}}</td>
                        <td>{{$var["identity_str"]}}</td>
                        <td>{{$var["full_time_str"]}}</td>
                        <td>{{$var["textbook"]}}</td>
                        <td>{{$var["add_time_str"]}}</td>
                        <td>{{$var["answer_begin_time_str"]}}</td>
                        <td>{{$var["confirm_time_str"]}}</td>
                        <td>{{$var["status_str"]}}</td>
                        <td>{{$var["reason"]}}</td>
                        <td>{{$var["trans_grade_str"]}}</td>
                        <td>
                            @if($var["t_subject"]>0 && $var["t_subject"] != $var["subject"])
                                {{@$var["t_subject_str"]}}
                            @endif
                        </td>
                        <td>{{$var["reference_name"]}}</td>
                        <td>{{$var["account"]}}</td>
                        <td>{{$var["user_agent"]}}</td>
                        <td>{{$var["is_test_flag_str"]}}</td>
                        <td>{{$var["zs_name"]}}</td>
                        <td class ="caozuo">
                            <div
                                {!! \App\Helper\Utils::gen_jquery_data($var) !!}
                            >
                                @if(in_array($acc,["adrian","coco","wander","nick","amyshen","王芳","tanya","max"]))
                                    <a class="opt-reset">重置</a>
                                @endif
                                @if(in_array($acc,["adrian","jack",$var["account"],"wander","nick","zoe","abby"]) || $var["account"]=="")
                                    <a class="fa-video-camera opt-play" title="回放"></a>
                                    @if($var['status']==0)
                                        <a class="opt-video_error" title="视频出错短信通知">视频出错</a>
                                    @endif
                                    @if(in_array($acc,["adrian","jack","ted","zoe","abby"]))
                                        <a class="opt-add_teacher" title="审核通过,但未在老师档案中,点击此按钮添加">添加老师</a>
                                        @if(in_array($acc,["adrian","jack"]))
                                            <a class="opt-set_test">测试数据切换</a>
                                        @endif
                                    @endif
                                    @if($var["account"]!="" || in_array($acc,["adrian"]))
                                        <a class="opt-update_lecture_status" title="更改状态">更改状态</a>
                                        @if($var['status']!=2 || in_array($acc,["adrian"]) )
                                            <a class="opt-edit-new" title="更改状态">审核 </a>
                                        @endif
                                        @if($var['identity_image']!='')
                                            <a class="opt-get_identity_image" title="查看证书">证书</a>
                                        @endif
                                        @if($var['resume_url']!='')
                                            <a class="opt-resume_url" title="查看简历">简历</a>
                                        @endif
                                        @if($var['status']>0)
                                            <a class="opt-confirm-score" title="评分详情">评分详情</a>
                                        @endif
                                        @if($acc=="jack" || $acc=="jim")
                                            <a class="opt-confirm-score_new" title="设置标签">设置标签</a>
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
