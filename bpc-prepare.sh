#!/bin/bash

rm -rf ./Workerman
rsync -a                        \
      --exclude=".*"            \
      --exclude="examples"      \
      --exclude="Events/React"  \
      -f"+ */"                  \
      -f"- *"                   \
      .                         \
      ./Workerman
for i in `cat src.list`
do
    filename=`basename -- $i`
    if [ "${filename##*.}" == "php" ]
    then
		echo "phptobpc $i"
        phptobpc $i > ./Workerman/$i
    else
		echo "cp       $i"
        cp $i ./Workerman/$i
    fi
done
cp src.list Makefile ./Workerman/
