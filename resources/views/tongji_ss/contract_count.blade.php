@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
	  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
	  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.pie.min.js"></script>
    <script type="text/javascript" >
     
     var g_grade_map= <?php  echo json_encode ($grade_map); ?> ;
     var g_grade_count_map= <?php  echo json_encode ($grade_count_map); ?> ;
     var g_subject_count_map= <?php  echo json_encode ($subject_count_map); ?> ;
     var g_subject_map= <?php  echo json_encode ($subject_map); ?> ;
     var g_adminid_right= <?php  echo json_encode($adminid_right); ?> ;
     var g_phone_map= <?php  echo json_encode ($phone_map); ?> ;
     var g_phone_count_map= <?php  echo json_encode ($phone_count_map); ?> ;
    </script>

    <section class="content">
        <div class="row">

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">分类</span>
                    <select class="c_sel form-control opt-change" id="id_contract_type">
                        <option value="-2">正式1v1课程</option>
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">1v1分类</span>
                    <select class="c_sel form-control opt-change" id="id_from_type">
                    </select>
                </div>
            </div>

            <div class="col-xs-12 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">下单时间:</span>
                    <input type="text" class=" form-control " id="id_start_time" />
                    <span class="input-group-addon">-</span>
                    <input type="text" class=" form-control opt-change "  id="id_end_time" />
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">检查状态</span>
                    <select class="c_sel form-control opt-change" id="id_check_money_flag">
                    </select>
                </div>
            </div>
            

        </div>
        <div class="row">
            <div class="col-xs-1 col-md-2">
                <div class="input-group ">
                    <span >学生</span>
                    <input id="id_studentid"  /> 
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">角色</span>
                    <select class="c_sel form-control" id="id_account_role">
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input type="text" value="" class="form-control opt-change"  data-field="origin" id="id_origin"  placeholder="渠道, 回车查找" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input id="id_sys_operator"  class="opt-change" placeholder="下单人,回车搜索" /> 
                </div>
            </div>

            <div  class="col-xs-6 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">销售选择</span>
                    <input class="opt-change form-control" id="id_seller_groupid_ex" />
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">测试用户</span>
                    <select class="opt-change form-control" id="id_is_test_user" >
                    </select>
                </div>
            </div>
        </div>
        <hr />
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td>类型 </td>
                        <td>总监 </td>
                        <td>经理 </td>
                        <td>小组 </td>
                        <td>成员 </td>
                        <td>是否离职 </td>
                        <td>总金额 </td>
                        <td>已确认金额 </td>
                        <td>未确认金额 </td>
                        <td>新签 </td>
                        <td>转介绍 </td>
                        <td>常规续费 </td>
                        <td>扩课续费 </td>
                        <td> 操作  </td> </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr class="{{$var["level"]}}">
                            <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>

                        <td  data-class_name="{{@$var["first_group_name_class"]}}" class=" first_group_name  {{$var["main_type_class"]}} {{@$var["first_group_name_class"]}}  " >{{@$var["first_group_name"]}}</td>

                        <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name {{@$var["first_group_name_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>

                        <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>

                        <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}} </td>

                            <td> {!! @$var["del_flag_str"] !!}</td>
                            <td> {{@$var["all_price"]}} </td>
                            <td> {{@$var["all_price_suc"]}}</td>
                            <td> {{@$var["all_price_fail"]}} </td>
                            <td> {{@$var["new_price"]}}</td>
                            <td> {{@$var["transfer_introduction_price"]}}</td>
                            <td> {{@$var["normal_price"]}}</td>
                            <td> {{@$var["extend_price"]}}</td>
                            <td><div class=" row-data"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                <a class="fa-comments opt-comments" > </a> 
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>

        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div> 年级-合同金额 </div>
		            <div id="id_grade_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 金额</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6 col-md-3">
                <div> 年级-合同数量 </div>
		            <div id="id_grade_pic_count" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div class="col-xs-6 col-md-3">
                <div> 科目-合同金额</div>
		            <div id="id_subject_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 金额</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
            <div class="col-xs-6 col-md-3">
                <div> 科目-合同数量</div>
		            <div id="id_subject_pic_count" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div> 电话归属地-合同金额</div>
		            <div id="id_phone_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 金额</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
            <div class="col-xs-6 col-md-3">
                <div> 电话归属地-合同数量</div>
		            <div id="id_phone_pic_count" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead> <tr> <td> 编号 </td> <td> 名称  </td><td> 个数</td> </tr> </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>


        <script src="/js/qiniu/plupload/plupload.full.min.js"></script>

@endsection

