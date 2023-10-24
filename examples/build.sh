#!/bin/bash

EXAMPLES=`ls *.php`

mkdir -p build
cp autoload.inc build/

for i in $EXAMPLES
do
    echo "prepare $i"
    phptobpc $i > build/$i
done

cd build
for i in $EXAMPLES
do
    echo "compile $i"
    bpc --static \
        -c ../../workerman-bpc.conf \
        -u workerman \
        -d max_execution_time=-1 \
        -d display_errors=on \
        -d date.timezone=Asia/Shanghai \
        -d memory_limit=512M \
        $i autoload.inc
done
