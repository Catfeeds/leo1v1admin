#!/bin/bash
cd `dirname $0`
opt_date=$(date +%Y%m%d-%H:%M:%S)
sed -i -e  "s/__WX_VRESION__/$opt_date/"  www/*.html
cd www/
tar czvf ../wx_www.tar.gz *
cd ../
sshpass  -p xcwen142857 scp wx_www.tar.gz jim@192.168.0.6:~/

sshpass  -p xcwen142857 ssh jim@192.168.0.6 "tar xvf wx_www.tar.gz  -C /var/www/wx-teacher-web/"
