<!DOCTYPE html>
<html class="bg-blue">
    <head>
        <meta charset="UTF-8">
        <title>理优教育|面试填表 </title>
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
        </style>
    </head>

    <body class="bg-blue">

        <div class="form-box" id="login-box" style="width:1100px;margin-top: 30px; " >
            <div class="header" id="id_normal"> 您的面试登记表[{{$reg_data["phone"]}}]</div>

            <div class="body bg-gray" style="">
                <div style="max-width:1020px; margin-left: 20px; ">
                    <div class="row">
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >姓名</span>
                                <input type="text" value="{{@$reg_data['name']}}"   id="id_name"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >籍贯</span>
                                <input type="text" value="{{@$reg_data['native_place']}}"   id="id_native_place"  placeholder="" />
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
                                <span >身高(CM)</span>
                                <input type="text" value="{{@$reg_data['height']}}"   id="id_height"  placeholder="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3 col-md-2">
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
                                <span >出生日期</span>
                                <input type="text" value="{{@$reg_data['birth']}}" class="id_date" id="id_birth"  placeholder="" / >
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-1">
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
                                <span >政治面貌</span>
                                <input type="text" value="{{@$reg_data['polity']}}"   id="id_polity"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >个人手机号</span>
                                <input type="text" value="{{@$reg_data['phone']}}"   id="id_phone"  placeholder="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >英语水平</span>
                                <input type="text" value="{{@$reg_data['english']}}"   id="id_english"  placeholder="" />
                            </div>
                        </div>
              
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >健康状况</span>
                                <input type="text" value="{{@$reg_data['health_condition']}}"   id="id_health_condition"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >婚姻状况</span>
                                <input type="text" value="{{@$reg_data['marry']}}"   id="id_marry"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >子女状况</span>
                                <input type="text" value="{{@$reg_data['child']}}"   id="id_child"  placeholder="" />
                            </div>
                        </div>

                      
                    </div>

                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <div class="input-group ">
                                <span >毕业学校</span>
                                <input type="text" value="{{@$reg_data['gra_school']}}"   id="id_gra_school"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >最高学历</span>
                                <input type="text" value="{{@$reg_data['education']}}"   id="id_education"  placeholder="" />
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
                        <div class="col-xs-6 col-md-6">
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

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >邮编</span>
                                <input type="text" value="{{@$reg_data['postcodes']}}"   id="id_postcodes"  placeholder="" />
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-xs-9 col-md-9">
                            <div class="input-group ">
                                <span >现居住地详细地址</span>
                                <input type="text" value="{{@$reg_data['address']}}"   id="id_address"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >是否已参保</span>                        
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


                    <div class="row">
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >电子信箱</span>
                                <input type="text" value="{{@$reg_data['email']}}"   id="id_email"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >最快入职时间</span>
                                <input type="text" value="{{@$reg_data['join_time']}}" class="id_date"  id="id_join_time"  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >面试岗位</span>
                                <input type="text" value="{{@$reg_data['post']}}"   id="id_post"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >面试部门</span>
                                <input type="text" value="{{@$reg_data['dept']}}"   id="id_dept"  placeholder="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <div class="input-group ">
                                <span >个人特长</span>
                                <input type="text" value="{{@$reg_data['strong']}}"   id="id_strong"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <div class="input-group ">
                                <span >兴趣爱好</span>
                                <input type="text" value="{{@$reg_data['interest']}}"   id="id_interest"  placeholder="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <div class="input-group ">
                                <span >身份证号码</span>
                                <input type="text" value="{{@$reg_data['carded']}}"   id="id_carded"  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <div class="input-group ">
                                <span >建设银行卡号(若无,则不填)</span>
                                <input type="text" value="{{@$reg_data['ccb_card']}}"   id="id_ccb_card"  placeholder="" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <div class="input-group ">
                                <span >目前是否与原单位存在竞业禁止协议</span>
                                <select id="id_non_compete" name="non_compete">
                                    <option value="1" @if (isset($reg_data['non_compete']) && $reg_data['non_compete'] ==1)
                                            selected
                                    @elseif (!isset($reg_data['non_compete']))
                                            selected
                                    @else
                                            ""
                                    @endif >是</option>
                                        <option value="0" @if (isset($reg_data['non_compete']) && $reg_data['non_compete'] ==0)
                                                selected
                                        @else
                                                ""
                                        @endif >否</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <div class="input-group ">
                                <span >与原单位是否解除劳动合同</span>
                                <select id="id_is_labor" name="is_labor">
                                    <option value="1" @if (isset($reg_data['is_labor']) && $reg_data['is_labor'] ==1)
                                            selected
                                    @elseif (!isset($reg_data['is_labor']))
                                            selected
                                    @else
                                            ""
                                    @endif >是</option>
                                        <option value="0" @if (isset($reg_data['is_labor']) && $reg_data['is_labor'] ==0)
                                                selected
                                        @else
                                                ""
                                        @endif >否</option>

                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-6">
                            <div class="input-group ">
                                <span >是否有朋友介绍在理优教育</span>
                                <select id="id_is_fre" name="is_fre">
                                    <option value="1" @if (isset($reg_data['is_fre']) && $reg_data['is_fre'] ==1)
                                            selected
                                    @elseif (!isset($reg_data['is_fre']))
                                            selected
                                    @else
                                            ""
                                    @endif >是</option>
                                        <option value="0" @if (isset($reg_data['is_fre']) && $reg_data['is_fre'] ==0)
                                                selected
                                        @else
                                                ""
                                        @endif >否</option>

                                </select>

                            </div>
                        </div>
                        <div class="col-xs-6 col-md-6">
                            <div class="input-group ">
                                <span >介绍人姓名</span>
                                <input type="text" value="{{@$reg_data['fre_name']}}"   id="id_fre_name"  placeholder="" />
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <span >紧急联系人</span>
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
                        <div class="col-xs-12 col-md-12" style="font-size:18px; margin-top:20px"> 
                            <span >教育背景:(从最近教育经历开始倒序填写,具体到月)</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-2 col-md-2">
                            开始日期
                        </div>
                        <div class="col-xs-2 col-md-2">
                            结束日期
                        </div>

                        <div class="col-xs-3 col-md-3">
                            学校
                        </div>
                        <div class="col-xs-2 col-md-2">
                            专业 
                        </div>
                        <div class="col-xs-1 col-md-1">
                            教育程度
                        </div>
                        <div class="col-xs-1 col-md-1">
                            学位
                        </div>

                        <div class="col-xs-1 col-md-1">
                            教育性质
                        </div>



                    </div>

                    <div class="row   education_info">
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][0]['start_time']}}"  class="id_date" id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][0]['end_time']}}" class="id_date"  id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][0]['school']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][0]['major']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][0]['education']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][0]['degree']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][0]['nature']}}"   id=""  placeholder="" />
                            </div>
                        </div>


                    </div>
                    <div class="row   education_info">
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][1]['start_time']}}"  class="id_date" id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][1]['end_time']}}" class="id_date"  id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][1]['school']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][1]['major']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][1]['education']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][1]['degree']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][1]['nature']}}"   id=""  placeholder="" />
                            </div>
                        </div>


                    </div>
                    <div class="row   education_info">
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][2]['start_time']}}"  class="id_date" id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][2]['end_time']}}" class="id_date"  id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][2]['school']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][2]['major']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][2]['education']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][2]['degree']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['education_info'][2]['nature']}}"   id=""  placeholder="" />
                            </div>
                        </div>


                    </div>
                    

                    <div class="row">
                        <div class="col-xs-12 col-md-12" style="font-size:18px; margin-top:20px"> 
                            <span >工作经历:(从最近工作经历开始倒序填写,具体到月)</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-2 col-md-2">
                            开始日期
                        </div>
                        <div class="col-xs-2 col-md-2">
                            结束日期
                        </div>

                        <div class="col-xs-3 col-md-3">
                            工作单位
                        </div>
                        <div class="col-xs-1 col-md-1">
                            职位
                        </div>
                        <div class="col-xs-1 col-md-1">
                            离职原因
                        </div>
                        <div class="col-xs-1 col-md-1">
                            薪资
                        </div>

                        <div class="col-xs-1 col-md-1">
                            证明人/职位
                        </div>
                        <div class="col-xs-1 col-md-1">
                            证明人电话
                        </div>



                    </div>

                    <div class="row work_info">
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['start_time']}}" class="id_date"  id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['end_time']}}"  class="id_date" id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['company']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['post']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['reason']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['salary']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['voucher']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][0]['voucher_phone']}}"   id=""  placeholder="" />
                            </div>
                        </div>


                    </div>
                    <div class="row work_info">
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['start_time']}}" class="id_date"  id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['end_time']}}"  class="id_date" id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['company']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['post']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['reason']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['salary']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['voucher']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][1]['voucher_phone']}}"   id=""  placeholder="" />
                            </div>
                        </div>


                    </div>
                    <div class="row work_info">
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['start_time']}}" class="id_date"  id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-2 col-md-2">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['end_time']}}"  class="id_date" id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-3 col-md-3">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['company']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['post']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['reason']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['salary']}}"   id=""  placeholder="" />
                            </div>
                        </div>

                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['voucher']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                        <div class="col-xs-1 col-md-1">
                            <div class="input-group ">
                                <input type="text" value="{{@$reg_data['work_info'][2]['voucher_phone']}}"   id=""  placeholder="" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-12" style="font-size:18px; margin-top:20px"> 
                            <span >家庭成员关系情况</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-2 col-md-2">
                            姓名
                        </div>
                        <div class="col-xs-2 col-md-2">
                            与本人关系
                        </div>
                        <div class="col-xs-3 col-md-3">
                            工作单位
                        </div>
                        <div class="col-xs-3 col-md-3">
                            职务 
                        </div>
                        <div class="col-xs-2 col-md-2">
                            联系电话
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

                    
                    


                    <div class="row" >
                        <div class="col-xs-12 col-md-12" style="text-align:center;">
                            <button  class="btn btn-primary  " id="id_save">保存</button>
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
