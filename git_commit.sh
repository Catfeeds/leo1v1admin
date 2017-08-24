#!/bin/bash
msg= $1  
if [ "$msg" == "" ] ;then
    msg="fix"
fi
git commit  -a -m $msg 
