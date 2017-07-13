#!/bin/bash
cd `dirname $0`

mkdir -p ../app/Enums/

ls config_*.php  | php gen_get_in_func_def.php

for f in  $(ls config_*.php ) ;
do
    echo "do" $f;
    ./code_gen.php $f

done

js_file="./enum_map.js"

echo "var g_enum_map={"> $js_file

for f in  $(ls config_*.php ) ;
do
    echo "js do" $f;
    ./js_code_gen.php $f >>$js_file
done

echo  "__END: {}">> $js_file
echo  "};">> $js_file
cp $js_file  ../public/page_js/
