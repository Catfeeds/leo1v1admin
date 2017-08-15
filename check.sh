#!/bin/bash
echo "check http://self.admin.yb1v1.com/test_control/test";
check_html_data=$(wget  "http://self.admin.yb1v1.com/test_control/test" 2>/dev/null  -O -)

if  [ "$check_html_data" == "succ" ]  ;then
    echo "succ"
else 
    echo "fail"
fi
