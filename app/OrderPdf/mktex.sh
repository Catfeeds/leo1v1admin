#!/bin/bash
export PATH=$PATH:/usr/bin/
cd /tmp/
/usr/bin/xelatex $1 &>/dev/null
