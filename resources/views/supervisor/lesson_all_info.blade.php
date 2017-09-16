@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <script type="text/javascript" >
     var g_phone = "{{$stu_info["stu_phone"]}}";
     var g_uid   = "{{$stu_info["userid"]}}";
    </script>

    <style type="text/css">
     .row-td-field-name {
         padding-right: 0px;
     }
     .row-td-field-value {
         padding-left:0px;
         padding-right: 0px;
     }

     .row-td-field-name >  span {
         background-color: #eee;
         border: 1px solid #ccc;
         border-collapse: separate;
         color: #555;
         display: table-cell;
         font-size: 14px;
         font-weight: normal;
         line-height: 1;
         padding: 6px 12px;
         text-align: right;
         vertical-align: middle;
         width: 1%;
         height: 26pt;
     }
     .row-td-field-value >  span {
         border: 1px solid #ccc;
         border-collapse: separate;
         color: #555;
         display: table-cell;
         font-size: 14px;
         font-weight: normal;
         line-height: 1;
         padding: 6px 12px;
         vertical-align: middle;
         height: 26pt;
         width: 1%;
         text-align:left ;
         background-color: #FFF;
         word-wrap: break-word;

     }


     body {background-color: red}
     p {margin-left: 20px}
    </style>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/jquery.query.js"></script>
    <script src="/page_js/enum_map.js" type="text/javascript"></script>
    <section class="content">
        @foreach ($table_data_list as $var )


            <div > <font color="red"> 请复制 以下链接 到 问题报告群: </font> </div>
            <div  style="color:blue;" > http://admin.yb1v1.com/supervisor/lesson_all_info?lessonid={{$var["lessonid"]}}
            </div>
        <br/>


            @if (\App\Helper\Common::check_in_phone ())
                <div style=" word-wrap: break-word; ">
                    <span >userid:</span> {{$var["userid"]}} <br/>
                    <span >学生昵称:</span> {{$var["student_nick"]}} <br/>
                    <span >老师昵称:</span> {{$var["teacher_nick"]}} <br/>
                    <span >服务器信息:</span> {{$var["ip"]}}:{{$var["port"]}}({{$var["region"]}}) <br/>
                    <span >课程类型:</span> {{$var["lesson_type_str"]}} <br/>
                    <span >语音通道:</span> {{$var["server_type_str"]}} <br/>
                    <span >课程id:</span> {{$var["lessonid"]}} <br/>
                    <span >负责人:</span> {{$stu_info["cur_require_admin_nick"]}}
                    {{$var["account"] }}
                    <br/>
                    <span >上课时间:</span> {{$var["lesson_time"] }}<br/>
                    <span >room_id:</span> {{$var["room_id"]}}<br/>
                    <span >科目:</span>    {{$stu_info["subject_str"]}}<br/>
                    <span >学生电话:</span> {{$stu_info["stu_phone"]}}<br/>
                    <span >老师电话:</span> {{$stu_info["tea_phone"]}}<br/>
                    <span >年级:</span> {{$stu_info["grade_str"]}} <br/>
                    <span >老师退出次数:</span> {{$stu_info["tea_xmpp"]}} |  {{@$stu_info['tea_log_status']}}<br/>
                    <span >学生退出次数:</span> {{$stu_info["stu_xmpp"]}} |  {{@$stu_info['stu_log_status']}}<<br/>
                    <span >学生网络:</span> {{$stu_info["stu_situation"]}}<br/>
                    <span >老师网络:</span> {{$stu_info["tea_situation"]}}<br/>
                    <span >老师版本:</span> {{$stu_info["tea_user_agent"]}} <br/>
                    <span >学生版本:</span> {{$stu_info["stu_user_agent"]}} <br/>

                    <div> <a href="http://admin.yb1v1.com/supervisor/monitor?userid={{$var["userid"]}}">课程信息 </a> </div>
                    <div> <a href="http://admin.yb1v1.com/tea_manage/lesson_list?lessonid={{$var["lessonid"]}}">课程状态 </a> </div>
                </div>
            @else
                <div class="row wb_monitor_item">

                    <div class="col-xs-12 col-md-12"   >
                        <div style="width:98%" id="id_stu_info"
                             {!!  \App\Helper\Utils::gen_jquery_data($var)  !!}
                        >
                            <div class="row">
                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >userid:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  >{{$var["userid"]}} </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >学生昵称:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  >{{$var["student_nick"]}} </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >老师昵称:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  >{{$var["teacher_nick"]}} </span>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >IP信息:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  >{{$var["ip"]}}:{{$var["port"]}}({{$var["region"]}}) </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >课程类型:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  >{{$var["lesson_type_str"]}} </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >语音通道:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  >{{$var["server_type_str"]}} </span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >课程id:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  >{{$var["lessonid"]}} </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >销售:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  > 
                                            {{$stu_info["cur_require_admin_nick"]}} |
                                            {{@$var["st_application_nick"]}} {{$var["account"] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >上课时间:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span >{{$var["lesson_time"] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >courseid:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  >{{$var["courseid"]}} </span>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >room_id:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  >{{$var["room_id"]}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >科目:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  > {{$stu_info["subject_str"]}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >学生电话:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  >{{$stu_info["stu_phone"]}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >老师电话:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  >{{$stu_info["tea_phone"]}}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >年级:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span  >{{$stu_info["grade_str"]}} </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >老师退出次数:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span>{{$stu_info["tea_xmpp"]}} | {{@$stu_info['tea_log_status']}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-md-4"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-5 row-td-field-name"  >
                                            <span >学生退出次数:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-7  row-td-field-value">
                                            <span>{{$stu_info["stu_xmpp"]}} | {{@$stu_info['stu_log_status']}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-xs-12 col-md-10"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-2 row-td-field-name"  >
                                            <span >学生网络:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-10  row-td-field-value">
                                            <span  >{{$stu_info["stu_situation"]}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-10"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-2 row-td-field-name"  >
                                            <span >老师网络:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-10  row-td-field-value">
                                            <span  >{{$stu_info["tea_situation"]}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-md-10"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-2 row-td-field-name"  >
                                            <span >老师版本:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-10  row-td-field-value">
                                            <span  >{{$stu_info["tea_user_agent"]}} </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-md-10"  >
                                    <div class="row">
                                        <div class="col-xs-4 col-md-2 row-td-field-name"  >
                                            <span >学生版本:</span>
                                        </div>
                                        <div class="col-xs-8 col-md-10  row-td-field-value">
                                            <span  >{{$stu_info["stu_user_agent"]}} </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif
        @endforeach

            <h5 style=" border-bottom: 2px solid #999;font-size:25px ; line-height: 50px;" >登录日志 </h5>

            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <td>时间</td>
                        <td>角色</td>
                        <td>用户id</td>
                        <td>服务</td>
                        <td>进出</td>
                        <td>ip</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($log_lists as $val )
                    <tr class="{{$val['cls']}}">
                        <td>{{@$val['opt_time']}}</td>
                        <td>{{@$val['rule_str']}}</td>
                        <td>{{@$val['userid']}}</td>
                        <td>{{@$val['server_type']}}</td>
                        <td>{{@$val['opt_type']}}</td>
                        <td>{{@$val['server_ip']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>




    </section>

    <div id="id_dlg_select_user" style="display:none;"   >
        <table   class="table table-bordered table-striped "   >
            <tr ><th></th><th>姓名</th><th>性别</th><th>学生人数</th>  </tr>
        <tbody class="row-data" >
        </tbody>
        </table>
    </div>

    <div id="id_dlg_set_server" style="display:none;">
        <div class="row">
            <div class="col-xs-0 col-md-3">
            </div>

            <div class="col-xs-6 col-md-3">
              <select id="id_region" class="form-control">
                  <option value="q">青岛</option>
                  <option value="h">杭州</option>
                  <option value="b">北京</option>
                  <option value="s">深圳</option>
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
    <div id="id_dlg_set_user" style="display:none;">
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">昵称</span>
                    <input type="text" value="" class=" form-control "  id="id_name"  placeholder="" />
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">实名</span>
                    <input type="text" value="" class=" form-control "  id="id_realname"  placeholder="" />
                </div>
            </div>

            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">性别</span>
                    <select id="id_gender" class="form-control">
                        <option value="0" >未设置</option>
                        <option value="1" >男</option>
                        <option value="2" >女</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">家长</span>
                    <input type="text" value="" class=" form-control "   id="id_parent_name"  placeholder="" />

                </div>
            </div>
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">关系</span>
                    <select class="form-control"  id="id_parent_type" >
                        <option value="1">父亲</option>
                        <option value="2">母亲</option>
                        <option value="3">爷爷</option>
                        <option value="4">奶奶</option>
                        <option value="5">外公</option>
                        <option value="6">外婆</option>
                        <option value="7">其他</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">生日</span>
                    <input type="text" value="" class=" form-control "   id="id_birth"  placeholder="" />
                </div>
            </div>


        </div>


        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="input-group ">
                    <span class="input-group-addon">地址</span>
                    <input type="text" value="" class=" form-control "  id="id_address"  placeholder="" />
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-xs-6 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">学校</span>
                    <input type="text" value="" class=" form-control "  id="id_school"  placeholder="" />
                </div>
            </div>
            <div class="col-xs-6 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">邮箱</span>
                    <input type="text" value="" class=" form-control "  id="id_stu_email"  placeholder="" />
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xs-12 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">教材</span>
                    <select class="form-control" id="id_textbook">
                    </select>

                </div>
            </div>

            <div class="col-xs-12 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">地区</span>
                    <input type="text" value="" class=" form-control "  id="id_region"  placeholder="" />
                </div>
            </div>
        </div>




    </div>
    <div class="dlg_set_dynamic_passwd" style="display:none">
        <div class="row ">
            <div class="input-group">
                <label class="stu_nick"> </label>
                <label class="stu_phone"> </label>
            </div>
        </div>
        <div class="row">
            <div class="input-group">
                <span class="input-group-addon">请输入临时密码</span>
                <input type="text" class="dynamic_passwd" />
            </div>
        </div>
    </div>

    <div class="dlg_add_user_parent" style="display:none">
        <div class="row ">
            <div class="input-group">
                <label class="userid"> </label>
                <label class="stu_phone"> </label>
            </div>
        </div>
        <div class="row">
            <div class="input-group">
                <span class="input-group-addon">家长电话</span>
                <input type="text" class="parent_phone" />
            </div>
        </div>
        <div class="row">
            <div class="input-group">
                <span class="input-group-addon">家长姓名</span>
                <input type="text" class="parent_name" />
            </div>
        </div>
    </div>

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




@endsection
