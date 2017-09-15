@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src='/js/strophe.js'></script>
    <script type="text/javascript" src="/js/strophe.muc.js"></script>
    <script type="text/javascript" src="/page_js/lib/wb_supervisor.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/jquery.websocket.js"></script>
    <script type="text/javascript" src="/js/jquery.base64.js"></script>
    <script src='/page_js/set_lesson_time_new.js'></script>
    <style type="text/css">
     .modal-dialog {
         width: 750px;
         margin:5px auto;
     }
     @media screen and (max-width: 480px) {
     .modal-dialog {
         width: 350px;
         margin:5px auto;
     }
    </style>
    <script type="text/javascript" >
     var group_type = "{{$group_type}}";
     var self_groupid = "{{$self_groupid}}";
     var is_group_leader_flag   = "{{$is_group_leader_flag}}";
    </script>
    <section class="content">
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group  " style="width:180px">
                    <div class=" input-group-btn "  >
                        <button id="id_pre_day" type="submit" class="btn  btn-primary"><i class="fa fa-chevron-left"></i></button>
                    </div>
                    <input type="text"   class="form-control" id="id_date" placeholder="日期" />
                    <div class="input-group-btn">
                        <button id="id_next_day" type="submit" class="btn btn-primary"><i class="fa fa-chevron-right"></i></button>
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group">
                    <span>状态</span>
                    <select id="id_run_flag" class="opt-change" >
                        <option value="-1">[全部]</option>
                        <option value="1">上课中</option>
                        <option value="2">试听</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group  " >
                    <input type="text"   class="form-control opt-change" id="id_st_application_nick" placeholder="试听销售" />
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group  ">
                    <span> 老师 </span>
                    <input type="text"   class="form-control opt-change" id="id_teacherid"  />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group  ">
                    <span> 学生</span>
                    <input type="text"   class="form-control opt-change" id="id_userid"  />
                </div>
            </div>


            <div class="col-xs-6 col-md-2">
                <div class="input-group  ">
                    <span>助教</span>
                    <input type="text"   class="form-control opt-change" id="id_assistantid"  />
                </div>
            </div>

            <div class="col-xs-6 col-md-2" id="id_seller_new">
                <div class="input-group ">
                    <span class="input-group-addon">销售</span>
                    <input id="id_test_seller_id" class="opt-change" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group  ">
                    <span style="color:red; font-size:20px" id="id_show_reload_msg"> </span>
                </div>
            </div>



        </div>
        <hr />
        <table class=" common-table  ">
            <thead>
                <tr>
                    <td class="remove-for-xs">服务器</td>
                    <td style="display:none;">助教</td>
                    <td >老师</td>
                    <td >学生</td>
                    <td >服务<br/>上课时间</td>
                    <td >老师</td>
                    <td >学生</td>
                    <td class="remove-for-xs"  >家长</td>
                    <td class="remove-for-xs"  >助教</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var )
                    <tr  class="wb_monitor_item"  data-lessonid="{{$var["lessonid"]}}" >
                        <td   >
                            {{$var["ip"]}}:{{$var["port"]}}<span style="display:none;">: {{$var["room_id"]}} </span> ({{$var["region"]}})<br/>
                            {{$var["lesson_type_str"]}} |语音通道:({{$var["server_type_str"]}})
                            <br>{{$var["index"]}}: 课程id:{{$var["lessonid"]}}  <span style="display:none;"> <br>  {{$var["ip"]}}H{{$var["port"]}}H{{$var["room_id"]}} </span> |销售: {{@$var["st_application_nick"]  }} {{$var["account"] }}
                        </td>
                        <td  >
                            {{$var["assistantid"]}}:
                            {{$var["assistant_nick"]}}
                        </td>
                        <td    >
                            @if ( $_origin_act=="/supervisor/monitor_seller" )
                                <a href="/teacher_info_admin/index?teacherid={{$var["teacherid"]}}" target="_blank" > {{$var["teacher_nick"]}} </a>
                            @else
                            <a href="/human_resource?teacherid={{$var["teacherid"]}}" target="_blank" > {{$var["teacher_nick"]}} </a>
                            @endif
                        </td>
                        <td >
                            <a class="opt-stu-info"  href="/stu_manage/index?sid={{$var["userid"]}}">{{$var["student_nick"]}}</a>
                        </td>
                        <td ><span>{{$var["region"]}}</span><br/>{{$var["lesson_time"]}}</td>
                        <td class="wb_tea " style="padding:8px 0px;">
                            <div style="  " class="xmpp_count">
                                <span style="   display:block;  text-align:center"></span>
                            </div>
                            <br/>
                            <div style="" class="webrtc_count" >
                                <span style=" border-radius:40%; display:block;  text-align:center"></span>
                            </div>
                        </td>
                        <td class="wb_stu" style="padding:8px 0px;">
                            <div style=" " class="xmpp_count">
                                <span style="display:block;text-align:center"></span>
                            </div>
                            <br/>
                            <div style="" class="webrtc_count" >
                                <span style=" border-radius:40%; display:block;  text-align:center"></span>
                            </div>


                        </td>
                        <td class="wb_par  " style="padding:8px 0px;">
                            <div class="xmpp_count">
                                <span style=" display:block;  text-align:center"></span>
                            </div>
                            <br/>
                            <div style="" class="webrtc_count" >
                                <span style=" border-radius:40%;  display:block;  text-align:center"></span>
                            </div>
                        </td>
                        <td class="wb_ad " style="padding:8px 0px;">
                            <div style=" " class="xmpp_count">
                                <span style=" display:block;   text-align:center"></span>
                            </div>
                            <br/>
                            <div style="" class="webrtc_count" >
                                <span style=" border-radius:40%;  display:block;  text-align:center"></span>
                            </div>
                        </td>

                        <td   >
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class=" fa-info opt-lesson-info" title="课程信息"></a>
                                <a class=" fa-play opt-play " title="实时白板"></a>
                                <a href="javascript:;" class="btn fa fa-qrcode opt-qr-pad-at-time "
                                   data-type="leoedu://meeting.leoedu.com/meeting="
                                   title="pad实时课程二维码" ></a>
                                <a class=" fa-sitemap opt-set-server" title="服务器" ></a>
                                <a class=" fa-list-alt opt-log-list" title="登录日志"></a>
                                <a class=" fa-credit-card opt-lesson" title="课程详细信息"></a>
                                <a class=" fa-rotate-left   opt-user-need-rejoin" title="让用户重进"></a>
                                <a class=" fa-comment   opt-user-send-xmpp-message " title="弹幕"></a>
                                <a class=" fa-gavel opt-set-server-type " title="设置服务器类型"></a>
                                <a class=" fa-print opt-add-error" title="写入错误信息"></a>
                                <a class=" fa-clock-o  opt-change-time" title="上课延时/调整上课时间"></a>
                                <a class=" fa-list-alt opt-lesson-all " title="课程信息汇总"></a>
                            </div>
                        </td>

                    </tr>

                @endforeach
            </tbody>

        </table>


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

        <div class="dlg_show_faq" style="display:none;" >
            <div>
                <table class="table table-bordered table-striped"   >
                    <tr>
                        <td rowspan="3">1</td>
                        <td> Q: </td><td>页面显示 网络错误（-1001）</td>
                    </tr>
                    <tr>
                        <td> R: </td><td>网络环境过差，无法及时获取数据</td>
                    </tr>
                    <tr>
                        <td> OP: </td><td>稍等后重试，或重启设备</td>
                    </tr>
                    <tr>
                        <td rowspan="3">2</td>
                        <td> Q: </td><td>页面显示 网络错误（-1003）</td>
                    </tr>
                    <tr>
                        <td> R: </td><td>无法连接服务器</td>
                    </tr>
                    <tr>
                        <td> OP: </td><td>重启设备或重连网络</td>
                    </tr>

                    <tr>
                        <td rowspan="3">3</td>
                        <td> Q: </td><td>理优管理系统显示语音未连接</td>
                    </tr>
                    <tr>
                        <td> R: </td><td>设备不支持、网络过差</td>
                    </tr>
                    <tr>
                        <td> OP: </td><td>I. 新用户语音无法连接，
                            a.若iOS，后台退出重试，多次未果。更换服务器。。。
                            b.若Android，如果为小米，执行a，否则，上报至开发 。。<br>
                            II. 老用户语音无法连接
                            从后台退出后重进，多次未果，更换服务器。。<br>

                            PS: 语音连接会稍慢，可多等待几秒钟</td>
                    </tr>

                    <tr>
                        <td rowspan="3">4</td>
                        <td> Q: </td><td>理优管理系统显示白板未连接</td>
                    </tr>
                    <tr>
                        <td> R: </td><td>白板内容过多，加载需要时间，网络过差</td>
                    </tr>
                    <tr>
                        <td> OP: </td><td>更改服务器</td>
                    </tr>

                    <tr>
                        <td rowspan="3">5</td>
                        <td> Q: </td><td>老师截图不可见</td>
                    </tr>
                    <tr>
                        <td> R: </td><td>学生或老师的网络环境较差，域名解析失败</td>
                    </tr>
                    <tr>
                        <td> OP: </td><td>I . 尽量减少拍照上传类型的图片传输，所贴图最好从pdf中截取<br>
                            II. 后台看到图片，学生看不到更改DNS，将8.8.8.8更改为114.114.114.114
                            后台看不到图片，老师上传失败</td>
                    </tr>

                    <tr>
                        <td rowspan="3">6</td>
                        <td> Q: </td><td>学生可以听到老师，老师无法听到学生</td>
                    </tr>
                    <tr>
                        <td> R: </td><td></td>
                    </tr>
                    <tr>
                        <td> OP: </td><td>尝试更换服务器</td>
                    </tr>

                    <tr>
                        <td rowspan="3">6</td>
                        <td> Q: </td><td>语音断断续续</td>
                    </tr>
                    <tr>
                        <td> R: </td><td>网络太差</td>
                    </tr>
                    <tr>
                        <td> OP: </td><td>尝试更换服务器，建议更改时间，若无法保证上课质量，可考虑放弃此学生。</td>
                    </tr>
                </table>
            </div>
        </div>

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
        <div class="opt-set-audio-server-type" style="display:none">
            <div class="row">
                <div class="input-group ">
                    <span class="input-group-addon">课程id</span>
                    <input type="text" class="lessonid form-control" >
                </div>
            </div>
            <div class="row">
                <div class="input-group ">
                    <span class="input-group-addon">音频服务器</span>
                    <select class="opt-audio-server-type form-control">
                        <option value="0">未设定(默认用声网)</option>
                        <option value="1">(理优)telpresence</option>
                        <option value="2">(声网)agora</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="dlg_add_error_info" style="display:none">
            <table class="table table-bordered table-striped">
              <tbody>
                <tr>
                  <td style="text-align:right; width:30%;">常见异常</td>
                  <td>
                            <div class="add_error_info"></div>
                        </td>
                </tr>
                <tr>
                  <td style="text-align:right; width:30%;">其他异常</td>
                  <td><textarea value="" class="add_error_info_other" type="text"></textarea></td>
                </tr>
            </tbody>
          </table>
        </div>
        <iframe  id="id_frame" style="display:none;"> </iframe>


    </section>


@endsection
