#!/bin/bash
# 加入新文件
git add app/ config/ database/  public/ resources/ tests/

#
git commit -a -m "$1" &&  git push

git push
