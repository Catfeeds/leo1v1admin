#!/bin/bash
work_dir=$(pwd)
echo "生成枚举"
#cd $work_dir/enum_config/ && ./gen.sh
echo "生成 表结构"
cd $work_dir/table_config/ && ./_z_gen_table

