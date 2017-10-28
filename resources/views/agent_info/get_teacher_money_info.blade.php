@extends('layouts.teacher_header')
@section('content')
    <script type="text/javascript" >
     var month_info = <?php  echo json_encode($money_list); ?> ;
    </script>
    <section class="content li-section" id="id_section">
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
                    <div class="box-body" style="overflow:hidden">
                        <div class="direct-chat-messages col-sm-12" style="height:100%">
                            <div class="row">
                                <div class="col-lg-3 col-xs-6">
                                    <!-- small box -->
                                    <div class="small-box bg-lyellow color-fff">
                                        <div class="col-xs-12 text-right">
                                            <!-- <i class="fa fa-question"></i> -->
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
                                <div class="col-lg-3 col-xs-6">
                                    <!-- small box -->
                                    <div class="small-box bg-linfo color-fff">
                                        <div class="col-xs-12 text-right">
                                            <br>
                                        </div>
                                        <div class="inner">
                                            <p>额外奖励</p>
                                            <h4 id="id_reward_money">0元</h4>
                                        </div>
                                    </div>
                                </div>
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
                            <thead>
                                <tr>
                                    <th class='text-cen' style='width:25px'></th>
                                    <th class='text-cen' style='width:150px'>分类</th>
                                    <th class='text-cen' >名称</th>
                                    <th class='text-cen' >时间</th>
                                    <th class='text-cen' >状态</th>
                                    <th class='text-cen' >扣款</th>
                                    <th class='text-cen' >到账金额</th>
                                </tr>
                            </thead>
                            @foreach($money_list as $list_key=>$list_val)
                                <tbody data-date="{{$list_val['date']}}" class="date-tbody">
                                    @if(isset($list_val['list']) && is_array($list_val['list']))
                                        @foreach($list_val['list'] as $l_key=>$l_val)
                                            <tr>
                                                <td>
                                                    <button type='button' class="btn btn-box-tool show_key"
                                                            data-show_key="{{$l_key}}_{{$list_val['date']}}">
                                                        <i class='fa fa-plus'></i>
                                                    </button>
                                                </td>
                                                <td>{{$l_val['key_str']}}</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @foreach($l_val as $k=>$v)
                                                @if($k!=="key_str")
                                                    <tr class="{{$l_key}}_{{$list_val['date']}}">
                                                        <td></td>
                                                        <td>——</td>
                                                        <td>{{$v['name']}}</td>
                                                        <td>{{$v['time']}}</td>
                                                        <td>{{$v['status_info']}}</td>
                                                        <td>{{$v['cost']}}</td>
                                                        <td>{{$v['money']}}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    @endif
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
