<!DOCTYPE html>
<html class="bg-blue">
    <head>
        <meta charset="UTF-8">
        <title>员工档案表 </title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/css/bootstrap-dialog.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="/css/header.css" rel="stylesheet" type="text/css" />
        <link href="/js/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <style>
         .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-3, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
             min-height: 1px;
             padding-left: 0px;
             padding-right: 0px;
             position: relative;
            }
         #login-box .input-group {
            width:100%; 
         }
         .kuang{ margin:0 auto;text-align:center;font-size:20px;line-height:188px;border:1px solid #999}
        </style>
    </head>

    <body class="bg-blue">

        <div class="form-box" id="login-box" style="width:1100px;margin-top: 30px; " >
            <div class="header" id="id_normal" style="display:none"> 您的面试登记表[{{$reg_data["phone"]}}]</div>

            <div class="body bg-gray" style="">
                <div style="max-width:1020px; margin-left: 20px; ">
                    <div class="text-center" style="font-size:30px;margin-top:-45px">员工档案表</div>
                    <div class="col-xs-10 col-md-10">
                        <div class="row" style="margin-top:13px">
                            <div class="col-xs-3 col-md-3">
                                <div class="input-group ">
                                    <span >姓名</span>
                                    <input type="text" value="{{@$reg_data['name']}}"   id="id_name"  placeholder="" />
                                </div>
                            </div>
                            <div class="col-xs-3 col-md-3">
                                <div class="input-group ">
                                    <span >性别</span>
                                    <select id="id_gender" name="gender">
                                        <option value="1" @if (isset($reg_data['gender']) && $reg_data['gender'] ==1)
                                                selected
                                        @elseif (!isset($reg_data['gender']))
                                                selected
                                        @else
                                                ""
                                        @endif >男</option>
                                            <option value="2" @if (isset($reg_data['gender']) && $reg_data['gender'] ==2)
                                                    selected
                                            @else
                                                    ""
                                            @endif >女</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-3 col-md-3">
                                <div class="input-group ">
                                    <span >民族</span>
                                    <input type="text" value="{{@$reg_data['minor']}}"   id="id_minor"  placeholder="" />
                                </div>
                            </div>
                            <div class="col-xs-3 col-md-3">
                                <div class="input-group ">
                                    <span > 身高(CM)</span>
                                    <input type="text" value="{{@$reg_data['height']}}"   id="id_height"  placeholder="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3 col-md-3">
                                <div class="input-group ">
                                    <span >出生日期</span>
                                    <input type="text" value="{{@$reg_data['birth']}}" class="id_date" id="id_birth"  placeholder="" />
                                </div>
                            </div>
                            <div class="col-xs-1 col-md-1">
                                <div class="input-group ">
                                    <select id="id_birth_type" name="birth_type" >
                                        <option value="2" @if (isset($reg_data['birth_type']) && $reg_data['birth_type'] ==2)
                                                selected
                                        @elseif (!isset($reg_data['birth_type']))
                                                selected
                                        @else
                                                ""
                                        @endif >公历</option>
                                            <option value="1" @if (isset($reg_data['birth_type']) && $reg_data['birth_type'] ==1)
                                                    selected
                                            @else
                                                    ""
                                            @endif >农历</option>

                                    </select>
                                </div>   
                            </div>
                            <div class="col-xs-3 col-md-3">
                                <div class="input-group ">
                                    <span >籍贯</span>
                                    <input type="text" value="{{@$reg_data['native_place']}}"   id="id_native_place"  placeholder="" />
                                </div>
                            </div>
                            <div class="col-xs-5 col-md-5">
                                <div class="input-group ">
                                    <span >身份证号</span>
                                    <input type="text" value="{{@$reg_data['carded']}}"   id="id_carded"  placeholder="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3 col-md-3">
                                <div class="input-group ">
                                    <span > 文化程度</span>
                                    <input type="text" value="{{@$reg_data['education']}}"   id="id_education"  placeholder="" />
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-6">
                                <div class="input-group ">
                                    <span >毕业学校</span>
                                    <input type="text" value="{{@$reg_data['gra_school']}}"   id="id_gra_school"  placeholder="" />
                                </div>
                            </div>
                            <div class="col-xs-3 col-md-3">
                                <div class="input-group ">
                                    <span >专业</span>
                                    <input type="text" value="{{@$reg_data['gra_major']}}"   id="id_gra_major"  placeholder="" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-8 col-md-8">
                                <div class="input-group ">
                                    <span >现住地址</span>
                                    <input type="text" value="{{@$reg_data['address']}}"   id="id_address"  placeholder="" />
                                </div>
                            </div>
                            <div class="col-xs-2 col-md-2">
                                <div class="input-group ">
                                    <span >健康状况</span>
                                    <input type="text" value="{{@$reg_data['health_condition']}}"   id="id_health_condition"  placeholder="" />
                                </div>
                            </div>
                            <div class="col-xs-2 col-md-2">
                                <div class="input-group ">
                                    <span >婚姻状况</span>
                                    <input type="text" value="{{@$reg_data['marry']}}"   id="id_marry"  placeholder="" />
                                </div>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-xs-6 col-md-6">
                                <div class="input-group ">
                                    <span >建设银行卡号</span>
                                    <input type="text" value="{{@$reg_data['ccb_card']}}"   id="id_ccb_card"  placeholder="" />
                                </div>
                            </div>
                            <div class="col-xs-4 col-md-4">
                                <div class="input-group ">
                                    <span >邮箱</span>
                                    <input type="text" value="{{@$reg_data['email']}}"   id="id_email"  placeholder="" />
                                </div>
                            </div>

                            <div class="col-xs-2 col-md-2">
                                <div class="input-group ">
                                    <span >是否参保</span>
                                    
                                    <select id="id_is_insured" name="is_insured">
                                        <option value="1" @if (isset($reg_data['is_insured']) && $reg_data['is_insured'] ==1)
                                                selected
                                        @elseif (!isset($reg_data['is_insured']))
                                                selected
                                        @else
                                                ""
                                        @endif >是</option>
                                            <option value="0" @if (isset($reg_data['is_insured']) && $reg_data['is_insured'] ==0)
                                                    selected
                                            @else
                                                    ""
                                            @endif >否</option>

                                    </select>   
                                </div>
                            </div>

                            
                        </div>
                        
                                              
                    </div>
                    <div class="col-xs-2 col-md-2">
                        <div class="row" style="margin-top:18px">
                            <div class="col-xs-8 col-md-8" style ="width:165px;margin:0 auto;margin-left:33px;text-align:center;line-height:183px;border:1px solid #999 ">
                                
                                <span > 照片</span>
                                
                            </div>
                        </div>
                    </div>

                  
                    <div class="row">
                        <div class="col-xs-7 col-md-7">
                            <div class="input-group ">
                                <span >户籍地址</span>
                                <input type="text" value="{{@$reg_data['residence']}}"   id="id_residence"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >户口性质</span>
                                <select id="id_residence_type" name="residence_type">
                                    <option value="1" @if (isset($reg_data['residence_type']) && $reg_data['residence_type'] ==1)
                                            selected
                                    @elseif (!isset($reg_data['residence_type']))
                                            selected
                                    @else
                                            ""
                                    @endif >本埠城镇</option>
                                        <option value="2" @if (isset($reg_data['residence_type']) && $reg_data['residence_type'] ==2)
                                                selected
                                        @else
                                                ""
                                        @endif >本埠农村</option>
                                            <option value="3" @if (isset($reg_data['residence_type']) && $reg_data['residence_type'] ==3)
                                                    selected
                                            @else
                                                    ""
                                            @endif >外埠城镇</option>
                                                <option value="4" @if (isset($reg_data['residence_type']) && $reg_data['residence_type'] ==4)
                                                        selected
                                                @else
                                                        ""
                                                @endif >外埠农村</option>


                                </select>

                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <span >邮编</span>
                                <input type="text" value="{{@$reg_data['postcodes']}}"   id="id_postcodes"  placeholder="" />
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span > 入司时间</span>
                                <input type="text" value="{{@$reg_data['join_time']}}"   id="id_join_time"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >工作部门</span>
                                <input type="text" value="{{@$reg_data['dept']}}"   id="id_dept"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >职务</span>
                                <input type="text" value="{{@$reg_data['post']}}"   id="id_post"  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >本人联系电话</span>
                                <input type="text" value="{{@$reg_data['phone']}}"   id="id_phone"  placeholder="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span > 紧急联系人</span>
                                <input type="text" value="{{@$reg_data['emergency_contact_nick']}}"   id="id_emergency_contact_nick"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <div class="input-group ">
                                <span >地址</span>
                                <input type="text" value="{{@$reg_data['emergency_contact_address']}}"   id="id_emergency_contact_address"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >联系电话</span>
                                <input type="text" value="{{@$reg_data['emergency_contact_phone']}}"   id="id_emergency_contact_phone"  placeholder="" />
                            </div>
                        </div>
                    </div>

                   
                    <div class="row">
                        <div class="col-xs-12 col-md-12" style="font-size:18px; margin-top:20px;text-align:center"> 
                            <span >家 庭 状 况</span>
                        </div>
                    </div>

                    <div class="row " >
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="家庭成员"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="称谓"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="工作单位"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="职务"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="联系电话"   id=""  placeholder="" />
                            </div>
                        </div>
                  
                    </div>

                    <div class="row family_info">
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['family_info'][0]['name']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['family_info'][0]['relation']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['family_info'][0]['company']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['family_info'][0]['post']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['family_info'][0]['phone']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="row family_info">
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['family_info'][1]['name']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['family_info'][1]['relation']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['family_info'][1]['company']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['family_info'][1]['post']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['family_info'][1]['phone']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                    </div>

                   
                    <div class="row">
                        <div class="col-xs-12 col-md-12" style="font-size:18px; margin-top:20px;text-align:center"> 
                            <span >主要学习经历</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="学校"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="时间"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="专业"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="学历"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="学历类型"   id=""  placeholder="" />
                            </div>
                        </div>
                       

                    </div>

                    <div class="row   education_info">
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][0]['school']}}" id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][0]['time']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][0]['major']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][0]['education']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][0]['nature']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                    </div>
                    <div class="row   education_info">
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][1]['school']}}" id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][1]['time']}}"  id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][1]['major']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][1]['education']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][1]['nature']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                    </div>
                    <div class="row   education_info">
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][2]['school']}}" id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][2]['time']}}"  id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][2]['major']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][2]['education']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][2]['nature']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                    </div>

                                        

                    <div class="row">
                        <div class="col-xs-12 col-md-12" style="font-size:18px; margin-top:20px;text-align:center"> 
                            <span >主要工作经历</span>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="工作单位"  id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="时间"  id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="待遇"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="职务"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="离职原因"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="证明人"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="电话"   id=""  placeholder="" />
                            </div>
                        </div>
                       

                    </div>

                    <div class="row work_info">
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['company']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['time']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['salary']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['post']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['reason']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                       
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['voucher_str']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['voucher_phone']}}"   id=""  placeholder="" />
                            </div>
                        </div>


                    </div>
                    <div class="row work_info">
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['company']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['time']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['salary']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['post']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['reason']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['voucher_str']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['voucher_phone']}}"   id=""  placeholder="" />
                            </div>
                        </div>


                    </div>
                    <div class="row work_info">
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['company']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['time']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['salary']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['post']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['reason']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['voucher_str']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['voucher_phone']}}"   id=""  placeholder="" />
                            </div>
                        </div>


                    </div>

                  
                    <div class="row" style="margin-top:10px" >
                        <div class="col-xs-12 col-md-12" style="text-align:left;font-size:16px;">
                            <span>* 以下信息由公司填写</span>
                        </div>
                       
                        <div class="col-xs-2 col-md-2">
                            <div class="row" style="margin-top:10px">
                                <div class="col-xs-8 col-md-8" style ="width:145px;margin:0 auto;margin-left:15px;text-align:center;line-height:66px;border:1px solid #999 ">
                                    
                                        <span >公司录用信息</span>
                                       
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-10  col-md-10" style="margin-left:-15px">
                            <div class="row" style="margin-top:5px">
                                <div class="col-xs-4 col-md-4">
                                    <div class="input-group ">
                                        <span >试用部门</span>
                                        <input type="text" value="{{@$reg_data['trial_dept']}}"   id="id_trial_dept"  placeholder="" />
                                    </div>
                                </div>
                               
                                <div class="col-xs-8 col-md-8">
                                    <div class="input-group ">
                                        <span >试用日期</span>
                                        <input type="text" value="{{@$reg_data['trial_time']}}"   id="id_trial_time"  placeholder="" />
                                    </div>
                                </div>
                               
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-12">
                                    <div class="input-group ">
                                        <span >试用岗位</span>
                                        <input type="text" value="{{@$reg_data['trial_post']}}"  id="id_trial_post"  placeholder="" />
                                    </div>
                                </div>
                                
                            </div>
                        
                        </div>
                        <div class="col-xs-12 col-md-12" style="text-align:left;font-size:16px;">
                            <span>本人允许公司审查内容,如有虚假,公司保留无赔偿解聘的权利。</span>
                        </div>


                        <div class="col-xs-12 col-md-12" style="text-align:right;font-size:20px;margin-top:40px">
                            <span style="display:block;float:right">日期:&nbsp&nbsp&nbsp{{@$time}}</span>
                            <span style="display:block;float:right">本人签字:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
        





        <!-- jQuery 2.0.2 -->
        <script src="/js/jquery-2.1.4.js" type="text/javascript"></script>

        <script type="text/javascript" src="/page_js/admin_join/index.js?{{$_publish_version}}"></script>
        <script type="text/javascript" src="/js/jquery.datetimepicker.js"></script>

    </body>
</html>
