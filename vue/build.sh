#!/bin/bash
cd $(dirname $0)
./gen_route.php
npm run build
sed -i -e "s/self\.//g" ./dist/index.html
