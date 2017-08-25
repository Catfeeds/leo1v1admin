@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-4" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="input-group ">
                        <span >老师:</span>
                        <input type="text" id="id_teacherid" class="opt-change"/>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >工资分类</span>
                        <select id="id_teacher_money_type" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >等级分类</span>
                        <select id="id_level" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary" id="id_reset_money_count">清空统计信息</button>
                </div>
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary" id="id_reset_level_count">重置等级信息</button>
                </div>
            </div>

            <div class="col-xs-12 col-md-4">
                <div class="panel panel-warning"  >
                    <div class="panel-heading">
                        本月-我的数据
                    </div>
                    <div class="panel-body">
                        <table   class="table table-bordered "   >
                            <thead>
                                <tr>
                                    <td>项目</td>
                                    <td>数值</td>
                                    <td>公司排名</td>
                                </tr>
                            </thead>
                            <tbody id="id_self_body">
                                <tr>
                                    <td>邀约数</td>
                                </tr>

                                <tr>
                                    <td>成功试听数</td>
                                </tr>
                                <tr>
                                    <td>签单数</td>
                                </tr>

                                <tr>
                                    <td>转化率</td>
                                </tr>
                                <tr>
                                    <td>试听取消率</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- <table class="common-table">
                 <tr>
                 <td></td>
                 <td>工资成本</td>
                 <td>模拟工资成本</td>
                 </tr>
                 <tr>
                 <td>本月全部老师</td>
                 </tr>
                 <tr>
                 <td>1-7月全部老师</td>
                 </tr>
                 </table> -->
        <hr />
        </div>
        <hr/>
    </section>
@endsection

