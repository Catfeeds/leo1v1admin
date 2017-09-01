@extends('layouts.teacher_header')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <style>
     .ft14{
         font-size:14px;
     }
     .ft24{
         font-size:24px;
     }
     .ft22{
         font-size:22px;
     }
     .text-cen{
         text-align:center;
     }
     .text-top{
         vertical-align:top;
     }
     .r-border{
         border-right:1px solid #ccc;
     }
     .bor-hr{
         border-top:1px solid #ccc;
         margin-bottom:10px;
     }
     .div-mar{
         margin:10px;
     }
     .color-6{
         color:#666;
     }
     .color-red{
         color:red;
     }
     .color-blue{
         color:#00A6FF;
     }
     .div-bank{
         width:60%;
         margin:50px auto;
     }
     .btn-bank{
         display:block;
         width:100%;
     }
     .flag-baes th{
         width:20%;
         background:#E6FAFF;
         text-align:center;
     }
     .flag-baes td{
         width:30%;
         text-align:center;
     }
     input{
         border:0 solid #fff;
         width:100%;
         height:100%;
     }
    </style>
    <section class="content">
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-5 connectedSortable">
                <!-- Chat box -->
                <div class="box box-info">
                    <div class="box-header">
                    </div>
                    <div class="box-body">
                        <!-- Profile Image -->
                        <div class="box-body box-profile">
                            <img src="{{$my_info['face']}}" class="profile-user-img img-responsive img-circle" alt="">
                            <h3 class="profile-username text-center">{{$my_info['nick']}}</h3>
                            <p class="text-muted text-center">{{$my_info['level']}}星教师</p>
                        </div>
                        <div class="row text-cen">
                            <div class="col-sm-6 r-border">
                                <h3><span  class="color-blue">{{$my_info['days']}}</span><span class="ft14">天</span></h3>
                                <p class="color-6">入职天数</p>
                            </div>
                            <div class="col-sm-6">
                                <h3><span  class="color-blue">{{$my_info['normal_count']}}</span><span class="ft14">课时</span></h3>
                                <p class="color-6">总课耗</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat box -->
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title text-yellow">资料完整度</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bor-hr"></div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                            <div class="row">
                                <div class="col-sm-10">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="progress">
                                        <div class="progress-bar">
                                            <b class="ft24 text-yellow text-top">60%</b>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <p> 温馨提示：当前您还有<a href="javascript:;" class="color-red">基本信息</a>、<a href="javascript:;" class="color-red">简历</a>、<a href="javascript:;" class="color-red">资格证</a>、<a href="javascript:;" class="color-red">公校证明</a>没有补全。您的信息完整度将与您的晋升挂钩，（信息完整度只与简历和基本信息）所以请老师认真填写哦。 </p>
                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row">
                            <p class="text-cen color-6">信息完整度100%后，此模块可关闭</p>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->

                <!-- Chat box -->
                <div class="box box-info collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title text-blue" id="my_status" cur-status="{{$my_info['need_test_lesson_flag']}}">当前状态</h3>
                        <div class="box-tools pull-right">
                            <b class="color-red" data-status="full">饱和</b>
                            <b class="color-red" data-status="nofull">不饱和</b>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bor-hr"></div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                            <div class="row">
                                <div class="col-sm-12">
                                    <p data-status="full"> 您当前处于饱和状态，不会收到排课邀请，如需排课，请到控制台设置当前状态为不饱和即可。 </p>
                                    <p data-status="nofull"> 您当前处于不饱和状态，会收到排课邀请，如需不排课，请到控制台设置当前状态为饱和即可。 </p>
                                    <br>
                                    <button type="button" data-opt="set-status" data-status="full" class="btn btn-block btn-info opt-set ft22">设置不饱和</button>
                                    <button type="button" data-opt="set-status" data-status="nofull" class="btn btn-block btn-info opt-set ft22">设置饱和</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row">
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->

                <!-- Chat box -->
                <div class="box box-success collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title text-success">简历</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bor-hr"></div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                            <div class="row">
                                <div class="col-sm-12">
                                    @if ($my_info['jianli'])
                                        <button type="button" data-pdf="{{$my_info['jianli']}}" class="btn btn-block btn-info opt-show ft22">查看简历</button>
                                    @else
                                        <button type="button" id="jianli" data-val="jianli" class="btn btn-block btn-info opt-upload ft22">上传简历</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row text-cen">
                            @if ($my_info['jianli'])
                                <a href="javascript:;" class="color-6 opt-upload"  id="jianli" data-val="jianli" >重新上传</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->
                <!-- Chat box -->
                <div class="box box-success collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title text-success">资格证</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bor-hr"></div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                            <div class="row">
                                <div class="col-sm-12">
                                    @if (@$my_info['seniority'])
                                        <button type="button" data-pdf="{{$my_info['seniority']}}" class="btn btn-block btn-info opt-show ft22">查看资格证</button>
                                    @else
                                        <button type="button" id="seniority" data-val="seniority" class="btn btn-block btn-info opt-upload ft22">上传资格证</button>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row">
                            @if (@$my_info['seniority'])
                                <a href="javascript:;" class="color-6 opt-upload"  id="seniority" data-val="seniority" >重新上传</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->
                <!-- Chat box -->
                <div class="box box-success collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title text-success">公校证明</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bor-hr"></div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                            <div class="row">
                                <div class="col-sm-12">
                                    @if (@$my_info['prove'])
                                        <button type="button" data-pdf="{{$my_info['prove']}}" class="btn btn-block btn-info opt-show ft22">查看公校证明</button>
                                    @else
                                        <button type="button" id="prove" data-val="prove" class="btn btn-block btn-info opt-upload ft22">上传公校证明</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row">
                            @if (@$my_info['prove'])
                                <a href="javascript:;" class="color-6 opt-upload"  id="prove" data-val="prove" >重新上传</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->
                <!-- Chat box -->
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title text-blue">教师介绍</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bor-hr"></div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                            <div class="row">
                                <div class="col-sm-12">
                                    托管制学习体系
                                    媲美学校教学体系，覆盖学习的课前、课中和课后
                                    帮助学员高效提分，帮助家长规划学员学习，指导学员成长。
                                    将学生交给学校，将学习交给理优。 
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row">
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->
                <!-- Chat box -->
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title text-yellow">教学特长</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bor-hr"></div>
                    <div class="box-body border-radius-none">
                        <div class="chart" style="height: 100px;">
                            <div class="row">
                                <div class="col-sm-12">
                                    <span class="badge bg-green">特长1</span>
                                    <span class="badge bg-info">特长2</span>
                                    <span class="badge bg-red">特长3</span>
                                    <span class="badge bg-blue">特长4</span>
                                    <span class="badge bg-yellow">特长5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row">
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->
            </section>
            <!-- right col -->
            <section class="col-lg-7 connectedSortable">
                <!-- Chat box -->
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title text-blue">基本信息</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool opt-edit" data-name="user-info"><i class="fa fa-edit"></i>&nbsp;<span class="color-6 ft14">编辑</span>
                            </button>
                            <button type="button" class="btn btn-box-tool hide color-6" data-toggle="modal" data-target="#modal-default"><span class="color-6 ft14">取消</span></button>
                            <button type="button" class="btn btn-box-tool opt-submit hide text-blue" data-name="user-info"><span class="color-6 ft14">保存</span></button>
                        </div>
                    </div>
                    <div class="bor-hr"></div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                            <div class="row">
                                <form class="col-sm-12 flag-baes" data-sub="edit_teacher_info" id="user-info">
                                    <p class="color-6">个人信息</p>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>ID</th>
                                            <td id="teacherid">56123</td>
                                            <th>姓名</th>
                                            <td>
                                                <span>{{$my_info['nick']}}</span>
                                                <input type="text" name="nick" class="hide" value="{{$my_info['nick']}}">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>性别</th>
                                            <td class="form-group">
                                                <span>{{$my_info['gender_str']}}</span>
                                                <select name="gender" class="form-control hide">
                                                    <option value="0" @if($my_info['gender'] == 0) selected @endif >保密</option>
                                                        <option value="1" @if($my_info['gender'] == 1) selected @endif >男</option>
                                                            <option value="2" @if($my_info['gender'] == 2) selected @endif >女</option>
                                                </select>
                                            </td>
                                            <th>出生日期</th>
                                            <td>
                                                <span>{{$my_info['birth']}}</span>
                                                <input type="text" name="birth" class="hide" value="{{$my_info['birth']}}" placeholder="例如：19900101">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>邮箱</th>
                                            <td>
                                                <span>{{$my_info['email']}}</span>
                                                <input type="email" name="email" class="hide" value="{{$my_info['email']}}">
                                            </td>
                                            <th>推荐人</th>
                                            <td> {{$my_info['teacher_ref_type_str']}} </td>
                                        </tr>
                                        <tr>
                                            <th>手机号</th>
                                            <td>
                                                <span>{{$my_info['phone']}}</span>
                                                <input type="tel" name="phone" class="hide" value="{{$my_info['phone']}}">
                                                @if ($my_info['wx_openid'])
                                                    <a href="javascript:;"  data-toggle="modal" data-target="#modal-band-wx" class="color-red band-wx">未绑定</a>
                                                @endif
                                            </td>
                                            <th>信息创建时间</th>
                                            <td> {{$my_info['create_time']}} </td>
                                        </tr>
                                    </table>
                                    <p class="color-6">教学信息</p>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>教龄</th>
                                            <td>
                                                <span>{{$my_info['work_year']}}</span>
                                                <input type="text" name="work_year" class="hide" value="{{$my_info['work_year']}}">
                                            </td>
                                            <th>教材版本</th>
                                            <td>{{$my_info['textbook_type_str']}}</td>
                                        </tr>
                                        <tr>
                                            <th>科目</th>
                                            <td> {{$my_info['subject_str']}} </td>
                                            <th>年级段</th>
                                            <td> {{$my_info['grade_part_ex']}} </td>
                                        </tr>
                                        <tr>
                                            <th>方言备注</th>
                                            <td>
                                                <span>{{$my_info['dialect_notes']}}</span>
                                                <input type="text" name="dialect_notes" class="hide" value="{{$my_info['dialect_notes']}}" placeholder="未填写">

                                            </td>
                                        </tr>
                                    </table>
                                    <p class="color-6">教学背景</p>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>身份</th>
                                            <td>
                                                <span>{{$my_info['identity_str']}}</span>
                                                <input type="text" name="identity_str" value="{{$my_info['identity_str']}}" class="hide" placeholder="未填写">
                                            </td>
                                            <th>毕业院校</th>
                                            <td>
                                                <span>{{$my_info['school']}}</span>
                                                <input type="text" name="school" class="hide" value="{{$my_info['school']}}" placeholder="未填写">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>最高学历</th>
                                            <td>
                                                <span class="color-6">未填写</span>
                                                <input type="text" name="noname" class="hide" placeholder="未填写">
                                            </td>

                                            <th>专业</th>
                                            <td>
                                                <span class="color-6">未填写</span>
                                                <input type="text" name="noname" class="hide" placeholder="未填写">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>兴趣爱好</th>
                                            <td>
                                                <span class="color-6">未填写</span>
                                                <input type="text" name="noname" class="hide" placeholder="未填写">
                                            </td>
                                            <th>个人特长</th>
                                            <td>
                                                <span class="color-6">未填写</span>
                                                <input type="text" name="noname" class="hide" placeholder="未填写">
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row">
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->

                <!-- Chat box -->
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title text-blue">银行卡信息</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bor-hr"></div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                            <div class="row">
                                <form class="col-sm-12 flag-baes
                                             @if (!$my_info['bankcard'])
                                             hide
                                             @endif
                                             " id="bank-info">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>持卡人</th>
                                            <td>
                                                <span>{{$my_info['bank_account']}}</span>
                                                <input type="text" name="bank_account" class="hide" value="{{$my_info['bank_account']}}" placeholder="未绑定">
                                            </td>
                                            <th>身份证号</th>
                                            <td>
                                                <span>{{$my_info['idcard']}}</span>
                                                <input type="text" name="idcard" class="hide" value="{{$my_info['idcard']}}"  placeholder="未绑定">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>银行卡类型</th>
                                            <td>
                                                <span>{{$my_info['bank_type']}}</span>
                                                <select name="bank_type" class="form-control hide">
                                                        <option>中国建设银行</option>
                                                        <option>中国工商银行</option>
                                                        <option>中国农业银行</option>
                                                        <option>交通银行</option>
                                                        <option>招商银行</option>
                                                        <option>中国银行</option>
                                                        <option>中国邮政</option>
                                                    </select>

                                                </td>
                                                <th>支行名称</th>
                                                <td>
                                                    <span>{{$my_info['bank_address']}}</span>
                                                    <input type="text" name="bank_address" class="hide" value="{{$my_info['bank_address']}}"  placeholder="未绑定">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>开户省</th>
                                                <td>
                                                    <span>{{$my_info['bank_province']}}</span>
                                                    <input type="text" name="bank_province" class="hide" value="{{$my_info['bank_province']}}"  placeholder="未绑定">

                                                </td>
                                                <th>开户市</th>
                                                <td>
                                                    <span>{{$my_info['bank_city']}}</span>
                                                    <input type="text" name="bank_city" class="hide" value="{{$my_info['bank_city']}}" placeholder="未绑定">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>卡号</th>
                                                <td>
                                                    <span>{{$my_info['bankcard']}}</span>
                                                    <input type="text" name="bankcard" class="hide" value="{{$my_info['bankcard']}}" placeholder="未绑定">
                                                </td>
                                                <th>预留手机号</th>
                                                <td>
                                                    <span>{{$my_info['bank_phone']}}</span>
                                                    <input type="text" name="bank_phone" class="hide" value="{{$my_info['bank_phone']}}" placeholder="未绑定">
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                        <div class="div-bank text-cen
                                                    @if ($my_info['bankcard'])
                                                    hide
                                                    @endif
                                                    ">
                                        <button type="button" class="btn btn-info btn-bank ft22">绑定银行卡</button>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        @if ($my_info['bankcard'])
                            <div class="row text-cen">
                                <p>如需<a class="text-blue opt-bank" data-name="bank-info" href="javascript:;">更改银行卡</a>，请务必在每月5日之前更改，否则将会发到旧的银行卡</p>
                            </div>
                            @endif
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->

                <!-- Chat box -->
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title text-blue">转化率</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bor-hr"></div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width:20%">分类</th>
                                            <th style="width:60%">转化率</th>
                                            <th style="width:20%">百分比</th>
                                        </tr>
                                        <tr>
                                            <td>新签</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{$my_info['test_transfor_per']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$my_info['test_transfor_per']}}%">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-yellow">{{$my_info['test_transfor_per']}}%</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box  -->

                <!-- Chat box -->
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title text-success">上课情况</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bor-hr"></div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="col-sm-3 label label-warning div-mar">
                                        <h3>{{$my_info['leave_count']}}次</h3>
                                        <p>请假次数</p>
                                    </div>
                                    <div class="col-sm-3 label label-info div-mar">
                                        <h3>{{$my_info['late_count']}}次</h3>
                                        <p>迟到次数</p>
                                    </div>
                                    <div class="col-sm-3 label label-success div-mar">
                                        <h3>{{$my_info['noevaluate_count']}}次</h3>
                                        <p>未评价次数</p>
                                    </div>
                                    <div class="col-sm-3 label label-primary div-mar">
                                        <h3>{{$my_info['change_count']}}次</h3>
                                        <p>被换</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->

                <div class="box box-info direct-chat direct-chat-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title text-blue">课堂评分</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts"
                                    data-widget="chat-pane-toggle">
                                <i class="fa fa-question"></i></button>
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="bor-hr"></div>
                    <div class="box-body">
                        <div class="chart col-sm-12">
                            <div class="row">
                                <div class="col-sm-6">模拟试听课:<span class="ft24">96分</span></div>
                                <div class="col-sm-6">第一次试听课平均分:<span class="ft24">96分</span></div>
                                <div class="col-sm-6">第五次试听课平均分:<span class="ft24">96分</span></div>
                                <div class="col-sm-6">第八次试听课平均分:<span class="ft24">96分</span></div>
                                <div class="col-sm-6">第一次常规课平均分:<span class="ft24">96分</span></div>
                                <div class="col-sm-6">第五次常规课评价分:<span class="ft24">96分</span></div>
                            </div>
                        </div>
                        <div class="direct-chat-contacts">
                            <div class="col-sm-12 div-pad text-black">
                                <h5>平均分=所有学生第N次分数和/所有学生</h5>
                                <p>例如：李老师带了三个学生A、B、C。</p>
                                <div class="col-sm-offset-1">
                                    <p>A第五次试听课分数为90<br/>
                                    B第五次试听课分数为92<br/>
                                    C第五次试听课分数为88<br/>
                                    那么，李老师的第五次试听课平均分为(90+92+88)/3=90分</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                    </div>
                    <!-- /.box-footer-->
                </div>
                <!-- /.col -->
            </section>
            <!-- /.right -->
        </div>
    </section>
@endsection

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content" style="width:50%;margin:0 auto">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-cen">
                <div>
                    <img src="/img/warning.png" />
                </div>
                <h5 class="color-red">您还未保存信息，确定要退出吗？</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default col-sm-5 pull-left" data-refresh="">确认</button>
                <button type="button" class="btn btn-info col-sm-5 pull-right" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

<div class="modal fade" id="modal-band-wx">
    <div class="modal-dialog">
        <div class="modal-content" style="width:50%;margin:0 auto">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-cen">
                <div>
                    <img src="/img/band-wx.jpg" height="150"/>
                </div>
                <p class="text-blue text-cen">扫描并绑定
                    <br/>
                    理优1对1老师帮
                </p>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->

