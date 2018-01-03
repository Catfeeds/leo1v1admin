<?php
//表格
function t_field($filed_item,$comment) {
    \App\Helper\Utils::comment_field($filed_item ,$comment);
}


//表注释
function t_comment($table,$comment) {
    $table->comment=bin2hex($comment) ;
}
