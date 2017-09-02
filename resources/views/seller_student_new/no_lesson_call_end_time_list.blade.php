@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >xx</span>
                        <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>lessonid </td>
                    <td>学生 </td>
                    <td>学生电话 </td>
                    <td>销售 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["lessonid"]}} </td>
                        <td>{{@$var["stu_nick"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$admin_nick}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a title="手机拨打" class=" fa-phone  opt-telphone   "></a>
                                <a title="刷新回访"  class="fa fa-undo opt-undo-test-lesson "></a>
                                <a title="解锁"  class="" id="id_set_call_end_time">解</a>
                                <!-- <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                     <a class="fa fa-times opt-del" title="删除"> </a> -->
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

