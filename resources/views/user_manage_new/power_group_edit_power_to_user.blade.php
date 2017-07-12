@extends('layouts.app')
@section('content')

    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-4">

                    <section class="sidebar">
                    <ul class="sidebar-menu">

                        <li class="treeview ">
                            <a href="#"> <i class="fa fa-laptop"></i> <span>服务管理</span>
                            <i class="fa fa-angle-left pull-right"></i> </a>
                            <ul class="treeview-menu">
                                <li> <a href="/user_manage/all_users" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>所有用户</a></li>
                                <li> <a href="/user_manage/index" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>学员档案</a></li>
                                <li> <a href="/user_manage_new/account_list" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>账号登录管理</a></li>
                                <li> <a href="/user_manage/contract_list" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>合同管理</a></li>
                                <li> <a href="/user_manage/parent_archive" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>家长档案</a></li>
                                <li> <a href="/user_manage/pc_relationship" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>家长&lt;&gt;学生</a>
                                </li><li> <a href="/user_manage/ass_archive" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>学员档案-助教</a>
                                </li><li> <a href="/user_manage/count_zan" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>获赞统计</a>
                                </li><li> <a href="/user_manage/zan_info" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i>获赞类别详情</a>
                                </li>
                            </ul>
                    </section>

                </div>
            </div>
        </div>
    </section>
    
@endsection

