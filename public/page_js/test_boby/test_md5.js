/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_boby-test_md5.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {

    });
}
$(function(){


    getEtag('sd',function(i){
        console.log(i);
    });



    $('.opt-change').set_input_change_event(load_data);
});

function calculate(){
    var fileReader = new FileReader(),
    box=document.getElementById('box');
    blobSlice = File.prototype.mozSlice || File.prototype.webkitSlice || File.prototype.slice,
    file = document.getElementById("file").files[0],
    chunkSize = 2097152,
    // read in chunks of 2MB
    chunks = Math.ceil(file.size / chunkSize),
    currentChunk = 0,
    spark = new SparkMD5();

    fileReader.onload = function(e) {
        console.log("read chunk nr", currentChunk + 1, "of", chunks);
        spark.appendBinary(e.target.result); // append binary string
        currentChunk++;

        if (currentChunk < chunks) {
            loadNext();
        }
        else {
            console.log("finished loading");
            box.innerText='MD5 hash:'+spark.end();
            console.info("computed hash", spark.end()); // compute hash
        }
    };

    function loadNext() {
        var start = currentChunk * chunkSize,
        end = start + chunkSize >= file.size ? file.size : start + chunkSize;

        fileReader.readAsBinaryString(blobSlice.call(file, start, end));
    };

    loadNext();
}
