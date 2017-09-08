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
                        <h3 class="box-title color-blue no-money-title">暂无薪资</h3>
                        <div style="text-align:center;" class="color-blue has-money">
                            <button type="button" class="btn btn-box-tool color-blue left ft24" id="id_prev_year">
                                <i class="fa fa-angle-left"></i>
                            </button>
                            <h3 class="box-title">
                                <span id="id_year"></span>
                            </h3>
                            <button type="button" class="btn btn-box-tool color-blue right ft24" id="id_next_year">
                                <i class="fa fa-angle-right"></i>
                            </button>
                        </div>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive has-money" >
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
                                            <h4 id="id_teacher_level">{{$teacher_level_str}}</h4>
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
                                            <h4 id="id_normal_lesson_total">0课时</h4>
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
                                            <h4 id="id_trial_lesson_total">0课时</h4>
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
                                            <h4 id="id_all_money">0元</h4>
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
                        <table class='table table-bordered text-cen' id="id_teacher_money_list">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
