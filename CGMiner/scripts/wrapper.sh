#!/bin/bash

# example - input format: address,port
# ./wrapper_stratum.sh stratum_down.csv > cgminer.conf.s0

# !!! removal of comma is needed from last configuration section !!!

FILE=$1
USER="Bitcoin"
PASS="x"
TAB="    "
[ ! -f $FILE ] && { echo "$INPUT file not found"; exit 99; }



# read -r d1 d2 d3 d4; do # ...

echo "{"
echo "    \"pools\" : ["
# while read addr,port
while IFS=",$IFS" read addr port
do
	echo "    {"
	echo "        \"url\" : \"http://"$addr":"${port::-1}"\","
	echo "        \"user\" : \""$USER"\","
	echo "        \"pass\" : \""$PASS"\""
	echo "    },"
done < $FILE
# !!! remove last comma !!! #
echo "]"

