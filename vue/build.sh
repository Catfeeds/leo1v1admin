#!/bin/bash
cd $(dirname $0)
npm run build
sed -i -e "s/self\.//g" ./dist/index.html
