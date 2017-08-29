@extends('layouts.teacher_header')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Line Chart</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="line-chart" style="height: 300px;"></div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title text-green">薪资情况</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-yellow">
                                    <div class="col-xs-12 text-right">
                                        <i class="fa fa-question"></i>
                                    </div>
                                    <div class="inner">
                                        <p>教师等级</p>
                                        <h4>高级教师</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-blue">
                                    <div class="col-xs-12 text-right">
                                       初三 <i class="fa fa-plus"></i>
                                    </div>
                                    <div class="inner">
                                        <p>基本薪资</p>
                                        <h4>20.00/每节课</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-green">
                                    <div class="col-xs-12 text-right">
                                        <i class="fa fa-question"></i>
                                    </div>
                                    <div class="inner">
                                        <p>本月课耗</p>
                                        <h4>66.5课时</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-red">
                                    <div class="col-xs-12 text-right">
                                        <i class="fa fa-question"></i>
                                    </div>
                                    <div class="inner">
                                        <p>课耗奖励</p>
                                        <h4>每节课20元</h4>
                                    </div>
                                </div>
                            </div>
                            <!-- ./col -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <div class="col-sm-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title text-blue">薪资详情</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width:25px"></th>
                                <th>分类</th>
                                <th>姓名</th>
                                <th>时间</th>
                                <th>状态</th>
                                <th>金额</th>
                                <th>操作</th>
                            </tr>
                            <tr>
                                <td>
                                    <button data-key="key1" type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
                                    </button>

                                </td>
                                <td>推荐</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <button data-key="key2" type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
                                    </button>

                                </td>
                                <td>奖金</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="key2">
                                <td></td>
                                <td>荣誉榜奖金</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="hide key2">
                                <td></td>
                                <td>试听课奖金</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="key2">
                                <td> </td>
                                <td>试听培训奖金</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>
                                    <button data-key="key3" type="button" class="btn btn-box-tool"><i class="fa fa-minus"></i>
                                    </button>

                                </td>
                                <td>补偿</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="key3">
                                <td> </td>
                                <td>90分钟课程补偿</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="key3">
                                <td> </td>
                                <td>工资补偿</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>
                                    <button data-key="key4" type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
                                    </button>

                                </td>
                                <td>课程扣款</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <button data-key="key5" type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
                                    </button>

                                </td>
                                <td>平台管理费</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <button data-key="key6" type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
                                    </button>

                                </td>
                                <td>试听课程</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <button data-key="key7" type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i>
                                    </button>

                                </td>
                                <td>常规课程</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>




        </div>
    </section>

@endsection
