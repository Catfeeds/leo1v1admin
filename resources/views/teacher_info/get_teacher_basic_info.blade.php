@extends('layouts.teacher_header')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <script type="text/javascript" src="/js/cropbox.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg.js"></script>
    <script type="text/javascript" src="/js/jquery.query.js"></script>
    <script src="/page_js/enum_map.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/css/face-upload-style.css" type="text/css" />

    <script>
     var able_edit  = <?php  echo json_encode($able_edit); ?> ;
     var g_textbook_type= "{{@$able_edit["textbook_type"]}}";
    </script>
    <section class="content li-section">
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-5 connectedSortable" style="padding-right:5px">
                <!-- Chat box -->
                <div class="box box-info-ly">
                    <div class="box-header">
                    </div>
                    <div class="box-body">
                        <!-- Profile Image -->
                        <div class="box-body box-profile">
                            <img src="{{$my_info['face']}}" class="profile-user-img img-responsive img-circle" id="face"  data-toggle="modal" data-target="#modal-default" >
                            <h3 class="profile-username text-center">{{$my_info['nick']}}</h3>
                            <p class="text-muted text-center">{{$my_info['teacher_title']}}</p>
                        </div>
                        <div class="row text-cen">
                            <div class="col-sm-6 r-border">
                                <h3><span  class="color-blue">{{$my_info['days']}}</span><span class="ft14">天</span></h3>
                                <p class="color-9">入职天数</p>
                            </div>
                            <div class="col-sm-6">
                                <h3><span  class="color-blue">{{$my_info['normal_count']}}</span><span class="ft14">课时</span></h3>
                                <p class="color-9">总课耗</p>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($show_flag)
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
                                    <div class="col-xs-10">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="{{$my_info['integrity']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$my_info['integrity']}}%">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-2">
                                        <b class="ft18 text-yellow text-top" style="line-height: 20px">{{$my_info['integrity']}}%</b>
                                    </div>
                                    <div class="col-xs-12">
                                        <p style="text-indent:2em">温馨提示：当前您还有基本信息、简历、资格证、公校证明没有补全，请老师准确填写。（资料完整度只与简历，基本信息，银行卡信息有关。）</p>
                                        <br />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer no-border">
                            <div class="row">
                                <p class="text-cen color-9">信息完整度100%后，此模块可关闭</p>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /.box (chat box) -->
                @endif

                <!-- 暂时关闭 -->
                @if(1==2)
                    <!-- Chat box -->
                    <div class="box box-info-ly collapsed-box">
                        <div class="box-header">
                            <h3 class="box-title color-blue" id="my_status" cur-status="{{$my_info['need_test_lesson_flag']}}">当前状态</h3>
                            <div class="box-tools pull-right">
                                <b class="color-red" data-status="full">饱和</b>
                                <b style="color:#00b798" data-status="nofull">不饱和</b>
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="bor-hr"></div>
                        <div class="box-body border-radius-none">
                            <div class="chart">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p data-status="full"> 你当前处于饱和状态，不会收到排课邀请，如需排课，可设置状态为不饱和</p>
                                        <p data-status="nofull">你当前处于不饱和状态，可以收到排课邀请，如需不接收排课需求，可设置状态为饱和</p>
                                        <br>
                                        <button type="button" data-opt="set-status" data-status="full" class="btn btn-block btn-info opt-set ft18">设置不饱和</button>
                                        <button type="button" data-opt="set-status" data-status="nofull" class="btn btn-block btn-info opt-set ft18">设置饱和</button>
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
                @endif
                
                <!-- Chat box -->
                <div class="box box-info-ly collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title color-blue">简历</h3>
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
                                        <button type="button" data-pdf="{{$my_info['jianli']}}" class="btn btn-block btn-info opt-show ft18">查看简历</button>
                                    @else
                                        <button type="button" id="jianli" data-val="jianli" class="btn btn-block btn-info opt-upload ft18">上传简历</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row text-cen">
                            @if ($my_info['jianli'])
                                <a href="javascript:;" class="color-9 opt-upload"  id="jianli" data-val="jianli" >重新上传</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->
                <!-- Chat box -->
                <div class="box box-info-ly collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title color-blue">资格证</h3>
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
                                        <button type="button" data-pdf="{{$my_info['seniority']}}" class="btn btn-block btn-info opt-show ft18">查看资格证</button>
                                    @else
                                        <button type="button" id="seniority" data-val="seniority" class="btn btn-block btn-info opt-upload ft18">上传资格证</button>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row text-cen">
                            @if (@$my_info['seniority'])
                                <a href="javascript:;" class="color-9 opt-upload"  id="seniority" data-val="seniority" >重新上传</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->
                <!-- Chat box -->
                <div class="box box-info-ly collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title color-blue">公校证明</h3>
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
                                        <button type="button" data-pdf="{{$my_info['prove']}}" class="btn btn-block btn-info opt-show ft18">查看公校证明</button>
                                    @else
                                        <button type="button" id="prove" data-val="prove" class="btn btn-block btn-info opt-upload ft18">上传公校证明</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row text-cen">
                            @if (@$my_info['prove'])
                                <a href="javascript:;" class="color-9 opt-upload"  id="prove" data-val="prove" >重新上传</a>
                            @endif
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->
                <!-- Chat box -->
                @if ($my_info['tags_flag'] == 'no_open' )
                    <div class="box box-info-ly">
                        <div class="box-header">
                            <h3 class="box-title color-blue">教师风格</h3>
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
                                        <p>
                                            @foreach($my_info['teacher_tags_arr'] as $val)
                                                @if ($val == '幽默风趣')
                                                    <span class="badge bg-linfo ft14">幽默风趣</span>
                                                @elseif ($val == '生动活泼')
                                                    <span class="badge bg-lgreen ft14">生动活泼</span>
                                                @elseif ($val == '循循善诱')
                                                    <span class="badge bg-lpro ft14">循循善诱</span>
                                                @elseif ($val == '细致耐心')
                                                    <span class="badge bg-lyellow ft14">细致耐心</span>
                                                @elseif ($val == '考纲熟悉')
                                                    <span class="badge bg-lgreen ft14">考纲熟悉</span>
                                                @elseif ($val == '善于互动')
                                                    <span class="badge bg-lpro ft14">善于互动</span>
                                                @elseif ($val == '没有口音')
                                                    <span class="badge bg-lyellow ft14">没有口音</span>
                                                @elseif ($val == '经验丰富')
                                                    <span class="badge bg-lpro ft14">经验丰富</span>
                                                @elseif ($val == '功底扎实')
                                                    <span class="badge bg-lyellow ft14">功底扎实</span>
                                                @endif
                                            @endforeach
                                        </p>
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
                @endif
                <!-- /.box (chat box) -->
            </section>
            <!-- right col -->
            <section class="col-lg-7 connectedSortable">
                <!-- Chat box -->
                <div class="box box-info-ly">
                    <div class="box-header">
                        <h3 class="box-title color-blue">基本信息</h3>
                        <div class="box-tools pull-right" >
                            <button type="button" class="btn btn-box-tool opt-edit"  data-name="user-info" ><i class="fa fa-edit"></i>&nbsp;<span class="color-blue ft14">编辑</span>
                            </button>
                        </div>
                    </div>
                    <div class="bor-hr"></div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                            <div class="row">
                                <div class="col-sm-12 flag-baes user-info" id="user-info">
                                    <p class="color-9"  data-sub="edit_teacher_info" >个人信息</p>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="text-cen bg-lblue" style="width:20%">ID</th>
                                            <td id="teacherid">{{@$my_info['teacherid']}}</td>
                                            <th class="text-cen bg-lblue" style="width:20%">姓名</th>
                                            <td id="teacher-name"> {!! $my_info['nick'] !!} </td>
                                        </tr>
                                        <tr>
                                            <th class="text-cen bg-lblue" >性别</th>
                                            <td class="form-group"> <span>{{$my_info['gender_str']}}</span> </td>
                                            <th class="text-cen bg-lblue" >出生日期</th>
                                            <td> {!! $my_info['birth_code'] !!} </td>
                                        </tr>
                                        <tr>
                                            <th class="text-cen bg-lblue" >邮箱</th>
                                            <td> {!! $my_info['email'] !!} </td>
                                            <th class="text-cen bg-lblue" >推荐人</th>
                                            <td> {{$my_info['teacher_ref_type_str']}} </td>
                                        </tr>
                                        <tr>
                                            <th class="text-cen bg-lblue" >手机号</th>
                                            <td>
                                                {!! $my_info['phone'] !!}
                                                @if (!$my_info['wx_openid'])
                                                    <a href="javascript:;"  data-toggle="modal" data-target="#modal-band-wx" class="color-red band-wx">未绑定</a>
                                                @else
                                                    <span class="color-blue">已绑定</span>
                                                @endif
                                            </td>
                                            <th class="text-cen bg-lblue" >信息创建时间</th>
                                            <td> {{$my_info['create_time']}} </td>
                                        </tr>
                                    </table>
                                    <p class="color-9">教学信息</p>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="text-cen bg-lblue" style="width:20%">教龄</th>
                                            <td> {!! $my_info['work_year_code'] !!} </td>
                                            <th class="text-cen bg-lblue" style="width:20%">教材版本</th>
                                            <td>{{$my_info['teacher_textbook_str']}}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-cen bg-lblue" >科目</th>
                                            <td> {{$my_info['subject_str']}} </td>
                                            <th class="text-cen bg-lblue" >年级段</th>
                                            <td> {{$my_info['grade_str']}} </td>
                                        </tr>
                                        <tr>
                                            <th class="text-cen bg-lblue" >方言备注</th>
                                            <td> {!! $my_info['dialect_notes_code'] !!} </td>
                                            <th class="text-cen bg-lblue" >所在地</th>
                                            <td> {!! $my_info['address_code'] !!} </td>
                                        </tr>
                                    </table>
                                    <p class="color-9">教学背景</p>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="text-cen bg-lblue"  style="width:20%">身份</th>
                                            <td>{{$my_info['identity_str']}}</td>
                                            <th class="text-cen bg-lblue"  style="width:20%">毕业院校</th>
                                            <td> {!! $my_info['school_code'] !!} </td>
                                        </tr>
                                        <tr>
                                            <th class="text-cen bg-lblue" >最高学历</th>
                                            <td> {{ $my_info['education_str'] }} </td>
                                            <th class="text-cen bg-lblue" >QQ</th>
                                            <td> {!! $my_info['qq_info'] !!} </td>
                                        </tr>
                                        <tr>
                                           
                                            <th class="text-cen bg-lblue" >有无教师资格证</th>
                                            <td> {!! $my_info['is_prove_str'] !!} </td>
                                            <th class="text-cen bg-lblue" >微信</th>
                                            <td> {!! $my_info['wx_name'] !!} </td>
                                        </tr>
                                    </table>
                                    <p class="color-9">教学成果</p>
                                    <table class="table table-bordered" style="border-collapse: separate;">
                                        <tr>
                                            <td rowspan="4" style="text-align: left">
                                            @if ($my_info['teaching_achievement'] == '')
                                            您暂未填写教学成果，请及时更新，谢谢。
                                            @else
                                            {!! $my_info['teaching_achievement'] !!}
                                            @endif
                                            </td>
                                        </tr>
                                    </table>
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
                <div class="box box-info-ly">
                    <div class="box-header">
                        <h3 class="box-title color-blue">银行卡信息</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bor-hr"></div>
                    <div class="box-body border-radius-none">
                        <div class="chart">
                            <div class="row">
                                <div class="col-sm-12 flag-baes
                                            @if (!$my_info['bankcard'])
                                            hide
                                            @endif
                                            bank-info">
                                    <table class="table table-bordered" data-sub="edit_teacher_bank_info">
                                        <tr>
                                            <th class="text-cen bg-lblue" >持卡人</th>
                                            <td> {!! $my_info['bank_account_code'] !!} </td>
                                            <th class="text-cen bg-lblue" >身份证号</th>
                                            <td> {!! $my_info['idcard_code'] !!} </td>
                                        </tr>
                                        <tr>
                                            <th class="text-cen bg-lblue">银行卡类型</th>
                                            <td> {!! $my_info['bank_type_code'] !!} </td>
                                            <th class="text-cen bg-lblue" >支行名称</th>
                                            <td> {!! $my_info['bank_address_code'] !!} </td>
                                        </tr>
                                        <tr>
                                            <th class="text-cen bg-lblue" >开户省</th>
                                            <td> {!! $my_info['bank_province_code'] !!} </td>
                                            <th class="text-cen bg-lblue" >开户市</th>
                                            <td> {!! $my_info['bank_city_code'] !!} </td>
                                        </tr>
                                        <tr>
                                            <th class="text-cen bg-lblue" >卡号</th>
                                            <td> {!! $my_info['bankcard_code'] !!} </td>
                                            <th class="text-cen bg-lblue" >预留手机号</th>
                                            <td> {!! $my_info['bank_phone_code'] !!} </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="div-bank text-cen
                                            @if ($my_info['bankcard'])
                                            hide
                                            @endif
                                            ">
                                    <button type="button" data-toggle="modal" class="btn btn-block btn-info ft18  opt-edit" data-name="bank-info">绑定银行卡</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        @if ($my_info['bankcard'])
                            <div class="row text-cen">
                                <p>如需<a class="color-blue opt-edit" data-name="bank-info" href="javascript:;" >更改银行卡</a>，请务必在每月5日之前更改，否则将会发到旧的银行卡</p>
                            </div>
                        @endif
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->

                @if (1>2)
                    <!-- Chat box -->
                    <div class="box box-info-ly">
                        <div class="box-header">
                            <h3 class="box-title color-blue">转化率</h3>
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
                    <div class="box box-info-ly">
                        <div class="box-header">
                            <h3 class="box-title color-blue">上课情况</h3>
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
                                        <div class="col-sm-3">
                                            <div class="col-sm-12 bg-lyellow text-cen bor-rds color-fff">
                                                <h4>{{$my_info['leave_count']}}次</h4>
                                                <p>请假次数</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="col-sm-12 bg-linfo text-cen bor-rds color-fff">
                                                <h4>{{$my_info['late_count']}}次</h4>
                                                <p>迟到次数</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="col-sm-12 bg-lgreen text-cen bor-rds color-fff">
                                                <h4>{{$my_info['noevaluate_count']}}次</h4>
                                                <p>未评价次数</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="col-sm-12 bg-lpro text-cen bor-rds color-fff">
                                                <h4>{{$my_info['change_count']}}次</h4>
                                                <p>被换次数</p>
                                            </div>
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
                    <!-- 暂时不显示内容 -->
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
                @endif
            </section>
            <!-- /.right -->
        </div>
    </section>
@endsection

<div class="modal fade" id="modal-default" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" style="width:660px;border-top:3px solid #00A6FF;border-radius:3px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title color-blue">修改头像</h3>
            </div>
            <div class="modal-body" style="border:1px solid #eee">
                <div class="container pad-l-5">
                    <div class="imageBox">
                        <div class="thumbBox"></div>
                        <div class="spinner" style="display: none">Loading...</div>
                    </div>
                    <div class="action">
                        <!-- <input type="file" id="file" style=" width: 200px">-->
                        <div class="new-contentarea tc">
                            <a href="javascript:void(0)" class="upload-img"> 上传头像</a>
                            <input type="file" class="" name="upload-file" id="upload-file" accept="image/jpg, image/png"/>
                        </div>
                        <input type="button" id="btnCrop"  class="Btnsty_peyton" value="剪切">
                        <input type="button" id="btnZoomIn" class="Btnsty_peyton" value="+"  >
                        <input type="button" id="btnZoomOut" class="Btnsty_peyton" value="-" >
                    </div>
                    <div class="cropped"></div>
                </div>
                <p class="text-cen color-9"><br>上传头像后，点击剪切会在右方生成预览图，然后点击确认即可上传头像。</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info col-xs-2 pull-right margin-lr-20 opt-submit">确认</button>
                <button type="button" class="btn btn-default col-xs-2 pull-right" data-dismiss="modal">取消</button>
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
                <p class="color-blue text-cen">扫描并绑定
                    <br/>
                    理优1对1老师帮
                </p>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->
