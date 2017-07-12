@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <input type="text" value="" class=" form-control click_on put_name for_input"  data-field="user_name" id="id_user_name"  placeholder="请按姓名,手机,岗位   回车查找" />
                    </div>
                </div>
                <div class="col-md-1 col-xs-5 ">
                    <div class="input-group input-group-btn">
                        <button class="btn btn-warning id_add fa fa-plus form-control">新增面试信息</button>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td> 姓名 </td>
                    <td> 电话  </td>
                    <td> 性别  </td>
                    <td> 面试时间  </td>
                    <td> 面试岗位 </td>
                    <td> 面试部门  </td>
                    <td>  操作 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["name"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["gender_str"]}} </td>
                        <td>{{@$var["add_time"]}} </td>
                        <td>{{@$var["post"]}} </td>
                        <td>{{@$var["dept"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-user opt-info"  href="/admin_join/index?phone={{$var["phone"]}}"  title="详细信息" target="_blank"> </a>
                                <a class="fa fa-list-alt opt-set-trial-info"   title="录入试用信息" > </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>
                                <!--  <a class="fa fa-file-o opt-file"  href="/admin_join/file_print?phone={{$var["phone"]}}"  title="进入打印界面" target="_blank"> </a>
 -->                                <a class="fa fa-file-o opt-file-new"  href="/admin_join/file_print_new?phone={{$var["phone"]}}"  title="员工档案表打印" target="_blank"> </a>



                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

