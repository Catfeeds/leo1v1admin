#!/bin/bash

phpunit
if [ $?  -ne 0 ] ;then
  echo  "TEST ERROR. end"
  exit;
fi

# 加入新文件

git add app/ config/ database/  public/ resources/ tests/

msg=$1
if [ "$msg" = "" ] ;then
    msg="fix"
fi

git commit -a -m "$1" &&  git push

git push

