@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<section class="content">

    <div class="row">
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="stu_sel form-control" id="id_grade" >
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">回访类型</span>
                <select class="stu_sel form-control" id="id_revisit_type" >
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group ">
                <span >日期</span>
                <input type="text" id="id_start_date" class="opt-change"/>
                <span >-</span>
                <input type="text" id="id_end_date" class="opt-change"/>
            </div>
        </div>

        <div class="col-xs-6 col-md-2" >
            <div class="input-group ">
                <span >助教</span>
                <input id="id_assistantid"  /> 
            </div>
        </div>
        <div class="col-xs-6 col-md-2" >
            <div class="input-group ">
                <span >回访人</span>
                <input id="id_revisit_assistantid"  /> 
            </div>
        </div>


        <div class="col-xs-6 col-md-3">

            <div class="input-group ">
                <input type="text" value="" class=" form-control click_on put_name for_input"  data-field="user_name" id="id_user_name"  placeholder="学生/家长姓名,userid, 回车查找" />
                <div class=" input-group-btn ">
                    <button id="id_search_user" type="submit"  class="btn  btn-primary"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>

        <div class="col-xs-6 col-md-3" >
            <div class="input-group ">


                <input type="text" value="" class="form-control click_on put_phone for_input"   placeholder="联系电话 回车查找" data-field="phone"   id="id_phone"  />
                <div class=" input-group-btn ">
                    <button id="id_search_tel" type="submit"  class="btn  btn-primary"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>

    </div>
    <hr/> 

    <table   class=" common-table "   >
        <thead>
            <tr>
                <td  >userid</td>
                <td  >助教id</td>
                <td  >姓名</td>
                <td  >电话</td>
                <td  >回访类型</td>
                <td  >年级</td>
                <td  >回访时间</td>
                <td  >回访内容</td>
                <td  >回访人</td>
                <td  >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td >{{$var["userid"]}}</td>
                    <td >{{$var["assistant_nick"]}}</td>
                    <td >{{$var["nick"]}}</td>
                    <td >{{$var["phone"]}}</td>
                    <td >{{$var["revisit_type_str"]}}</td>
                    <td >{{$var["grade_str"]}}</td>
                    <td >{{$var["revisit_time"]}}</td>
                    <td >{{$var["operator_note"]}}</td>
                    <td >{{$var["sys_operator"]}}</td>
                    <td >
                        <div
                            {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a class="fa-comments opt-return-back-list " title="回访列表" ></a>
                        </div>
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")

    @include("layouts.return_record_add")

    <div class="dlg_set_dynamic_passwd" style="display:none">
        <div class="row ">
            <div class="input-group">
                <label class="stu_nick"> </label>
                <label class="stu_phone"> </label>
            </div>
        </div>
        <div class="row">
            <div class="input-group">
                <span class="input-group-addon">请输入临时密码</span>
                <input type="text" class="dynamic_passwd" />
            </div>
        </div>
    </div>
    <div style="display:none;" class="cl_dlg_change_type">
        <div class="mesg_alertCont">
    	        <table border="0" cellspacing="0" width="100%" style="border-collapse:collapse;" class="stu_tab02">
                    <tr>
                        <td width="30%">设置测试学员：</td>
                        <td width="70%" class="align_l"><select id="id_set_channel"><option value="-1">请选择</option></select></td>
                        
                    </tr>
                </table>
        </div>
    </div>
    <div style="display:none;" class="cl_dlg_stu_origin">
        <div class="mesg_alertCont">
    	        <table border="0" cellspacing="0" width="100%" style="border-collapse:collapse;" class="stu_tab02">
                    <tr>
                        <td width="30%">设置渠道：</td>
                        <td width="70%" class="align_l">
                            <select id="id_stu_origin">
                                <option value="-1">请选择</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">userid：</td>
                        <td width="70%" class="align_l"><input id="id_origin_userid"></input></td>
                    </tr>

                </table>
        </div>
    </div>

@endsection


