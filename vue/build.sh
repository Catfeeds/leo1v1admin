#!/bin/bash
cd $(dirname $0)
./gen_route.php
npm run build
if [ $? -ne 0 ] ;then
	echo "build fail.";
	exit
fi
sed -i -e "s/self\./p./g" ./dist/index.html

tar czvf  dist.tar.gz dist
echo "cp dist.tar.gz to 0.6"
sshpass -p xcwen142857 scp dist.tar.gz jim@192.168.0.6:~/dist.tar.gz
echo "rm -rf ~/admin.yb1v1.com/vue/dist &&  tar xvf ~/dist.tar.gz  -C ~/admin.yb1v1.com/vue/  "

sshpass -p xcwen142857 ssh jim@192.168.0.6 " rm -rf ~/admin.yb1v1.com/vue/dist && tar xvf ~/dist.tar.gz  -C ~/admin.yb1v1.com/vue/"
