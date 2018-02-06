<script type="text/javascript" src="/js/pdfobject.js"></script>
<script
              src="http://code.jquery.com/jquery-3.3.1.min.js"
              integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
              crossorigin="anonymous"></script>
<div class="col-md-12 look-pdf"   style="width:100%;height:100%;position:fixed;background:#eee;display:none;z-index:8888;overflow:hidden;">
    <div class="look-pdf-son">
    </div>
</div>
<span style="display: none;" id="url">
    {{@$url}}
</span>
 <!-- <embed width="800" height="600" src="{{@$ret_info['url']}}"> </embed> -->
<script type="text/javascript">
    var url = $("#url").text();
    $('.look-pdf').show();
    $('.look-pdf-son').mousedown(function(e){
        if(e.which == 3){
            return false;
        }
    });
    //PDFObject.embed(ret.url).css({'width':'120%','height':'120%','margin':'-10%'});
    PDFObject.embed(url, ".look-pdf-son");
    $('.look-pdf-son').css({'width':'100%','height':'100%','margin':'0%','margin-top':'-50px'});
</script>