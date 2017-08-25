@extends('layouts.teacher_header')
@section('content')
    <style>
     .ft14{
         font-size:14px
     }
     .ft24{
         font-size:24px
     }
     .text-cen{
         text-align:center
     }
     .r-border{
         border-right:1px solid #ccc;
     }
     .bor-hr{
         border-top:1px solid #ccc
     }
     .div-pad{
         padding: 20px 10px
     }
     .div-mar{
         margin:10px
     }
     .color-6{
         color:#666
     }
     .color-red{
         color:red
     }
     .div-bank{
         width:60%;
         margin:50px auto;
     }
     .btn-bank{
         display:block;
         width:100%
     }
     .flag-baes th{
         width:20%;
         background:#39cccc;
         text-align:center
     }
     .flag-baes td{
         width:30%;
         text-align:center
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
                                <h3><span  class="text-blue">123</span><span class="ft14">天</span></h3>
                                <p>入职天数</p>
                            </div>
                            <div class="col-sm-6">
                                <h3><span  class="text-blue">123</span><span class="ft14">课时</span></h3>
                                <p>总课耗</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat box -->
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title text-yellow">资料完整度</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart" id="line-chart">
                            <div class="bor-hr"></div>
                            <div class="row div-pad">
                                <div class="col-sm-10">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <p class="ft24 text-yellow">60%</p>
                                </div>
                                <div class="col-sm-12">
                                    <p>
                                        温馨提示：当前您还有<a href="javascript:;" class="color-red">个人信息、</a><a href="javascript:;" class="color-red">简历、</a><a href="javascript:;" class="color-red">资格证</a>还没有补全，您的信息完整度将与您的晋升挂钩，所以请老师认真填写哦。
                                    </p>
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
                        <!-- /.row -->
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->

                <!-- Chat box -->
                <div class="box box-info collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title text-blue">当前状态</h3>
                        <div class="box-tools pull-right">
                            <h3 class="box-title color-red">饱和</h3>
                            <button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart" id="line-chart">
                            <div class="bor-hr"></div>
                            <div class="row div-pad">
                                <div class="col-sm-12"">
                                <p> 您当前处于饱和状态，不会收到派克邀请，如需排课，请到控制台设置当前状态为不饱和即可。 </p>
                                <br>
                                <button type="button" class="btn btn-block btn-info btn-sm">设置不饱和</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row">
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->

                <!-- Chat box -->
                <div class="box box-success collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title text-success">简历</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart" id="line-chart">
                            <div class="bor-hr"></div>
                            <div class="row div-pad">
                                <div class="col-sm-12"">
                                    <button type="button" class="btn btn-block btn-info btn-sm">查看简历</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row text-cen">
                            <a href="javascript:;" class="color-6">重新上传简历</a>
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->
                <!-- Chat box -->
                <div class="box box-success collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title text-success">资格证</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart" id="line-chart" style="height: 100px;">
                            <div class="bor-hr"></div>
                            <div class="row div-pad">
                                <div class="col-sm-12"">
                                    <button type="button" class="btn btn-block btn-info btn-sm">上传资格证</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row">
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->
                <!-- Chat box -->
                <div class="box box-success collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title text-success">公校证明</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart" id="line-chart" style="height: 100px;">
                            <div class="bor-hr"></div>
                            <div class="row div-pad">
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer no-border">
                        <div class="row">
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->
                <!-- Chat box -->
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title text-blue">教师介绍</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart" id="line-chart">
                            <div class="bor-hr"></div>
                            <div class="row div-pad">
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
                        <!-- /.row -->
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->
                <!-- Chat box -->
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title text-yellow">教学特长</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart" id="line-chart" style="height: 100px;">
                            <div class="bor-hr"></div>
                            <div class="row div-pad">
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
                        <!-- /.row -->
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
                            <button type="button" class="btn"><i class="fa fa-edit"></i>编辑
                            </button>
                        </div>
                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart" id="line-chart" >
                            <div class="bor-hr"></div>
                            <div class="row div-pad">
                                <div class="col-sm-12 flag-baes">
                                    <p class="color-6">个人信息</p>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>ID</th>
                                            <td>56123</td>
                                            <th>姓名</th>
                                            <td>姓名</td>
                                        </tr>
                                        <tr>
                                            <th>性别</th>
                                            <td><span class="color-6">未填写</span></td>
                                            <th>出生日期</th>
                                            <td>姓名</td>
                                        </tr>
                                        <tr>
                                            <th>邮箱</th>
                                            <td>56123</td>
                                            <th>推荐人</th>
                                            <td>姓名</td>
                                        </tr>
                                        <tr>
                                            <th>手机号</th>
                                            <td>56123
                                                <span class="color-red btn-box-tool">未绑定</span>
                                            </td>
                                            <th>信息创建时间</th>
                                            <td>姓名</td>
                                        </tr>
                                    </table>
                                    <p class="color-6">教学信息</p>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>教龄</th>
                                            <td><span class="color-6">未填写</span></td>
                                            <th>教材版本</th>
                                            <td><span class="color-6">未填写</span></td>
                                        </tr>
                                        <tr>
                                            <th>科目</th>
                                            <td>56123</td>
                                            <th>年级段</th>
                                            <td>姓名</td>
                                        </tr>
                                        <tr>
                                            <th>方言备注</th>
                                            <td><span class="color-6">未填写</span></td>
                                        </tr>
                                    </table>
                                    <p class="color-6">教学背景</p>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>身份</th>
                                            <td>56123</td>
                                            <th>毕业院校</th>
                                            <td><span class="color-6">未填写</span></td>
                                        </tr>
                                        <tr>
                                            <th>最高学历</th>
                                            <td><span class="color-6">未填写</span></td>
                                            <th>专业</th>
                                            <td><span class="color-6">未填写</span></td>
                                        </tr>
                                        <tr>
                                            <th>兴趣爱好</th>
                                            <td><span class="color-6">未填写</span></td>
                                            <th>个人特长</th>
                                            <td><span class="color-6">未填写</span></td>
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
                        <!-- /.row -->
                    </div>
                    <!-- /.box-footer -->
                </div>
                <!-- /.box (chat box) -->

                <!-- Chat box -->
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title text-blue">银行卡信息</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart" id="line-chart">
                            <div class="bor-hr"></div>
                            <div class="row div-pad">
                                <div class="div-bank text-cen">
                                    <button type="button" class="btn btn-info btn-bank">绑定银行卡</button>
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

                <!-- Chat box -->
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title text-blue">转化率</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart" id="line-chart">
                            <div class="bor-hr"></div>
                            <div class="row div-pad">
                                <div class="col-sm-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width:20%">分类</th>
                                            <th style="width:60%">转化率</th>
                                            <th style="width:20%">百分比</th>
                                        </tr>
                                        <tr>
                                            <td>新增</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-blue">20%</span></td>
                                        </tr>
                                        <tr>
                                            <td>扩展</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-green">40%</span></td>
                                        </tr>
                                        <tr>
                                            <td>转介绍</td>
                                            <td>
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-yellow">60%</span></td>
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
                <!-- /.box (chat box) -->

                <!-- Chat box -->
                <div class="box box-success">
                    <div class="box-header">
                        <h3 class="box-title text-success">上课情况</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body border-radius-none">
                        <div class="chart" id="line-chart">
                            <div class="bor-hr"></div>
                            <div class="row div-pad">
                                <div class="col-sm-12">
                                    <div class="col-sm-3 label label-warning div-mar">
                                        <h3>8次</h3>
                                        <p>请假次数</p>
                                    </div>
                                    <div class="col-sm-3 label label-info div-mar">
                                        <h3>3次</h3>
                                        <p>迟到次数</p>
                                    </div>
                                    <div class="col-sm-3 label label-success div-mar">
                                        <h3>6次</h3>
                                        <p>未评价次数</p>
                                    </div>
                                    <div class="col-sm-3 label label-primary div-mar">
                                        <h3>7次</h3>
                                        <p>被换</p>
                                    </div>
                                    <div class="col-sm-3 label label-danger div-mar">
                                        <h3>0次</h3>
                                        <p>退费</p>
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
                    <div class="box-body">
                        <div class="chart col-sm-12" id="line-chart">
                            <div class="bor-hr"></div>
                            <div class="row div-pad">
                                <div class="col-sm-6">模拟试听课:<span class="ft24">96分</span></div>
                                <div class="col-sm-6">模拟试听课:<span class="ft24">96分</span></div>
                                <div class="col-sm-6">模拟试听课:<span class="ft24">96分</span></div>
                                <div class="col-sm-6">模拟试听课:<span class="ft24">96分</span></div>
                                <div class="col-sm-6">模拟试听课:<span class="ft24">96分</span></div>
                                <div class="col-sm-6">模拟试听课:<span class="ft24">96分</span></div>
                            </div>
                        </div>
                        <div class="direct-chat-contacts">
                            <div class="bor-hr"></div>
                            <div class="col-sm-12 div-pad text-black">
                                <p>各种描述</p>
                                <p>各种描述</p>
                                <p>各种描述</p>
                            </div>
                        </div>
                        <!-- /.direct-chat-pane -->
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


