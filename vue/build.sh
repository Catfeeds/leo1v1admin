#!/bin/bash
cd $(dirname $0)
./gen_route.php
npm run build
sed -i -e "s/self\.//g" ./dist/index.html

tar czvf  dist.tar.gz dist
#sshpass xcwen142857 scp jim@192.168.0.6:~/admin.yb1v1.com/vue/
