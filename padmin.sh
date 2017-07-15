if [ "" == "$1" ]; then
    echo "need desc , padmin.sh xxxx"
    exit
fi
cd ~/admin_yb1v1/ && git pull  && ./add_new.sh &&  git commit  -a -m "$1" && git push   && sshpass -p"xcwen142857" ssh -2  -o "StrictHostKeyChecking no" -p22 -l"jim" 192.168.0.6 "cd ~/admin.yb1v1.com && git pull && ~/bin/publish_admin.sh" 
