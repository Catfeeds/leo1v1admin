<?php
function exec_cmd($cmd){
        \App\Helper\Utils::logger("EXEC: $cmd");

        $fp  = popen("$cmd", "r");
        $ret = "";
        while(!feof($fp)) {
            $ret .=fread($fp, 1024);
        }
        fclose($fp);
        return $ret;
}

