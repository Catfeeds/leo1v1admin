#!/bin/bash

# 加入新文件

git add app/ config/ database/  public/ resources/ tests/

msg=$1
if [ "$msg" = "" ] ;then
    msg="fix"
fi

git commit -a -m "$msg" &&  git push

git push

