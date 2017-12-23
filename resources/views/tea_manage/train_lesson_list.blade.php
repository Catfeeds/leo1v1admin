@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src='/js/strophe.js'></script>
    <script type="text/javascript" src="/js/strophe.muc.js"></script>
    <script type="text/javascript" src="/js/jquery.websocket.js"></script>
    <script type="text/javascript" src="/js/jquery.base64.js"></script>
    <style type="text/css">
     .upload_process{
         height:5px;width:0;background:#0bceff;font-size:5px;position:absolute;left:0;top:0;z-index:2;
     }
    </style>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-4" data-title="时间段">
                <div id="id_date_range"></div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group">
                    <span>状态</span>
                    <select id="id_lesson_status" class="opt-change" >
                        <option value="-1">[全部]</option>
                        <option value="0">未上</option>
                        <option value="1">进行中</option>
                        <option value="2">已结束</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group">
                    <span>课程子类型</span>
                    <select id="id_lesson_sub_type" class="opt-change" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group">
                    <span>培训类型</span>
                    <select id="id_train_type" class="opt-change" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group">
                    <span>老师</span>
                    <input type="text" class="form-control opt-change" id="id_teacherid"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-1">
                <div class="input-group">
                    <a id="id_add_lesson" class="btn btn-warning"><li class="fa fa-plus">添加课程</li></a>
                </div>
            </div>
            <div class="col-xs-6 col-md-1">
                <div class="input-group">
                    <a id="id_add_trial_train_lesson" class="btn btn-warning"><li class="fa fa-plus">添加模拟试听</li></a>
                </div>
            </div>
        </div>
        <hr />
        <table class="common-table">
            <thead>
                <tr>
                    <td >服务器</td>
                    <td >老师</td>
                    <td >课程名称</td>
                    <td >上课时间</td>
                    <td >课堂状态</td>
                    <td >讲义状态</td>
                    <td >参与列表</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td >
                            {{$var["ip"]}}:{{$var["port"]}}({{$var["region"]}})
                            <br/>
                            {{$var["lesson_type_str"]}} | 语音通道:({{$var["server_type_str"]}})
                            <br/>
                            {{$var["index"]}}:课程id:{{$var["lessonid"]}}
                        </td>
                        <td >{{$var['tea_nick']}}</td>
                        <td >{{$var['lesson_name']}}</td>
                        <td >{{$var['lesson_time']}}</td>
                        <td >{{$var['lesson_status_str']}}</td>
                        <td >{{$var['cw_status']}}</td>
                        <td >
                            总人数:{{$var['user_num']}}<br/>
                            到达数:{{$var['login_num']}}<br/>
                            通过数:{{$var['through_num']}}<br/>
                        </td>
                        <td >
                            <div
                                {!! \App\Helper\Utils::gen_jquery_data($var) !!}
                            >
                                <a class="btn fa fa-qrcode opt-qr-pad-at-time " data-type="leoedu://meeting.leoedu.com/meeting="
                                   title="pad实时课程二维码" ></a>
                                <a class=" fa-edit opt-lesson" title="修改课程信息"></a>
                                <a class=" fa-sitemap opt-set-server" title="设置服务器类型"></a>
                                <a class=" opt-upload">上传课件</a>
                                <a class=" btn fa fa-group opt-add_train_lesson_user" title="一键添加参与者"></a>
                                <a class=" btn fa fa-user-md opt-add_single_user" title="添加单个参与者"></a>
                                <a class=" opt-get_user_list">名单</a>
                                <a class=" opt-log-list">出勤</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
        <div style="display:none;" >
            <div id="id_lesson_log"  >
                <div class="row">
                    <div class="col-xs-6 col-md-3">
                        <div class="input-group ">
                            <select class="opt-userid form-control" >
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-3">
                        <div class="input-group ">
                            <select class="opt-server-type form-control"  >
                                <option value="-1" > 不限 </option>
                                <option value="1" > webrtc</option>
                                <option value="2" > xmpp</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr/>
                <table   class="table table-bordered "   >
                    <tr>  <th> 时间 <th>角色 <th>用户id <th>服务 <th> 进出 <th> ip </tr>
                        <tbody class="data-body">
                        </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
