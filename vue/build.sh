#!/bin/bash
cd $(dirname $0)
./gen_route.php
npm run build
sed -i -e "s/self\.//g" ./dist/index.html

tar czvf  dist.tar.gz dist
echo "cp dist.tar.gz to 0.6"
sshpass -p xcwen142857 scp dist.tar.gz jim@192.168.0.6:~/dist.tar.gz
echo "tar xvf ~/dist.tar.gz  -C ~/admin.yb1v1.com/vue/  "
sshpass -p xcwen142857 ssh jim@192.168.0.6 "tar xvf ~/dist.tar.gz  -C ~/admin.yb1v1.com/vue/"
