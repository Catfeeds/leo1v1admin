@extends('layouts.tea_header')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <script type="text/javascript" > 
     var g_phone = "{{@$tea_info["phone"]}}";
     var g_teacherid   = "{{@$tea_info["teacherid"]}}";
     var g_nick = "{{@$tea_info["nick"]}}";
     var g_realname = "{{@$tea_info["realname"]}}";
     var g_gender = "{{@$tea_info["gender"]}}";
     var g_birth = "{{@$tea_info["birth_str"]}}";
     var g_work_year = "{{@$tea_info["work_year"]}}";
     var g_email = "{{@$tea_info["email"]}}";
     var g_base_intro = "{{@$tea_info["base_intro"]}}";
     var g_advantage= "{{@$tea_info["advantage"]}}";
     var g_face= "{{@$tea_info["face"]}}";
     var g_textbook_type= "{{@$tea_info["textbook_type"]}}";
     var g_grade_part_ex= "{{@$tea_info["grade_part_ex"]}}";
     var g_subject= "{{@$tea_info["subject"]}}";
     var g_putonghua_is_correctly= "{{@$tea_info["putonghua_is_correctly"]}}";
     var g_dialect_notes= "{{@$tea_info["dialect_notes"]}}";
     var g_jianli= "{{@$tea_info["jianli"]}}";
     var g_is_good_flag= "{{@$tea_info["is_good_flag"]}}";
     var account_role={{@$account_role}};
    </script>
    <style type="text/css">
     .row-td-field-name {
         padding-right: 0px;
     }

     .row-td-field-value {
         padding-left:0px;
         padding-right: 0px;
     }

     .row-td-field-name >  span {
         background-color: #eee;
         border: 1px solid #ccc;
         border-collapse: separate;
         color: #555;
         display: table-cell;
         font-size: 14px;
         font-weight: normal;
         line-height: 1;
         padding: 6px 12px;
         text-align: right;
         vertical-align: middle;
         width: 1%;
         height: 26pt;
     }

     .row-td-field-value >  span {
         border: 1px solid #ccc;
         border-collapse: separate;
         color: #555;
         display: table-cell;
         font-size: 14px;
         font-weight: normal;
         line-height: 1;
         padding: 6px 12px;
         vertical-align: middle;
         height: 26pt;
         width: 1%;
         text-align:left ;
         background-color: #FFF;
     }

     p {margin-left: 20px}
    </style>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/jquery.query.js"></script>
    <script src="/page_js/enum_map.js" type="text/javascript"></script>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-2">
                <div class="row" >
                    <div class="col-xs-6 col-md-12" style="text-align:center;" >
                        <div class="header_img">
                            <img style="border-radius:130px;width:120px; border: 3px solid #ccc;"  src="{{@$tea_info["face"]}}" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-md-10"   >
                <div style="width:98%" id="id_tea_info"
                     {!!  \App\Helper\Utils::gen_jquery_data($tea_info)  !!}
                >
                    <div class="row">
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >teacherid:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["teacherid"]}} </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >实名:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["realname"]}} </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >邮箱:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["email"]}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >昵称:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["nick"]}} </span>
                                </div>
                            </div>
                        </div>


                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >性别:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["gender_str"]}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >出生日期:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  > {{@$tea_info["birth_str"]}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >联系方式:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["phone"]}} </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >教龄:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["work_year"]}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >评价:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["rate_score"]}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >教材版本:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["textbook_type_str"]}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >年级段:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["grade_part_ex_str"]}} </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >科目:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["subject_str"]}}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >老师信息创建时间:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["create_time"]}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >普通话是否标准:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["putonghua_is_correctly_str"]}} </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6 col-md-4"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-5 row-td-field-name"  >
                                    <span >方言备注:</span>
                                </div>
                                <div class="col-xs-6 col-md-7  row-td-field-value">
                                    <span  >{{@$tea_info["dialect_notes"]}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12"  style="display:none">
                            <div class="row">
                                <div class="col-xs-6 col-md-2 row-td-field-name"  >
                                    <span >教学特长:</span>
                                </div>
                                <div class="col-xs-6 col-md-10  row-td-field-value">
                                    <span  >{{@$tea_info["advantage"]}} </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12" style="display:none" >
                            <div class="row">
                                <div class="col-xs-6 col-md-2 row-td-field-name"  >
                                    <span >教师介绍:</span>
                                </div>
                                <div class="col-xs-6 col-md-10  row-td-field-value">
                                    <span  >{{@$tea_info["base_intro"]}} </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-xs-12 col-md-12"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-2 row-td-field-name"  >
                                    <span >教学成果:</span>
                                </div>
                                <div class="col-xs-6 col-md-10  row-td-field-value">
                                    <span  >{{@$tea_info["teaching_achievement"]}} </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-md-12"  >
                            <div class="row">
                                <div class="col-xs-6 col-md-2 row-td-field-name"  >
                                    <span >家长/学员评价:</span>
                                </div>
                                <div class="col-xs-6 col-md-10  row-td-field-value">
                                    <span  >{{@$tea_info["parent_student_evaluate"]}} </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    

                    <div class="row">
                        <div class="col-xs-6 col-md-2  row-td-field-name"   > <span>操作:</span></div>
                        <div class="col-xs-6 col-md-10  row-td-field-value"  data-teacherid="{{@$tea_info['teacherid']}}">
                            @if(in_array($account_role,[4,5]) || in_array($adminid,[72,349,186,74]) || $acc=="jim")
                            <button style="margin-left:10px" id="id_set_teacher" type="button" class="btn btn-warning" >修改资料</button>
                            <button style="margin-left:10px"  id="id_upload_quiz_analyse" type="button" class="btn btn-danger" >更改经典试题(图片)</button> 
                            <button style="margin-left:10px"  id="id_upload_jianli" type="button" class="btn btn-info" >上传简历</button>
                            @endif
                            <button style="margin-left:10px"  id="id_upload_face" type="button" class="btn btn-primary" >上传头像</button> 
                            <button style="margin-left:10px"  id="id_read_jianli" type="button" class="btn btn-success" >查看简历</button> 
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-md-12 row-td-field-name"  > <span style="text-align:left">经典试题分析如下（图片）:</span></div>
                    <div class="col-xs-6 col-md-12 " >
                        <div class="header_img"><img  style="width:100%; border: 3px solid #ccc;"  src="{{@$tea_info["quiz_analyse"]}}" /></div>
                    </div>
                </div>

            </div>
        </div>

    </section>


@endsection


