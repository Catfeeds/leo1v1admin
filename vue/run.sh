#!/bin/bash
cd $(dirname $0)
./gen_route.php
npm run dev
