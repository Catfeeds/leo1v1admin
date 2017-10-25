@extends('layouts.app')
@section('content')

    <section class="content ">

        <div class="col-xs-12 col-md-12">
            <div class="panel panel-warning"  >
                <div class="panel-heading center-title ">
                    <button class="btn btn-primary push">一键推送</button>
                </div>
                <div class="panel-body">

                    <table   class="table table-bordered "   >
                        <thead>
                            <tr>
                                <td width="200px" align="center">
                                    优学优享/精品内容
                                </td>
                                <td>{{@$arr['total_new_list_num']}}</td>
                                <td width="200px" align="center">
                                    优学优享/学员反馈
                                </td>
                                <td>{{@$arr['total_test_pic_info_num']}}</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
    
@endsection

