#!/bin/bash
cd `dirname $0`

mkdir -p ../app/Enums/

ls config_*.php  | php gen_get_in_func_def.php

./enum_gen.php
cp  enum_map.js ../public/page_js/
