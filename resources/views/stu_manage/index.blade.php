@extends('layouts.stu_header')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <script type="text/javascript" >
     var g_phone = "{{$stu_info["phone"]}}";
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

     }


     body {background-color: red}
     p {margin-left: 20px}
    </style>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/jquery.query.js"></script>
    <script src="/page_js/enum_map.js" type="text/javascript"></script>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-2">
                <div class="row" >
                    <div class="col-xs-6 col-md-12" style="text-align:center;" >
                        <div class="header_img"><img  style="border-radius:130px;width:120px; border: 3px solid #ccc;"  src="{{$stu_info["face"]}}" /></div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-md-10"   >
                <div style="width:98%" id="id_stu_info"
                     {!!  \App\Helper\Utils::gen_jquery_data($stu_info)  !!}
                >
                    <div class="row">
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >userid:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["userid"]}} </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >实名:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["realname"]}} </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >邮箱:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["stu_email"]}} </span>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >昵称:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["nick"]}} </span>
                                </div>
                            </div>
                        </div>


                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >性别:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["gender_str"]}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >注册时间:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  > {{$stu_info["reg_time"]}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >年级:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["grade_str"]}} </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >账号:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["phone"]}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >生日:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["birth"]}} </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >家长:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["parent_name"]}} </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >关系:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["parent_type_str"]}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >家长微信:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["parent_wx_openid"]}}</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >助教:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["assistant_nick"]}} </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >助教信息:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >组别: {{$stu_info["group_name"]}} </span>
                                    <span  >组长: {{$stu_info["master_adminid_name"]}} </span>
                                </div>
                            </div>
                        </div>


                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >销售:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >
                                        {{$stu_info["seller_admin_nick"]}}/
                                        {{$stu_info["seller_phone"]}}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >家长电话:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["parent_phone"]}} </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-8"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-3  row-td-field-name"  >
                                    <span >地址:</span>
                                </div>
                                <div class="col-xs-6 col-md-9  row-td-field-value">
                                    <span  >{{$stu_info["address"]}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >学校:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["school"]}} </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6 col-md-8"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-3  row-td-field-name"  >
                                    <span >教材:</span>
                                </div>
                                <div class="col-xs-6 col-md-9  row-td-field-value">
                                    <span  >{{$stu_info["textbook"]}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >获赞数:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["praise"]}} </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5  row-td-field-name"  >
                                    <span >渠道来源:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["origin"]}} </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5  row-td-field-name"  >
                                    <span >地区:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{$stu_info["region"]}} </span>
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-12"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-2 row-td-field-name"  >
                                    <span >user_agent:</span>
                                </div>
                                <div class="col-xs-6 col-md-10  row-td-field-value">
                                    <span  >{{$stu_info["user_agent"]}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-2  row-td-field-name"   > <span>操作:</span></div>
                        <div class="col-xs-6 col-md-10  row-td-field-value"  data-studentid="{{$stu_info['userid']}}">
                            <button style="margin-left:10px" id="id_set_user" type="button" class="btn btn-warning" >修改资料</button>
                            <button  id="id_add_user_parent" type="button" class="btn btn-warning" >添加家长</button>
                            <button  id="id_set_assistantid" type="button" class="btn btn-warning" >设置助教</button>
                            <button  id="id_set_seller_adminid" type="button" style="display:none;" class="btn btn-warning" >设置销售</button>
                            <button  id="id_tmp_passwd" type="button" style="" class="btn btn-warning" >临时密码</button>
                            <button  id="id_set_grade" type="button" style="" class="btn btn-warning" >年级调整</button>
                            <button  id="id_set_stu_account" type="button" class="btn btn-warning" >修改账号</button>
                            @if($stu_info['userid']==61631 || $stu_info['is_test_user']==1)
                                <button id="id_add_mypraise" type="button" class="btn btn-warning">加赞</button>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-2  row-td-field-name"   > <span> 助教交接单:</span></div>
                        <div class="col-xs-6 col-md-10  row-td-field-value"  >
                            <button  id="id_set_init_info_pdf_url" type="button" style="margin-left:10px" class="btn btn-warning" >上传</button>
                            <button  id="id_show_init_info_pdf" type="button" style="margin-left:10px" class="btn btn-primary" >查看</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

            <h5 style=" border-bottom: 2px solid #999;font-size:25px ; line-height: 50px;" >小班课 </h5>
            <table   class="table table-bordered table-striped" >
                <thead>
                    <tr>
                        <td class="remove-for-not-xs "> </td>
                        <td >课程id</td>
                        <td >课程名称</td>
                        <td >加入时间</td>
                        <td class=" remove-for-xs ">老师</td>
                        <td class=" remove-for-xs  " >助教</td>
                        <td class=" remove-for-xs  " >操作</td>
                    </tr>
                </thead>

                @foreach ($small_lesson_list as  $key => $var )
                    <tr >
                    <include: file="../al_common/td_xs_opt.html" />
                        <td  >
                            {{$var["courseid"]}}
                        </td>
                        <td>{{$var["course_name"]}}</td>
                        <td>{{$var["join_time"]}}</td>
                        <td class=" remove-for-xs  ">{{$var["teacher_nick"]}}</td>
                        <td class=" remove-for-xs  ">{{$var["assistant_nick"]}}</td>
                <td class=" remove-for-xs  " >
                  <div data-courseid="{{$var["courseid"]}}">
                                <a href="javascript:;" class="btn  fa fa-info td-info"></a>
                                <a href="/small_class/index?courseid={{$var["courseid"]}}" target="_blank" >查看课程 </a>
                  </div>
                </td>
                    </tr>
                @endforeach
            </table>

            <h5 style=" border-bottom: 2px solid #999;font-size:25px ; line-height: 50px;" >公开课</h5>
            <table   class=" common-table" >
                <thead>
                    <tr>
                        <td >课程id</td>
                        <td >课程名称</td>
                        <td >上课时间</td>
                        <td >老师</td>
                        <td >助教</td>
                        <td >加入时间</td>
                        <td >操作</td>
                    </tr>
                </thead>
                @foreach ($open_lesson_list as  $key => $var )
                    <tr >
                        <td> {{$var["courseid"]}} </td>
                        <td>{{$var["course_name"]}} </td>
                        <td>{{$var["lesson_time"]}}</td>
                        <td>{{$var["teacher_nick"]}}</td>
                        <td>{{$var["assistant_nick"]}}</td>
                        <td>{{$var["join_time"]}}</td>
                <td  >
                  <div  data-courseid="{{$var["courseid"]}}" data-lessonid="{{$var["lessonid"]}}">
                                <a href="javascript:;" class="btn  fa fa-info td-info"></a>
                                <a href="/tea_manage/open_class?lessonid={{$var["lessonid"]}}" target="_blank" >查看课程 </a>
                  </div>
                </td>
                    </tr>
                @endforeach
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
            <div class="col-xs-6 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">昵称</span>
                    <input type="text" value="" class=" form-control "  id="id_name"  placeholder="" />
                </div>
            </div>
            <div class="col-xs-6 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">实名</span>
                    <input type="text" value="" class=" form-control "  id="id_realname"  placeholder="" />
                </div>
            </div>

            <div class="col-xs-6 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">性别</span>
                    <select id="id_gender" class="form-control">
                        <option value="0" >未设置</option>
                        <option value="1" >男</option>
                        <option value="2" >女</option>
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
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">省</span>
                    <select class="form-control" id="province" name="province">
                    </select>

                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">市</span>
                    <select class="form-control" id="city" name="city">
                    </select>

                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">区(县)</span>
                    <select class="form-control" id="area" name="area">
                    </select>

                </div>
            </div>
            <div class="col-xs-12 col-md-12">
                <div class="input-group ">
                    <span class="input-group-addon">地址</span>
                    <input type="text" value="" class=" form-control "  id="id_address"  placeholder="" />
                </div>
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">教材(目前使用)</span>
                    <select class="form-control" id="id_textbook">
                    </select>

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


@endsection
