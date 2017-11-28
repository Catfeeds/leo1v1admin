
@extends('layouts.app')
@section('content')

    <link href="/ztree/zTreeStyle.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="/ztree/jquery.ztree.all.min.js"></script>
    <script type="text/javascript">
     var ext = <?php echo json_encode($ext);?>;
     var zNodes = <?php echo json_encode($info)?>;
	  </script>
<section class='content'>
    <div> <!-- search ... -->
        <div class='row  row-query-list' >
            <div class='col-xs-12 col-md-5'>
                <div id='id_date_range' >
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <div class="zTreeDemoBackground left">
		    <ul id="treeDemo" class="ztree"></ul>
	  </div>
    <button id="confirm">确定</button>
    <table class="common-table">
    </table>

@include('layouts.page')
</section>
@endsection
