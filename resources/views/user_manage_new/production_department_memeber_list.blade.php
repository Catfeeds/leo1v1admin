@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
	<script type="text/javascript" src="/source/jquery.fancybox.js"></script>
 	<link rel="stylesheet" type="text/css" href="/source/jquery.fancybox.css" media="screen" />
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-md-3 col-xs-0" data-always_show="1">
                    <div class="input-group col-sm-12"  >
                        <input  id="id_user_info" type="text" value="" class="form-control opt-change"  placeholder="输入用户名/电话，回车查找" />
                    </div>
                </div>
                <div class="col-md-2 col-xs-0">
                    <div class="input-group ">
                        <span>部门</span>
                        <select class="opt-change" id="id_department">
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-xs-0">
                    <div class="input-group ">
                        <span>岗位</span>
                        <select class="opt-change" id="id_post">
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-xs-0">
                    <div class="input-group ">
                        <span>小组</span>
                        <select class="opt-change" id="id_department_group">
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">账号</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td> 序号 </td>
                    <td> 工号  </td>
                    <td> 中文名 </td>
                    <td> 英文名 </td>
                    <td> 性别 </td>
                    <td> 所属公司 </td>
                    <td> 学历 </td>
                    <td> 员工级别 </td>
                    <td> 毕业院校 </td>
                    <td> 专业 </td>
                    <td> 身份证号码 </td>
                    <td> 入职日期 </td>
                    <td> 转正日期 </td>
                    <td> 劳动合同结束日期</td>
                    <td> 岗位</td>
                    <td> 部门</td>
                    <td> 小组</td>
                    <!-- <td> 基本工资</td>
                         <td> 绩效工资</td>
                         <td> 转正基本工资</td>
                         <td> 转正绩效工资</td>
                         <td> 常用手机</td>
                       -->
                    <td> 公司邮箱</td>
                    <td> 个人邮箱</td>
                    <td> 备注</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id"]}} </td>
                        <td>{{@$var["uid"]}} </td>
                        <td>{{@$var["name"]}} </td>                        
                        <td>{{@$var["account"]}} </td>                        
                        <td>{{@$var["gender_str"]}} </td>
                        <td>{{@$var["company_str"]}} </td>
                        <td>{{@$var["education_str"]}} </td>
                        <td>{{@$var["employee_level_str"]}} </td>
                        <td>{{@$var["gra_school"]}} </td>
                        <td>{{@$var["gra_major"]}} </td>
                        <td>{{@$var["identity_card"]}} </td>
                        <td>{{@$var["create_time_str"]}} </td>
                        <td>{{@$var["become_full_member_time_str"]}} </td>
                        <td>{{@$var["order_end_time_str"]}} </td>
                        <td>{{@$var["post_str"]}} </td>
                        <td>{{@$var["department_str"]}} </td>
                        <td>{{@$var["department_group_str"]}} </td>
                        <!--<td>{{@$var["basic_pay"]/100}} </td>
                             <td>{{@$var["merit_pay"]/100}} </td>
                             <td>{{@$var["post_basic_pay"]/100}} </td>
                             <td>{{@$var["post_merit_pay"]/100}} </td>

                             <td>{{@$var["phone"]}} </td>
                           -->
                        <td>{{@$var["email"]}} </td>
                        <td>{{@$var["personal_email"]}} </td>
                        <td>{{@$var["personal_desc"]}} </td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a href="javascript:;" title="编辑" class="fa fa-edit edit-manage"></a>
                                <a href="javascript:;" class="read_resume">查看简历</a> 


                            </div> 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
       
        @include("layouts.page")
    </section>
    
@endsection

