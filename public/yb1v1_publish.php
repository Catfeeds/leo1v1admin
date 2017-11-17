<?php

$data=json_encode ( $_REQUEST );

file_put_contents("/tmp/git_push.log", $data );
