@extends('layouts.teacher_header')
@section('content')
    <script type="text/javascript" >
     var month_info = <?php  echo json_encode($money_list); ?> ;
    </script>
    <section class="content" id="id_section">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-info-ly">
                    <div class="box-header with-border">
                        <div style="text-align:center;" class="color-blue">
                            <button type="button" class="btn btn-box-tool color-blue left ft24" id="id_prev_year">
                                <i class="fa fa-angle-left"></i>&nbsp;&nbsp;
                            </button>
                            <h3 class="box-title">
                                <span id="id_year"></span>
                            </h3>
                            &nbsp;&nbsp;
                            <button type="button" class="btn btn-box-tool color-blue right ft24" id="id_next_year">
                                <i class="fa fa-angle-right"></i>
                            </button>
                        </div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="line-chart" style="height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12">
                <div class="box box-info-ly direct-chat direct-chat-success">
                    <div class="box-header with-border">
                        <h3 class="box-title color-blue">薪资情况</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="bor-hr"></div>
                    <div class="box-body">
                        <div class="direct-chat-messages col-sm-12">
                            <div class="row">
                                <div class="col-lg-3 col-xs-6">
                                    <!-- small box -->
                                    <div class="small-box bg-lyellow color-fff">
                                        <div class="col-xs-12 text-right">
                                            <!-- <i class="fa fa-question"></i> -->
                                            <br>
                                        </div>
                                        <div class="inner">
                                            <p>教师等级</p>
                                            <h4 id="id_teacher_level">高级教师</h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-xs-6">
                                    <!-- small box -->
                                    <div class="small-box bg-linfo color-fff">
                                        <div class="col-xs-12 text-right">
                                            <br>
                                        </div>
                                        <div class="inner">
                                            <p>常规课耗</p>
                                            <h4 id="id_normal_lesson_total"></h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-xs-6">
                                    <!-- small box -->
                                    <div class="small-box bg-lgreen color-fff">
                                        <div class="col-xs-12 text-right">
                                            <!-- <i class="fa fa-question"></i> -->
                                            <br>
                                        </div>
                                        <div class="inner">
                                            <p>试听课耗</p>
                                            <h4 id="id_trial_lesson_total"></h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- ./col -->
                                <div class="col-lg-3 col-xs-6">
                                    <!-- small box -->
                                    <div class="small-box bg-lpro color-fff">
                                        <div class="col-xs-12 text-right">
                                            <!-- <i class="fa fa-question"></i> -->
                                            <br>
                                        </div>
                                        <div class="inner">
                                            <p>总薪资</p>
                                            <h4 id="id_all_money"></h4>
                                        </div>
                                    </div>
                                </div>
                                <!-- ./col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <div class="direct-chat-contacts">
                        </div>
                        <!-- /.direct-chat-pane -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                    </div>
                    <!-- /.box-footer-->
                </div>
            </div>

            <div class="col-sm-12">
                <div class="box box-info-ly">
                    <div class="box-header with-border">
                        <h3 class="box-title color-blue">薪资详情</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered text-cen">
                            <tr>
                                <th class="text-cen" style="width:25px"></th>
                                <th class="text-cen" style="width:150px">分类</th>
                                <th class="text-cen">姓名</th>
                                <th class="text-cen">时间</th>
                                <th class="text-cen">状态</th>
                                <th class="text-cen">扣款 </th>
                                <th class="text-cen">金额</th>
                                <!-- <th class="text-cen">操作</th> -->
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
