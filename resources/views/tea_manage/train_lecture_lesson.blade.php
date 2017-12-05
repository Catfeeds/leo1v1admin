@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/jquery.base64.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <link href="/css/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css" />
    <section class="content">
    <div class="row">
        <div class="col-xs-12 col-md-4" data-title="时间段">
            <div id="id_date_range"></div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group">
                <span>面试老师</span>
                <input id="id_train_teacherid" class="opt-change"/>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group">
                <span>状态</span>
                <select id="id_lesson_status" class="opt-change" >
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group">
                <span>科目</span>
                <select id="id_subject" class="opt-change" >
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group">
                <span>年级</span>
                <select id="id_grade" class="opt-change" >
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group">
                <span>老师身份</span>
                <select id="id_identity" class="opt-change" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group">
                <span>审核状态</span>
                <select id="id_check_status" class="opt-change" >
                    <option value="-2">[全部]</option>
                    <option value="-1">未审核</option>
                    <option value="0">未通过</option>
                    <option value="1">已通过</option>
                    <option value="2">老师未到</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span >试讲状态</span>
                <select id="id_lecture_status" class ="opt-change" >
                    <option value="-2">无试讲</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span >全职老师</span>
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
                <span >邮件通知</span>
                <select id="id_train_email_flag" class ="opt-change" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span >推荐人</span>
                <input id="id_teacherid" type="text" value="" class="opt-change" placeholder="" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group">
                <span>培训状态</span>
                <select id="id_train_through_new_time" class="opt-change" >
                    <option value="-1">[全部]</option>
                    <option value="0">未通过</option>
                    <option value="1">已通过</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group">
                <span>模拟试听</span>
                <select id="id_train_through_new" class="opt-change" >
                    <option value="-1">[全部]</option>
                    <option value="0">未通过</option>
                    <option value="1">已通过</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-4">
            <button class="btn" id="id_have_wx_flag"  > {{@$wx_num["all_user"]}}/{{@$all_num["all_user"]}}/{{@$all_num["all_num"]}}</button>
            <button class="btn" id="id_send_email_flag"  >{{@$email_num["all_user"]}}/{{@$all_num["all_user"]}}/{{@$all_num["all_num"]}} </button>
        </div>
    </div>
    <hr />
    <table class="common-table">
        <thead>
            <tr>
                <td >课程时间</td>
                <td style="display:none">课程名称</td>
                <td style="display:none">邀约时间</td>
                <td >面试老师</td>
                <td >面试老师电话</td>
                <td >是否绑定微信</td>
                <td >邮件通知</td>
                <td >面试老师设备</td>
                <td >试讲状态</td>
                <td style="display:none">年级</td>
                <td style="display:none">科目</td>
                <td style="display:none">审核老师</td>
                <td >课程状态</td>
                <td >培训状态</td>
                <td >模拟试听</td>
                <td >状态</td>
                <td style="display:none">审核人</td>
                <td style="display:none">通过后老师id</td>
                <td style="display:none">老师身份</td>
                <td style="display:none">推荐人</td>
                <td >招师</td>
                <td >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td >{{$var['lesson_time']}}</td>
                    <td >{{$var['lesson_name']}}</td>
                    <td >{{$var['add_time_str']}}</td>
                    <td >{{$var['nick']}}</td>
                    <td>
                        <a href="javascript:;" class="show_phone" data-phone="{{$var["phone_spare"]}}" >
                            {{@$var["phone_ex"]}}
                        </a>
                    </td>
                    <td >{{@$var['have_wx_flag']}}</td>
                    <td >{{@$var['train_email_flag_str']}}</td>
                    <td >{{$var['user_agent']}}</td>
                    <td >{{$var['lecture_status_str']}}</td>
                    <td >{{$var['grade_str']}}</td>
                    <td >{{$var['subject_str']}}</td>
                    <td >{{$var['tea_nick']}}</td>
                    <td >{{$var['lesson_status_str']}}</td>
                    <td >{{$var['train_status_str']}}</td>
                    <td >{{$var['train_through_str']}}</td>
                    <td >{!! $var['trial_train_status_str'] !!}</td>
                    <td >{{$var['acc']}}</td>
                    <td >{{$var['real_teacherid']}}</td>
                    <td >{{$var['identity_str']}}</td>
                    <td >{{$var['reference_name']}}</td>
                    <td >{{$var['zs_name']}}</td>

                    <td >
                        <div
                            {!! \App\Helper\Utils::gen_jquery_data($var) !!}
                        >
                            @if($var['trial_train_status']==-1 || in_array($acc,["adrian","夏宏东","amyshen","jack","zoe"]) || $var["trial_train_status"]==2)
                                <a class="opt-set-server" title="服务器" >切换</a>
                                <!-- <a class="fa-edit opt-edit" title="审核"></a> -->
                                <a class="opt-edit-new" title="审核">审核</a>
                               
                            @endif
                            @if($var['lesson_status']==0)
                                <a class="opt-email" title="补发邮件">邮</a>
                            @endif
                            @if($acc=="jack" || $acc=="jim" || $acc=="林文彬")
                                <a class="opt-test" >测试</a>
                                <a class="opt-edit-pass" title="审核">审核-new</a>
                                <a class="opt-edit-no-pass" title="判定为不通过">不通过 </a>

                            @endif
                            @if($var['resume_url']!='')
                                <a class="opt-resume_url" title="查看简历">简历</a>
                            @endif
                            @if(in_array($acc_role,[8,10,12]) || $acc=="李明玉")
                                <a class="opt-del" title="删除">删除</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div id="id_dlg_set_server" style="display:none;">
        <div class="row">
            <div class="col-xs-0 col-md-3">
            </div>

            <div class="col-xs-6 col-md-3">
                <select id="id_region" class="form-control">
                    <option value="h">杭州</option>
                    <option value="b">北京</option>
                    <option value="q">青岛-测试</option>
                </select>
            </div>
            <div class="col-xs-6 col-md-3">
                <select id="id_server" class="form-control">
                    <option value="00">1</option>
                    <option value="01">2</option>
                    <option value="02">3</option>
                    <option value="03">4</option>
                    <option value="04">5</option>
                </select>
            </div>
        </div>
    </div>
    @include("layouts.page")
    </section>
@endsection
